<?php

function getVerse($verse_id) {

	# Get verse object
	$verse_object = VerseQuery::create()
		->findOneById($verse_id);

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

	# Handle verse translation words
	$verse_translation_words_array = explode(' ', $verse_translation_object->getText());
	foreach ($verse_translation_words_array as $word_number => &$word_value) {
		$word_number ++;
		$word_value = '<span data-verse-translation="' . $verse_translation_object->getId() . '" data-word="' . $word_number . '">' . $word_value . '</span>';
	}
	$verse_translation_text_formatted = implode(' ', $verse_translation_words_array);

	# Define verse translation data
	$verse_translation_data = [
		'id' => $verse_translation_object->getId(),
		'text' => [
			'default' => $verse_translation_object->getText(),
			'formatted' => $verse_translation_text_formatted,
		],
		'word_count' => $verse_translation_object->getWordCount(),
	];

	# Return verse translation data
	return $verse_translation_data;

}