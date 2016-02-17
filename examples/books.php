<?php

require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';

$book = BookQuery::create()
	->filterByName('Genesis')
	->findOneOrCreate();

if ($book->isNew()) {
	$book->save();
}

var_dump($book->toArray());

$books = BookQuery::create()
	->find();

var_dump($books->toArray());