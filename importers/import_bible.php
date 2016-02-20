<?php

require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';

$bible_object = BibleQuery::create()
	->filterByCode('kjv')
	->filterByName('King James (Authorized Version)')
	->findOneOrCreate();

if ($bible_object->isNew()) {
	$bible_object->save();
}