<?php

function getBook($book_id) {

	# Get book object
	$book_object = BookQuery::create()
		->findOneById($book_id);

	# Return book object
	return $book_object;

}