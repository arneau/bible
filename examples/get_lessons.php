<?php

require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

$root_lesson = LessonQuery::create()->findRoot();
foreach ($root_lesson->getBranch() as $node) {
	echo str_repeat('- ', $node->getLevel()) . $node->getTitle() . "\n";
}