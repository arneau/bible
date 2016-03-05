<?php

function addTopic($topic_name) {

	# Add topic
	$topic_object = new Topic();
	$topic_object->setName($topic_name)
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

function getTopicChildrenData($topic_id) {

	# Get topic children ids
	$topic_children_ids = TopicParentQuery::create()
		->filterByParentId($topic_id)
		->select([
			'topic_id',
		])
		->find()
		->toArray();

	# Handle topic children
	$topic_children_data = [];
	foreach ($topic_children_ids as $topic_child_id) {

		# Get child data
		$topic_child_object = TopicQuery::create()
			->findOneById($topic_child_id);

		# Return child data
		$topic_children_data[] = [
			'id' => $topic_child_object->getId(),
			'name' => $topic_child_object->getName(),
		];

	}

	# Return topic children data
	return $topic_children_data;

}

function getTopicLinksData($topic_id) {

	# Get topic links ids
	$topic_links_ids = TopicLinkQuery::create()
		->filterByTopicId($topic_id)
		->orderByStrength('DESC')
		->select([
			'link_id',
		])
		->find()
		->toArray();

	# Handle topic links
	$topic_links_data = [];
	foreach ($topic_links_ids as $topic_link_id) {

		# Get link data
		$topic_link_object = TopicQuery::create()
			->findOneById($topic_link_id);

		# Return link data
		$topic_links_data[] = [
			'id' => $topic_link_object->getId(),
			'name' => $topic_link_object->getName(),
		];

	}

	# Return topic links data
	return $topic_links_data;

}