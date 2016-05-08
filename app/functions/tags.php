<?php

function addTag($reference_string, $bible_code = 'kjv', $relevant_words = '') {

	# Get reference data
	$reference_data = getReferenceData($reference_string);

	if (!$reference_data) {
		return false;
	}

	# Add tag object
	$tag_object = new Tag();

	# Add tag verse object for each verse number
	foreach ($reference_data['verses'] as $verse_number) {

		# Get verse object
		$verse_object = getVerseByReference($reference_data['book'] . ' ' . $reference_data['chapter'] . ':' . $verse_number);

		if (!$verse_object) {
			return false;
		}

		# Add tag verse object
		$tag_verse_object = new TagVerse();
		$tag_verse_object->setTag($tag_object)
			->setVerse($verse_object)
			->save();

	}

	$tag_object->save();

	# Get bible object
	$bible_object = getBibleByCode($bible_code);

	# Add tag highlighter object
	$tag_highlighter_object = new TagHighlighter();
	$tag_highlighter_object->setBible($bible_object)
		->setRelevantWords($relevant_words)
		->setTag($tag_object)
		->save();

	return $tag_object;

}

function addTopicTag($topic_id, $reference_string, $bible_code = 'kjv', $relevant_words = '') {

	# Add tag object
	$tag_object = addTag($reference_string, $bible_code, $relevant_words);

	# Add topic tag object (if applicable)
	if ($tag_object) {
		$topic_tag_object = new TopicTag();
		$topic_tag_object->setTopicId($topic_id)
			->setTag($tag_object)
			->save();
	}

}

function addLessonTag($lesson_id, $reference_string, $bible_code = 'kjv', $relevant_words = '') {

	# Add tag object
	$tag_object = addTag($reference_string, $bible_code, $relevant_words);

	# Add lesson tag object (if applicable)
	if ($tag_object) {
		$lesson_tag_object = new LessonTag();
		$lesson_tag_object->setLessonId($lesson_id)
			->setTag($tag_object)
			->save();
	}

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

	# Get tag reference
	$tag_reference = getTagReference($tag_object->getId());

	# Get bible object
	$bible_object = getBibleByCode($bible_code);

	# Get tag highlighter object
	$tag_highlighter_object = TagHighlighterQuery::create()
		->filterByBible($bible_object)
		->filterByTag($tag_object)
		->findOne();

	# Define passage HTML data
	$passage_html_data = [
		'bible_id' => $bible_object->getId(),
		'reference_string' => $tag_reference,
		'tag_id' => $tag_object->getId(),
		'tag_highlighter_id' => $tag_highlighter_object->getId(),
	];

	# Get passage HTML
	$tag_html = getPassageHTML($passage_html_data);

	# Return tag HTML
	return $tag_html;

}

function getTagReference($tag_id) {

	# Get tag object
	$tag_object = getTag($tag_id);

	# Get tag verses ids
	$tag_verses_ids = $tag_object->getTagVerses()
		->getPrimaryKeys();

	# Get verses objects
	$verses_objects = VerseQuery::create()
		->useTagVerseQuery()
		->filterByPrimaryKeys($tag_verses_ids)
		->endUse()
		->find();

	# Define tag reference data
	foreach ($verses_objects as $verse_object) {

		# Get verse data
		$verse_data = getVerseData($verse_object->getId());

		# Append verse data to tag reference data
		$tag_reference_data['book'] = $verse_data['book']['name'];
		$tag_reference_data['chapter'] = $verse_data['chapter'];
		$tag_reference_data['verses_numbers'][] = $verse_data['number'];

	}

	# Define tag reference
	$tag_reference = $tag_reference_data['book'] . ' ' . $tag_reference_data['chapter'] . ':' . getNumbersStringFromArray($tag_reference_data['verses_numbers']);

	# Return tag reference
	return $tag_reference;

}

function getTagHighlighter($tag_highlighter_id) {

	# Get tag highlighter object
	$tag_highlighter_object = TagHighlighterQuery::create()
		->findOneById($tag_highlighter_id);

	# Return tag highlighter object
	return $tag_highlighter_object;

}

function getTagHighlighterByTagId($tag_id, $bible_id) {

	# Get tag highlighter object
	$tag_highlighter_object = TagHighlighterQuery::create()
		->filterByTagId($tag_id)
		->filterByBibleId($bible_id)
		->findOne();

	# Return tag highlighter object
	return $tag_highlighter_object;

}

function getTagHighlighterData($tag_id) {

	# Get tag highlighter object
	$tag_highlighter_object = getTagHighlighter($tag_id);

	# Get bible object
	$bible_object = $tag_highlighter_object->getBible();

	# Get bible data
	$bible_data = getBibleData($bible_object->getId());

	# Define tag highlighter data
	$tag_highlighter_data = [
		'id' => $tag_highlighter_object->getId(),
		'bible' => $bible_data,
		'relevant_words' => $tag_highlighter_object->getRelevantWords(),
	];

	# Return tag highlighter data
	return $tag_highlighter_data;

}