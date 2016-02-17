<?php

require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';

$bible = BibleQuery::create()
	->filterByCode('kjv')
	->filterByName('King James (Authorized Version)')
	->findOneOrCreate();

if ($bible->isNew()) {
	$bible->save();
}

var_dump($bible->toArray());

$bibles = BibleQuery::create()
	->find();

var_dump($bibles->toArray());