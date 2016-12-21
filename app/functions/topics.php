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

	$topics_parents_array = getTopicsParents();

	$current_topic_object = $topic_object;
	$found_first_ancestor = false;
	$topic_ancestors_array = [];

	while (!$found_first_ancestor) {

		if ($current_topic_object->getIsRoot()) {
			$found_first_ancestor = true;
			continue;
		}

		$current_topic_object = getTopic($topics_parents_array[$current_topic_object->getId()][0]);
		$topic_ancestors_array[] = $current_topic_object->getId();

	}

	$topic_data['Ancestors'] = array_reverse($topic_ancestors_array);

	if ($topic_data['Ancestors']) {

		foreach ($topic_data['Ancestors'] as $topic_ancestor_id) {

			$topic_ancestor_object = getTopic($topic_ancestor_id);
			$topic_data['Breadcrumb'] .= '<span>' . $topic_ancestor_object->getTitle() . ' / </span>';

		}

	}

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

	$topics_objects = TopicQuery::create()
		->_if(count($topics_ids))
		->filterByPrimaryKeys($topics_ids)
		->_endif()
		->find();

	$topics_datas = [];
	foreach ($topics_objects as $topic_object) {
		$topics_datas[$topic_object->getId()] = $topic_object->toArray();
	}

	return $topics_datas;

}

function getTopicsChildren() {

	$topics_parents_objects = TopicParentQuery::create()
		->find();

	$topics_children = [];
	foreach ($topics_parents_objects as $topic_parent_object) {
		$topics_children[$topic_parent_object->getParentId()][] = $topic_parent_object->getTopicId();
	}

	return $topics_children;

}

function getTopicsLessons() {

	$topics_lessons_objects = TopicLessonQuery::create()
		->find();

	$topics_lessons = [];
	foreach ($topics_lessons_objects as $topic_lesson_object) {
		$topics_lessons[$topic_lesson_object->getTopicId()][] = $topic_lesson_object->getLessonId();
	}

	return $topics_lessons;

}

function getTopicsParents() {

	$topics_parents_objects = TopicParentQuery::create()
		->find();

	$topics_parents = [];
	foreach ($topics_parents_objects as $topic_parent_object) {
		$topics_parents[$topic_parent_object->getTopicId()][] = $topic_parent_object->getParentId();
	}

	return $topics_parents;

}

function getTopicsTags() {

	$topics_tags_objects = TopicTagQuery::create()
		->find();

	$topics_tags = [];
	foreach ($topics_tags_objects as $topic_tag_object) {
		$topics_tags[$topic_tag_object->getTopicId()][] = $topic_tag_object->getTagId();
	}

	return $topics_tags;

}