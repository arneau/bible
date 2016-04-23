<?php

function getPassageData($reference_string, $bible_code = 'kjv') {

	# Stop if no reference string provided
	if (!$reference_string) {
		return;
	}

	# Get reference data
	$reference_data = getReferenceData($reference_string);

	# Get bible object
	$bible_object = BibleQuery::create()
		->filterByCode($bible_code)
		->findOne();

	# Get book object
	$book_object = BookQuery::create()
		->filterByName($reference_data['book'])
		->findOne();

	# Define passage data
	$passage_data = [
		'bible' => [
			'code' => [
				'default' => $bible_object->getCode(),
				'formatted' => strtoupper($bible_object->getCode()),
			],
			'id' => $bible_object->getId(),
			'name' => $bible_object->getName(),
		],
		'book' => [
			'id' => $book_object->getId(),
			'name' => $book_object->getName(),
		],
		'chapter' => [
			'number' => $reference_data['chapter'],
		],
		'reference' => [
			'string' => $reference_string,
		],
		'verses' => $reference_data['verses'],
	];

	# Return passage data
	return $passage_data;

}

function getPassageDataAndVerses($reference_string, $bible_code = 'kjv') {

	# Get passage data
	$passage_data = getPassageData($reference_string, $bible_code);

	# Get verses objects
	$verses_objects = VerseQuery::create()
		->filterByBookId($passage_data['book']['id'])
		->filterByChapterNumber($passage_data['chapter']['number'])
		->_if($passage_data['verses'])
		->filterByVerseNumber($passage_data['verses'])
		->_endif()
		->find();

	# Handle verse objects
	$passage_data['verses'] = [];
	foreach ($verses_objects as $verse_object) {

		# Get translation object
		$verse_translation_object = VerseTranslationQuery::create()
			->filterByBibleId($passage_data['bible']['id'])
			->filterByVerseId($verse_object->getId())
			->findOne();

		# Get verse topics tags data
		$verse_topics_tags_data = getVerseTopicsTagsData($verse_object->getId());

		# Get verse lessons tags data
		$verse_lessons_tags_data = getVerseLessonsTagsData($verse_object->getId());

		# Handle verse words
		$verse_translation_text_array = explode(' ', $verse_translation_object->getText());
		foreach ($verse_translation_text_array as $word_number => &$word_value) {
			$index ++;
			$word_value = '<span data-verse="' . $verse_object->getId() . '" data-word="' . $index . '">' . $word_value . '</span>';
		}
		$verse_translation_text_array = implode(' ', $verse_translation_text_array);

		# Append verse data
		$passage_data['verses'][] = [
			'id' => $verse_object->getId(),
			'number' => $verse_object->getVerseNumber(),
			'tags' => [
				'lessons' => $verse_lessons_tags_data,
				'topics' => $verse_topics_tags_data,
			],
			'text' => [
				'array' => $verse_translation_text_array,
				'string' => $verse_translation_object->getText(),
			],
			'word_count' => $verse_translation_object->getWordCount(),
		];

	}

	# Return passage data
	return $passage_data;

}

function getPassageHTMLByVerseId($verse_id, $bible_code = 'kjv', $relevant_words = [], $vote_count = 0) {

	# Get verse reference
	$verse_reference = getVerseReference($verse_id);

	# Get passage HTML
	$passage_html = getPassageHTML($verse_reference, $bible_code, $relevant_words, $vote_count);

	# Return passage HTML
	return $passage_html;

}

function getReferenceData($reference_string) {

	# Preg match parts
	preg_match('/(\d?\s?\w*)\s+(\d+):?(.+)?/', $reference_string, $reference_parts);

	# Check parts
	if ($reference_parts && $reference_parts[1] && $reference_parts[2]) {

		# Handle parts
		$reference_data['book'] = $reference_parts[1];
		$reference_data['chapter'] = $reference_parts[2];
		if (isset($reference_parts[3])) {
			$reference_data['verses'] = getNumbersArrayFromString($reference_parts[3]);
		} else {
			$reference_data['verses'] = [];
		}

		# Return reference data
		return $reference_data;

	} else {

		# Return error
		return false;

	}

}

