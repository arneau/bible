<?php

require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

$root_lesson = LessonQuery::create()
	->findRoot();

if (!$root_lesson) {
	$root_lesson = new Lesson();
	$root_lesson->setTitle('Lessons')
		->makeRoot()
		->save();
}

$lesson_object1 = new Lesson();
$lesson_object1->setTitle('God is light')
	->insertAsLastChildOf($root_lesson)
	->save();

$lesson_object2 = new Lesson();
$lesson_object2->setTitle('God\'s will be our light for eternity')
	->insertAsLastChildOf($lesson_object1)
	->save();