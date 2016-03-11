<?php

function getReferenceData($reference_string) {

	# Preg match parts
	preg_match('/(\d?\s?\w*)\s+(\d+):?(.+)?/', $reference_string, $reference_parts);

	# Check parts
	if ($reference_parts && $reference_parts[1] && $reference_parts[2]) {

		# Handle parts
		$reference_data['book'] = $reference_parts[1];
		$reference_data['chapter'] = $reference_parts[2];
		if ($reference_parts[3]) {
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
		}

		# Return reference data
		return $reference_data;
	} else {

		# Return error
		return false;
	}

}

function getPassageData($reference_string, $bible_code = 'kjv') {

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
		->filterByBook($book_object)
		->filterByChapterNumber($reference_data['chapter'])
		->filterByVerseNumber($reference_data['verses'])
		->useTranslationQuery()
		->filterByBible($bible_object)
		->endUse()
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
	];

	# Define verses data
	foreach ($verses_objects as $verse_object) {

		# Get translation object
		$translation_object = TranslationQuery::create()
			->filterByBible($bible_object)
			->filterByVerseId($verse_object->getId())
			->findOne();


		$verse_tags_array = getVerseTagsData($verse_object->getId());

		$passage_data['verses'][] = [
			'id' => $verse_object->getId(),
			'number' => $verse_object->getVerseNumber(),
			'tags' => $verse_tags_array,
			'text' => $translation_object->getText(),
			'word_count' => $translation_object->getWordCount(),
		];

	}

	# Return passage data
	return $passage_data;

}

function getVerseTagsData($verse_id) {

	# Get verse tags objects
	$verse_tags_objects = TagQuery::create()
		->filterByVerseId($verse_id)
		->orderByVoteCount('DESC')
		->find();

	# Handle verse tags objects
	$verse_tags_data = [];
	foreach ($verse_tags_objects as $verse_tag_object) {

		$verse_tag_keyword_object = $verse_tag_object->getKeyword();

		$verse_tags_data[] = [
			'id' => $verse_tag_object->getId(),
			'keyword_id' => $verse_tag_keyword_object->getId(),
			'value' => $verse_tag_keyword_object->getValue(),
			'vote_count' => $verse_tag_object->getVoteCount(),
		];

	}

	# Return verse tag data
	return $verse_tags_data;

}