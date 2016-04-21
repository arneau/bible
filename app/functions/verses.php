<?php

function getVerse($verse_id) {

	# Get verse object
	$verse_object = VerseQuery::create()
		->findOneById($verse_id);

	# Return verse object
	return $verse_object;

}

function getVerseByReference($reference_string) {

	# Get reference data
	$reference_data = getReferenceData($reference_string);

	# Get book object
	$book_object = BookQuery::create()
		->filterByName($reference_data['book'])
		->findOne();

	# Get verse object
	$verse_object = VerseQuery::create()
		->filterByBook($book_object)
		->filterByChapterNumber($reference_data['chapter'])
		->filterByVerseNumber($reference_data['verses'])
		->findOne();

	# Return verse object
	return $verse_object;

}

function getVerseData($verse_id) {

	# Get verse object
	$verse_object = getVerse($verse_id);

	# Get book object
	$book_object = $verse_object->getBook();

	# Define verse data
	$verse_data = [
		'id' => $verse_object->getId(),
		'book' => [
			'name' => $book_object->getName(),
		],
		'chapter' => $verse_object->getChapterNumber(),
		'number' => $verse_object->getVerseNumber(),
	];
	$verse_data['reference'] = $verse_data['book']['name'] . ' ' . $verse_data['chapter'] . ':' . $verse_data['number'];

	# Return verse data
	return $verse_data;

}

function getVerseDataByReference($reference_string) {

	# Get verse object
	$verse_object = getVerseByReference($reference_string);

	# Get verse data
	$verse_data = getVerseData($verse_object->getId());

	# Return verse data
	return $verse_data;

}

function getVerseReference($verse_id) {

	# Get verse object
	$verse_object = getVerse($verse_id);

	# Get book object
	$book_object = $verse_object->getBook();

	# Define verse reference
	$verse_reference = $book_object->getName() . ' ' . $verse_object->getChapterNumber() . ':' . $verse_object->getVerseNumber();

	# Return verse reference
	return $verse_reference;

}

function getVerseTranslation($verse_translation_id) {

	# Get verse translation object
	$verse_translation_object = VerseTranslationQuery::create()
		->findOneById($verse_translation_id);

	# Return verse translation object
	return $verse_translation_object;

}

function getVerseTranslationByVerseId($verse_id, $bible_id) {

	# Get verse translation object
	$verse_translation_object = VerseTranslationQuery::create()
		->filterByVerseId($verse_id)
		->filterByBibleId($bible_id)
		->findOne();

	# Return verse translation object
	return $verse_translation_object;

}

function getVerseTranslationData($verse_translation_id) {

	# Get verse translation object
	$verse_translation_object = getVerseTranslation($verse_translation_id);

	# Define verse translation words array
	$verse_translation_words_array = explode(' ', $verse_translation_object->getText());

	# Define verse translation data
	$verse_translation_data = [
		'id' => $verse_translation_object->getId(),
		'text' => $verse_translation_object->getText(),
		'word_count' => $verse_translation_object->getWordCount(),
		'words' => $verse_translation_words_array,
	];

	# Return verse translation data
	return $verse_translation_data;

}