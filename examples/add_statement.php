<?php

require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';

$response_object = new Response();
$response_object->setText('Are you absolutely sure?')
	->save();

$statement_object = new Statement();
$statement_object->setResponse($response_object)
	->setText('There are no absolutes.');