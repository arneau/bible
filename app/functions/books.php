<?php

function getBook($book_id) {

	# Get book object
	$book_object = BookQuery::create()
		->findOneById($book_id);

	# Return book object
	return $book_object;

}

function getBookByName($book_name) {

	# Get book object (if possible)
	$book_object = BookQuery::create()
		->findOneByName($book_name);

	# Get book object by abbreviation (if necessary)
	if (!$book_object) {

		$book_abbreviation_object = BookAbbreviationQuery::create()
			->findOneByName($book_name);

		if ($book_abbreviation_object) {
			$book_object = $book_abbreviation_object->getBook();
		}
	}

	# Return book object
	return $book_object;

}