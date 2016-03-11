<?php

function addTopic($topic_name, $is_root = false) {

	# Add topic
	$topic_object = new Topic();
	$topic_object->setName($topic_name)
		->setIsRoot($is_root)
		->save();

	# Return ID
	return $topic_object->getId();

}

function addTopicsLink($topic_1_id, $topic_2_id, $strength = 1) {

	# Add first link
	$topic_link_object = new TopicLink();
	$topic_link_object->setTopicId($topic_1_id)
		->setLinkId($topic_2_id)
		->setStrength($strength)
		->save();

	# Add second link
	$topic_link_object = new TopicLink();
	$topic_link_object->setTopicId($topic_2_id)
		->setLinkId($topic_1_id)
		->setStrength($strength)
		->save();

}

function addTopicParent($topic_id, $parent_id) {

	$topic_parent_object = new TopicParent();
	$topic_parent_object->setTopicId($topic_id)
		->setParentId($parent_id)
		->save();

}

function getTopicData($topic_id) {

	# Get topic data
	$topic_data = TopicQuery::create()
		->findOneById($topic_id)
		->toArray();

	# Return topic data
	return $topic_data;

}

function getTopicChildrenIds($topic_id) {

	# Get topic children ids
	$topic_children_ids = TopicParentQuery::create()
		->filterByParentId($topic_id)
		->select([
			'topic_id',
		])
		->find()
		->toArray();

	# Return topic children ids
	return $topic_children_ids;

}

function getTopicLinksIds($topic_id) {

	# Get topic links ids
	$topic_links_ids = TopicLinkQuery::create()
		->filterByTopicId($topic_id)
		->orderByStrength('DESC')
		->select([
			'link_id',
		])
		->find()
		->toArray();

	# Return topic links data
	return $topic_links_ids;

}

function getTopicsTree() {

	# Get root topics ids
	$root_topics_ids = TopicQuery::create()
		->filterByIsRoot(true)
		->select([
			'id',
		])
		->find()
		->toArray();

	# Get topics tree
	$topics_tree = iterateThroughTopics($root_topics_ids);

	# Return topics tree
	return $topics_tree;

}

function iterateThroughTopics($topics_ids, $level = 0) {

	# Handle topics ids
	$topics_array = [];
	foreach ($topics_ids as $topic_id) {

		# Get topic data
		$topic_data = getTopicData($topic_id);

		# Add level
		$topic_data['Level'] = $level;

		# Get topic children
		$topic_children_ids = getTopicChildrenIds($topic_id);

		# Handle topic children
		if ($topic_children_ids) {
			$topic_data['Children'] = iterateThroughTopics($topic_children_ids, $level + 1);
		}

		# Append data to built tree
		$topics_array[] = $topic_data;
	}

	# Return topics array
	return $topics_array;

}

function getTopicsSelect() {

	# Get topics tree
	$topics_tree = getTopicsTree();



}