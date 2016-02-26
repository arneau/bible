<?php

require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

$topic_children_data = getTopicChildrenData('1');

var_dump($topic_children_data);