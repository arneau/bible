<?php

function addTopicTag($topic_id, $verse_id, $bible_code, $relevant_words) {

	# Add tag object
	$tag_object = new Tag();
	$tag_object->setVerseId($verse_id)
		->save();

	# Get bible object
	$bible_object = getBibleByCode($bible_code);

	# Add tag translation object
	$tag_translation_object = new TagTranslation();
	$tag_translation_object->setBible($bible_object)
		->setRelevantWords($relevant_words)
		->setTag($tag_object)
		->save();

	# Add topic tag object
	$topic_tag_object = new TopicTag();
	$topic_tag_object->setTopicId($topic_id)
		->setTag($tag_object)
		->save();

}

function addLessonTag($lesson_id, $verse_id, $bible_code, $relevant_words) {

	# Add tag object
	$tag_object = new Tag();
	$tag_object->setVerseId($verse_id)
		->save();

	# Get bible object
	$bible_object = getBibleByCode($bible_code);

	# Add tag translation object
	$tag_translation_object = new TagTranslation();
	$tag_translation_object->setBible($bible_object)
		->setRelevantWords($relevant_words)
		->setTag($tag_object)
		->save();

	# Add lesson tag object
	$lesson_tag_object = new LessonTag();
	$lesson_tag_object->setLessonId($lesson_id)
		->setTag($tag_object)
		->save();

}