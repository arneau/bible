<?php

require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';

$tag_object = TagQuery::create()
	->filterById(1)
	->findOne();

$tag_vote_object = new TagVote();
$tag_vote_object->setTag($tag_object)
	->save();