function getPassageHTML($passage_html_data = []) {

	# Get bible object
	if ($passage_html_data['bible_id']) {
		$bible_object = getBible($passage_html_data['bible_id']);
	} elseif ($passage_html_data['bible_code']) {
		$bible_object = getBibleByCode($passage_html_data['bible_code']);
	} else {
		$bible_object = getBibleByCode('kjv');
	}

	# Get bible data
	$bible_data = getBibleData($bible_object->getId());

	if ($passage_html_data['verse_id']) {

		# Get verse object
		$tag_verses_objects = [
			getVerse($passage_html_data['verse_id'])
		];

	} elseif ($passage_html_data['tag_id']) {

		# Get tag object
		$tag_object = getTag($passage_html_data['tag_id']);

		# Get tag data
		$tag_data = getTagData($tag_object->getId());

		# Get tag highlighter object
		$tag_highlighter_object = getTagHighlighter($passage_html_data['tag_highlighter_id']);

		# Get tag highlighter data
		$tag_highlighter_data = getTagHighlighterData($tag_highlighter_object->getId());

		# Get tag verses ids
		$tag_verses_ids = $tag_object->getTagVerses()
			->getPrimaryKeys();

		# Get verses objects
		$verses_objects = VerseQuery::create()
			->useTagVerseQuery()
			->filterByPrimaryKeys($tag_verses_ids)
			->endUse()
			->find();

	}

	# Start passage HTML
	$passage_html = <<<s
<blockquote class="passage" data-tag-highlighter="{$tag_highlighter_data['id']}">
	<div class="text">
s;

	# Add each verse to passage HTML
	foreach ($verses_objects as $verse_object) {

		$verse_data = getVerseData($verse_object->getId());

		$verse_translation_object = getVerseTranslationByVerseId($verse_object->getId(), $bible_object->getId());
		$verse_translation_data = getVerseTranslationData($verse_translation_object->getId());

		$word_number = $verse_translation_data['previous_verses_word_count'] + 1;

		$passage_html .= <<<s
		<p>
			<sup>{$verse_data['number']}</sup>
s;

		foreach ($verse_translation_data['words'] as $word_value) {

			$passage_html .= <<<s
		<span class="word" data-word="{$word_number}">{$word_value}</span>
s;

			$word_number ++;

		}

		$passage_html .= <<<s
		</p>
s;

	}

	$passage_html .= <<<s
	</div>
	<div class="footer">
		<cite>
			<span class="reference">{$passage_html_data['reference_string']}</span> &middot;
			<span class="bible" data-info="{$bible_data['name']}">{$bible_data['code']['formatted']}</span>
		</cite>
s;

	# Add tag elements (if applicable)
	if ($passage_html_data['tag_id']) {

		$passage_html .= <<<s
		<div class="votes">
			<span class="vote_count">{$tag_data['vote_count']}</span> votes
			<span class="vote_up icon-arrow-up"></span>
			<span class="vote_down icon-arrow-down"></span>
		</div>
		<div class="relevant_words">
			<span class="edit icon-pencil" onclick="editTagHighlighter({$tag_highlighter_data['id']});"></span>
			<span class="confirm icon-tick" onclick="updateTagHighlighter({$tag_highlighter_data['id']});"></span>
		</div>
		<div class="tag">
			<span class="delete icon-close" onclick="deleteTag({$passage_html_data['tag_id']});"></span>
		</div>
s;

	}

	# Continue passage HTML
	$passage_html .= <<<s
	</div>
</blockquote>
s;

	# Add highlighting (if applicable)
	if ($passage_html_data['tag_highlighter_id']) {

		# Add words to highlight javascript
		$passage_html .= <<<s
<script>
	$(document).ready(function() {
		applyTagHighlighter({$tag_highlighter_data['id']}, '{$tag_highlighter_data['relevant_words']}');
	});
</script>
s;

	}

	# Return passage HTML
	return $passage_html;

}

