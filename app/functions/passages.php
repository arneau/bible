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

function getPassageHTML($passage_data = []) {

	# Get bible object
	if ($passage_data['bible_id']) {
		$bible_object = getBible($passage_data['bible_id']);
	} elseif ($passage_data['bible_code']) {
		$bible_object = getBibleByCode($passage_data['bible_code']);
	} else {
		$bible_object = getBibleByCode('kjv');
	}

	# Get verse object
	if ($passage_data['verse_id']) {
		$verse_object = getVerse($passage_data['verse_id']);
	} elseif ($passage_data['tag_id']) {
		$tag_object = getTag($passage_data['tag_id']);
		$verse_object = $tag_object->getVerse();
	}

	# Get verse translation object and data
	$verse_translation_object = getVerseTranslationByVerseId($verse_object->getId(), $bible_object->getId());
	$verse_translation_data = getVerseTranslationData($verse_translation_object->getId());

	var_dump($verse_translation_data);
	die;

	# Get passage data
	$passage_data = getPassageDataAndVerses($reference_string, $bible_code);

	# Start passage HTML
	$passage_html = <<<s
<blockquote class="passage">
	<p>
		<sup>{$passage_data[verses][0][number]}</sup>
		{$passage_data[verses][0][text]['array']}
	</p>
	<cite>
		<span class="reference">{$passage_data[reference][string]}</span> &middot;
		<span class="bible" data-info="{$passage_data[bible][name]}">{$passage_data[bible][code][formatted]}</span> &middot;
		<span class="vote_count">$vote_count</span> votes
		<span class="vote_up">
			<img src="assets/images/arrow_up.png" />
		</span>
		<span class="vote_down">
			<img src="assets/images/arrow_down.png" />
		</span> &middot;
		<input data-action="update_translation_relevant_words" data-tag-translation="" type="text" value="$words_to_highlight" />
	</cite>
</blockquote>
s;

	# Add highlighting (if applicable)
	if ($words_to_highlight) {

		# Get words to highlight array
		$words_to_highlight_array = getUniqueNumbers($words_to_highlight);

		# Handle words to highlight
		$passage_html .= <<<s
<script>
s;

		foreach ($words_to_highlight_array as $word_to_highlight_number) {
			$passage_html .= <<<s
	$('[data-verse={$passage_data[verses][0][id]}][data-word=$word_to_highlight_number]').addClass('highlighted');
s;
		}

		$passage_html .= <<<s
</script>
s;

		}

		# Return passage HTML
		return $passage_html;

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
				$reference_data['verses'] = getUniqueNumbers($reference_parts[3]);
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

	function getUniqueNumbers($reference_string) {

		# Handle reference string
		$numbers_to_return = [];
		foreach (explode(',', $reference_string) as $reference_string_part) {

			# Check if reference string part contains range
			if (strpos($reference_string_part, '-')) {

				# Get reference string part range
				$reference_string_range = explode('-', $reference_string_part);

				# Get numbers array
				$numbers_array = range($reference_string_range[0], $reference_string_range[1]);

				# Append numbers array to numbers to return
				foreach ($numbers_array as $number) {
					$numbers_to_return[] = $number;
				}

			} else {

				# Append number to numbers to return
				$numbers_to_return[] = $reference_string_part;
			}

		}

		# Return numbers
		return $numbers_to_return;

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