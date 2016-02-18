<?php

require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

$bible = BibleQuery::create()
	->filterByCode('kjv')
	->findOne();

$book = BookQuery::create()
	->filterByName('Genesis')
	->findOne();

$verse = new Verse();
$verse->setBible($bible)
	->setBook($book)
	->setChapterNumber(1)
	->setVerseNumber(1)
	->setText('In the beginning God created the heaven and the earth.')
	->save();

$passage_data = getPassageData('Genesis 1:1');

var_dump($passage_data);