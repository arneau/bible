<?php

require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

$root_topic = TopicQuery::create()
	->findRoot();

if (!$root_topic) {
	$root_topic = new Topic();
	$root_topic->setName('Topics')
		->makeRoot()
		->save();
}