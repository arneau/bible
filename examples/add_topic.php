<?php

require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';

$topic_object = new Topic();
$topic_object->setName('Topics')
	->makeRoot()
	->save();

$topic_synonym_object = new TopicSynonym();
$topic_synonym_object->setName($topic_object->getName())
	->setTopic($topic_object)
	->save();