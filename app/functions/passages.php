<?php

function getReferenceData($reference_string) {

	# Preg match parts
	preg_match('/(\d?\s?\w*)\s+(\d+):?(.+)?/', $reference_string, $reference_parts);

	# Check parts
	if ($reference_parts && $reference_parts[1] && $reference_parts[2]) {

		# Handle parts
		$reference_data['book'] = $reference_parts[1];
		$reference_data['chapter'] = $reference_parts[2];
		if (isset($reference_parts[3])) {
			foreach (explode(',', $reference_parts[3]) as $verses_reference_part) {
				if (strpos($verses_reference_part, '-')) {
					$verses_reference_parts = explode('-', $verses_reference_part);
					$verses_reference_array = range($verses_reference_parts[0], $verses_reference_parts[1]);
					foreach ($verses_reference_array as $verse_reference_number) {
						$reference_data['verses'][] = $verse_reference_number;
					}
				} else {
					$reference_data['verses'][] = $verses_reference_part;
				}
			}
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

	# Get verses objects
	$verses_objects = VerseQuery::create()
		->filterByChapterNumber($reference_data['chapter'])
		->_if($reference_data['verses'])
		->filterByVerseNumber($reference_data['verses'])
		->_endif()
		->find();

	# Define passage data
	$passage_data = [
		'bible' => [
			'code' => $bible_object->getCode(),
			'name' => $bible_object->getName(),
		],
		'book' => [
			'name' => $book_object->getName(),
		],
		'chapter' => [
			'number' => $reference_data['chapter'],
		],
		'reference' => [
			'string' => $reference_string,
		],
	];

	# Handle verse objects
	foreach ($verses_objects as $verse_object) {

		# Get translation object
		$translation_object = TranslationQuery::create()
			->filterByBible($bible_object)
			->filterByVerseId($verse_object->getId())
			->findOne();

		# Get verse topics tags data
		$verse_topics_tags_data = getVerseTopicsTagsData($verse_object->getId());

		# Get verse lessons tags data
		$verse_lessons_tags_data = getVerseLessonsTagsData($verse_object->getId());

		# Append verse data
		$passage_data['verses'][] = [
			'id' => $verse_object->getId(),
			'number' => $verse_object->getVerseNumber(),
			'tags' => [
				'lessons' => $verse_lessons_tags_data,
				'topics' => $verse_topics_tags_data,
			],
			'text' => $translation_object->getText(),
			'word_count' => $translation_object->getWordCount(),
		];

	}

	# Return passage data
	return $passage_data;

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