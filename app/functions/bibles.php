<?php

function getBibleByCode($bible_code = 'kjv') {

	# Get bible object
	$bible_object = BibleQuery::create()
		->filterByCode($bible_code)
		->findOne();

	# Return bible object
	return $bible_object;

}