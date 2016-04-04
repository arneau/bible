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

function getTag($tag_id) {

	# Get tag object
	$tag_object = TagQuery::create()
		->findOneById($tag_id);

	# Return tag object
	return $tag_object;

}

function getTagData($tag_id) {

	# Get tag object
	$tag_object = getTag($tag_id);

	# Define tag data
	$tag_data = [
		'id' => $tag_object->getId(),
		'vote_count' => $tag_object->getVoteCount(),
	];

	# Return tag data
	return $tag_data;

}

function getTagHTML($tag_id, $bible_code = 'kjv') {

	# Get tag object
	$tag_object = getTag($tag_id);

	# Get bible object
	$bible_object = getBibleByCode($bible_code);

	# Get tag translation object
	$tag_translation_object = TagTranslationQuery::create()
		->filterByBible($bible_object)
		->filterByTag($tag_object)
		->findOne();

	# Define verse HTML data
	$verse_html_data = [
		'bible_id' => $bible_object->getId(),
		'tag_id' => $tag_object->getId(),
		'tag_translation_id' => $tag_translation_object->getId(),
	];

	# Get tag HTML
	$tag_html = getVerseHTML($verse_html_data);

	# Return tag HTML
	return $tag_html;

}

function getTagTranslation($tag_translation_id) {

	# Get tag translation object
	$tag_translation_object = TagTranslationQuery::create()
		->findOneById($tag_translation_id);

	# Return tag translation object
	return $tag_translation_object;

}

function getTagTranslationByTagId($tag_id, $bible_id) {

	# Get tag translation object
	$tag_translation_object = TagTranslationQuery::create()
		->filterByTagId($tag_id)
		->filterByBibleId($bible_id)
		->findOne();

	# Return tag translation object
	return $tag_translation_object;

}

function getTagTranslationData($tag_id) {

	# Get tag translation object
	$tag_translation_object = getTagTranslation($tag_id);

	# Get bible object
	$bible_object = $tag_translation_object->getBible();

	# Get bible data
	$bible_data = getBibleData($bible_object->getId());

	# Define tag translation data
	$tag_translation_data = [
		'id' => $tag_translation_object->getId(),
		'bible' => $bible_data,
		'relevant_words' => $tag_translation_object->getRelevantWords(),
	];

	# Return tag translation data
	return $tag_translation_data;

}