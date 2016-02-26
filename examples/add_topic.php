<?php

require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

$topic_object1 = new Topic();
$topic_object1->setName('Events')
	->setIsRoot(true)
	->save();

$topic_object2 = new Topic();
$topic_object2->setName('Places')
	->save();

$topic_object3 = new Topic();
$topic_object3->setName('Sodom and Gomorrah')
	->save();

$topic_parent1 = new TopicParent();
$topic_parent1->setTopic($topic_object2)
	->setParentId(1)
	->save();

$topic_parent2 = new TopicParent();
$topic_parent2->setTopic($topic_object3)
	->setParentId(1)
	->save();

$root_topics = new TopicQuery();
$root_topics = $root_topics->filterByIsRoot(true)
	->find();

var_dump($root_topics->toArray());