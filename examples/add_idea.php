<?php

require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

$idea_object = IdeaQuery::create()
	->filterByTitle('God is light')
	->findOneOrCreate();

if ($idea_object->isNew()) {
	$idea_object->save();
}

$passage_data = getPassageData('Genesis 1:1');

foreach ($passage_data['verses'] as $verse_data) {

	$idea_verse_object = new IdeaVerse();
	$idea_verse_object->setVerseId($verse_data['id'])
		->setIdea($idea_object)
		->save();

}