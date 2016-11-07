<?php

use Propel\Runtime\Propel;

function addTopic($topic_ancestor_id, $topic_name) {

	$ancestor_object = TopicQuery::create()
		->findOneById($topic_ancestor_id);

	$topic_object = new Topic();
	$topic_object->setName($topic_name)
		->insertAsLastDescendantOf($ancestor_object)
		->save();

	$topic_synonym_object = new TopicSynonym();
	$topic_synonym_object->setName($topic_name)
		->setTopic($topic_object)
		->save();

	return $topic_object->getId();

}

function addTopicLesson($topic_id, $lesson_id) {

	$topic_lesson_object = new TopicLesson();
	$topic_lesson_object->setTopicId($topic_id)
		->setLessonId($lesson_id)
		->save();

}

function deleteTopic($topic_id) {

	$topic_object = getTopic($topic_id);

	$topic_object->delete();

}

function getTopic($topic_id) {

	$topic_object = TopicQuery::create()
		->findOneById($topic_id);

	return $topic_object;

}

function getTopicData($topic_id) {

	$topic_object = getTopic($topic_id);

	$topic_data = $topic_object->toArray();

	$topic_ancestors_objects = $topic_object->getAncestors();
	if ($topic_ancestors_objects) {
		$topic_ancestors_datas = $topic_ancestors_objects->toArray();
		unset($topic_ancestors_datas[0]);
	}

	$topic_data['name'] = [
		'default' => $topic_data['Name'],
		'formatted' => '',
	];
	if ($topic_ancestors_datas) {
		foreach ($topic_ancestors_datas as $topic_ancestor_data) {
			$topic_data['name']['formatted'] .= '<span>' . $topic_ancestor_data['Name'] . ' / </span>';
		}
	}
	$topic_data['name']['formatted'] .= $topic_data['Name'];

	return $topic_data;

}

function getTopicTags($topic_id, $order_by = 'vote_count') {

	# Get topic object
	$topic_object = getTopic($topic_id);

	# Get topic tags IDs
	$topic_tags_ids = $topic_object->getTopicTags()
		->getPrimaryKeys();

	# Get tag verse column to sort by
	$tag_verse_column_to_order_by = Propel::getDatabaseMap()
		->getTableByPhpName('TagVerse')
		->getColumnByPhpName('VerseId')
		->getFullyQualifiedName();

	# Get applicable tag objects
	$tags_objects = TagQuery::create()
		->useTopicTagQuery()
		->filterByPrimaryKeys($topic_tags_ids)
		->endUse()
		->_if($order_by == 'vote_count')
		->orderByVoteCount()
		->_elseif($order_by == 'date_tagged')
		->orderById('DESC')
		->_endif()
		->joinWithTagVerse()
		->addAscendingOrderByColumn($tag_verse_column_to_order_by)
		->find();

	# Handle topic tags objects
	$topic_tags_to_return = [];
	foreach ($tags_objects as $tag_object) {

		# Append topic tag to topic tags to return
		$topic_tags_to_return[] = [
			'id' => $tag_object->getId(),
		];

	}

	# Return topic tags
	return $topic_tags_to_return;

}

function getTopicsSelectOptions($selected_topic_id = false) {

	# Get topics list
	$topics_list = getTopicsList(false);

	# Begin topics select options string
	$topics_select_options = '<option value="1">None</option>';

	# Iterate through topics
	foreach ($topics_list as $topic_data) {
		if ($selected_topic_id && $selected_topic_id == $topic_data['Id']) {
			$selected_attr = 'selected';
		} else {
			$selected_attr = '';
		}
		$topics_select_options .= '<option ' . $selected_attr . ' value="' . $topic_data['Id'] . '">' . str_repeat('--', $topic_data['ListLevel']) . ' ' . $topic_data['Name'] . '</option>';
	}

	# Return topics select options string
	return $topics_select_options;

}

function moveTopic($topic_id, $topic_ancestor_id) {

	# Get topic object
	$topic_object = getTopic($topic_id);

	# Get ancestor topic object
	$ancestor_topic_object = getTopic($topic_ancestor_id);

	# Rename topic and save
	$topic_object->moveToLastDescendantOf($ancestor_topic_object)
		->save();

	# Return topic object
	return $topic_object;

}

function renameTopic($topic_id, $topic_name) {

	# Get topic object
	$topic_object = getTopic($topic_id);

	# Rename topic and save
	$topic_object->setName($topic_name)
		->save();

	# Return topic object
	return $topic_object;

}

function getRootTopicsIds() {

	$topics_object = TopicQuery::create()
		->filterByIsRoot(1)
		->find();

	return $topics_object->getPrimaryKeys();

}

function getTopicsDatas($topics_ids = []) {

	$topics_array_to_return = [];

	$topics_objects = TopicQuery::create()
		->_if($topics_ids)
		->filterByPrimaryKeys($topics_ids)
		->_endif()
		->find();

	foreach ($topics_objects as $topic_object) {
		$topics_array_to_return[$topic_object->getId()] = $topic_object->toArray();
	}

	return $topics_array_to_return;

}

function getTopicsDescendantsArray() {

	$topics_ancestors_objects = TopicAncestorQuery::create()
		->find();

	$topics_descendants_array_to_return = [];
	foreach ($topics_ancestors_objects as $topic_ancestor_object) {
		$topics_descendants_array_to_return[$topic_ancestor_object->getAncestorId()][] = $topic_ancestor_object->getTopicId();
	}

	return $topics_descendants_array_to_return;

}

function getTopicsLessonsArray() {

	$topics_lessons_objects = TopicLessonQuery::create()
		->find();

	$topics_lessons_array = [];
	foreach ($topics_lessons_objects as $topic_lesson_object) {
		$topics_lessons_array[$topic_lesson_object->getTopicId()][] = $topic_lesson_object->getLessonId();
	}

	return $topics_lessons_array;

}

function getTopicsTagsArray() {

	$topics_tags_objects = TopicTagQuery::create()
		->find();

	$topics_tags_array_to_return = [];
	foreach ($topics_tags_objects as $topic_tag_object) {
		$topics_tags_array_to_return[$topic_tag_object->getTopicId()][] = $topic_tag_object->getTagId();
	}

	return $topics_tags_array_to_return;

}