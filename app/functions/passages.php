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
			$reference_data['verses'] = getVerseNumbers($reference_parts[3]);
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

function getVerseHTML($verse_html_data = []) {

	# Get bible object
	if ($verse_html_data['bible_id']) {
		$bible_object = getBible($verse_html_data['bible_id']);
	} elseif ($verse_html_data['bible_code']) {
		$bible_object = getBibleByCode($verse_html_data['bible_code']);
	} else {
		$bible_object = getBibleByCode('kjv');
	}

	# Get bible data
	$bible_data = getBibleData($bible_object->getId());

	if ($verse_html_data['verse_id']) {

		# Get verse object
		$verse_object = getVerse($verse_html_data['verse_id']);

	} elseif ($verse_html_data['tag_id']) {

		# Get tag object
		$tag_object = getTag($verse_html_data['tag_id']);

		# Get tag data
		$tag_data = getTagData($tag_object->getId());

		# Get tag translation object
		$tag_translation_object = getTagTranslationByTagId($tag_object->getId(), $bible_object->getId());

		# Get tag translation data
		$tag_translation_data = getTagTranslationData($tag_translation_object->getId());

		# Get verse object
		$verse_object = $tag_object->getVerse();

	}

	# Get verse data
	$verse_data = getVerseData($verse_object->getId());

	# Get verse translation object
	$verse_translation_object = getVerseTranslationByVerseId($verse_object->getId(), $bible_object->getId());

	# Get verse translation data
	$verse_translation_data = getVerseTranslationData($verse_translation_object->getId());

	# Start verse HTML
	$verse_html = <<<s
<blockquote class="verse" data-tag-translation="{$tag_translation_data['id']}">
	<div class="text">
		<sup>{$verse_data['number']}</sup>
		{$verse_translation_data['text']['formatted']}
	</div>
	<cite>
		<span class="reference">{$verse_data['reference']}</span> &middot;
		<span class="bible" data-info="{$bible_data['name']}">{$bible_data['code']['formatted']}</span>
	</cite>
s;

	# Add tag elements (if applicable)
	if ($verse_html_data['tag_id']) {
		$verse_html .= <<<s
	<div class="votes">
		<span class="vote_count">{$tag_data['vote_count']}</span> votes
		<span class="vote_up icon-arrow-up"></span>
		<span class="vote_down icon-arrow-down"></span>
	</div>
	<div class="relevant_words">
		<span class="edit icon-pencil" onclick="editTagTranslationRelevantWords({$tag_translation_data['id']});"></span>
		<span class="confirm icon-tick" onclick="confirmTagTranslationRelevantWords({$tag_translation_data['id']});"></span>
	</div>
s;
	}

	# Continue verse HTML
	$verse_html .= <<<s
</blockquote>
s;

	# Add highlighting (if applicable)
	if ($verse_html_data['tag_id']) {

		# Add words to highlight javascript
		$verse_html .= <<<s
<script>
	$(document).ready(function() {
		highlightTagTranslationWords({$tag_translation_data['id']}, '{$tag_translation_data['relevant_words']}');
	});
</script>
s;

	}

	# Return verse HTML
	return $verse_html;

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

function getVerseNumbers($verses_string) {

	# Handle verses string
	$verses_numbers_array = [];
	foreach (explode(',', $verses_string) as $verses_string_part) {

		# Check if verses string part contains range
		if (strpos($verses_string_part, '-')) {

			# Get verses string string part limits
			$verses_string_part_limits = explode('-', $verses_string_part);

			# Get verses string part range
			$verses_string_part_range = range($verses_string_part_limits[0], $verses_string_part_limits[1]);

			# Append multiple verse numbers to verses numbers array
			$verses_numbers_array = array_merge($verses_numbers_array, $verses_string_part_range);

		} else {

			# Append single verse to verses numbers array
			$verses_numbers_array[] = $verses_string_part * 1;
		}

	}

	# Return verses numbers array
	return $verses_numbers_array;

}

function getWordsToHighlight($words_to_highlight_string) {

	# Handle words to highlight string
	$words_to_highlight_array = [];
	foreach (explode(',', $words_to_highlight_string) as $words_to_highlight_string_part) {

		# Check if words to highlight string part contains range
		if (strpos($words_to_highlight_string_part, '-')) {

			# Get words to highlight string part limits
			$words_to_highlight_string_part_limits = explode('-', $words_to_highlight_string_part);

			# Get words to highlight string part range
			$words_to_highlight_string_part_range = range($words_to_highlight_string_part_limits[0], $words_to_highlight_string_part_limits[1]);

			# Append multiple words to highlight to words to highlight array
			$words_to_highlight_array = array_merge($words_to_highlight_array, $words_to_highlight_string_part_range);

		} else {

			# Append single word to highlight to words to highlight array
			$words_to_highlight_array[] = $words_to_highlight_string_part * 1;
		}

	}

	# Return words to highlight array
	return $words_to_highlight_array;

}