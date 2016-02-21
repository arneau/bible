<?php

require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';

$answer_type_object = AnswerTypeQuery::create()
	->filterByValue('default')
	->findOneOrCreate();

if ($answer_type_object->isNew()) {
	$answer_type_object->save();
}