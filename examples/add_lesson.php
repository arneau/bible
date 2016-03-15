<?php

require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

$root_lesson_object = LessonQuery::create()
	->findRoot();

if (!$root_lesson_object) {
	$root_lesson_object = new Lesson();
	$root_lesson_object->setSummary('Lessons')
		->makeRoot()
		->save();
}