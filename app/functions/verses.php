<?php

function getVerse($verse_id) {

	# Get verse object
	$verse_object = VerseQuery::create()
		->findOneById($verse_id);

	# Return verse object
	return $verse_object;

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