function getVerseTopicsTagsData($verse_id) {

	# Get verse topics tags objects
	$verse_topics_tags_objects = TopicTagQuery::create()
		->useTagQuery()
		->filterByVerseId($verse_id)
		->orderByVoteCount('DESC')
		->endUse()
		->find();

	# Handle verse topics tags objects
	$verse_topics_tags_data = [];
	foreach ($verse_topics_tags_objects as $verse_topic_tag_object) {

		# Get topic object
		$verse_topic_object = $verse_topic_tag_object->getTopic();

		# Get tag object
		$verse_tag_object = $verse_topic_tag_object->getTag();

		# Append tag data
		$verse_topics_tags_data[] = [
			'id' => $verse_topic_tag_object->getId(),
			'tag' => [
				'id' => $verse_tag_object->getId(),
				'vote_count' => $verse_tag_object->getVoteCount(),
			],
			'topic' => [
				'id' => $verse_topic_object->getId(),
				'name' => $verse_topic_object->getName(),
			],
		];

	}

	# Return verse tag data
	return $verse_topics_tags_data;

}

function getVerseLessonsTagsData($verse_id) {

	# Get verse lessons tags objects
	$verse_lessons_tags_objects = LessonTagQuery::create()
		->useTagQuery()
		->filterByVerseId($verse_id)
		->orderByVoteCount('DESC')
		->endUse()
		->find();

	# Handle verse lessons tags objects
	$verse_lessons_tags_data = [];
	foreach ($verse_lessons_tags_objects as $verse_lesson_tag_object) {

		# Get lesson object
		$verse_lesson_object = $verse_lesson_tag_object->getLesson();

		# Get tag object
		$verse_tag_object = $verse_lesson_tag_object->getTag();

		# Append tag data
		$verse_lessons_tags_data[] = [
			'id' => $verse_lesson_tag_object->getId(),
			'tag' => [
				'id' => $verse_tag_object->getId(),
				'vote_count' => $verse_tag_object->getVoteCount(),
			],
			'lesson' => [
				'id' => $verse_lesson_object->getId(),
				'name' => $verse_lesson_object->getSummary(),
			],
		];

	}

	# Return verse tag data
	return $verse_lessons_tags_data;

}

function getNumbersArrayFromString($numbers_string) {

	# Handle string
	$numbers_array = [];
	foreach (explode(',', $numbers_string) as $string_part) {

		# Check if string part contains range
		if (strpos($string_part, '-')) {

			# Get string string part limits
			$string_part_limits = explode('-', $string_part);

			# Get string part range
			$string_part_range = range($string_part_limits[0], $string_part_limits[1]);

			# Append multiple verse numbers to numbers array
			$numbers_array = array_merge($numbers_array, $string_part_range);

		} else {

			# Append single verse to numbers array
			$numbers_array[] = $string_part * 1;
		}

	}

	# Return numbers array
	return $numbers_array;

}

function getNumbersStringFromArray($numbers_array) {

	# Get unique numbers within numbers array
	$numbers_array = array_unique($numbers_array);

	# Sort numbers array
	sort($numbers_array);

	# Define numbers array start and end points
	$numbers_array_start_points = [];
	$numbers_array_end_points = [];
	for ($index = 0; $index < count($numbers_array); $index ++) {

		# Check if start point
		if ($index == 0 || $numbers_array[$index - 1] + 1 < $numbers_array[$index]) {
			$numbers_array_start_points[] = $index;
		}

		# Check if end point
		if ($numbers_array[$index] < $numbers_array[$index + 1] - 1 || $index == count($numbers_array) - 1) {
			$numbers_array_end_points[] = $index;
		}

	}

	# Define numbers string parts
	$numbers_string_parts = [];
	for ($index = 0; $index < count($numbers_array_start_points); $index ++) {

		# Get start and end numbers
		$start_number = $numbers_array[$numbers_array_start_points[$index]];
		$end_number = $numbers_array[$numbers_array_end_points[$index]];

		# Check if start point
		if ($start_number != $end_number) {
			$numbers_string_parts[] = $start_number . '-' . $end_number;
		} else {
			$numbers_string_parts[] = $start_number;
		}

	}

	# Define numbers string
	$numbers_string = implode(',', $numbers_string_parts);

	# Return numbers string
	return $numbers_string;

}