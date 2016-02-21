<?php

require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

$passage_data = getPassageData('Genesis 1:1');

$keyword_object = KeywordQuery::create()
	->filterByValue('Creation')
	->findOneOrCreate();

if ($keyword_object->isNew()) {

	$keyword_object->save();

	$keyword_synonym_object = new KeywordSynonym();
	$keyword_synonym_object->setKeyword($keyword_object)
		->setValue($keyword_object->getValue())
		->save();

}

foreach ($passage_data['verses'] as $verse_data) {

	$tag_object = new Tag();
	$tag_object->setKeyword($keyword_object)
		->setRelevantWords('1-' . $verse_data['word_count'])
		->setVerseId($verse_data['id'])
		->save();

}