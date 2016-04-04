<?php

function getBible($bible_id) {

	# Get bible object
	$bible_object = BibleQuery::create()
		->findOneById($bible_id);

	# Return bible object
	return $bible_object;

}

function getBibleByCode($bible_code) {

	# Get bible object
	$bible_object = BibleQuery::create()
		->filterByCode($bible_code)
		->findOne();

	# Return bible object
	return $bible_object;

}

function getBibleData($bible_id) {

	# Get bible object
	$bible_object = getBible($bible_id);

	# Define bible data
	$bible_data = [
		'code' => [
			'default' => $bible_object->getCode(),
			'formatted' => strtoupper($bible_object->getCode()),
		],
		'id' => $bible_object->getId(),
		'name' => $bible_object->getName(),
	];

	# Return bible data
	return $bible_data;

}

function getBibleDataByCode($bible_code) {

	# Get bible object
	$bible_object = getBibleByCode($bible_code);

	# Get bible data
	$bible_data = getBibleData($bible_object->getId());

	# Return bible data
	return $bible_data;

}