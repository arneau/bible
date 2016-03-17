<?php

function addTopic($topic_parent_id, $topic_name) {

	# Get parent
	$parent_object = TopicQuery::create()
		->findOneById($topic_parent_id);

	# Add topic
	$topic_object = new Topic();
	$topic_object->setName($topic_name)
		->insertAsLastChildOf($parent_object)
		->save();

	# Add topic synonym
	$topic_synonym_object = new TopicSynonym();
	$topic_synonym_object->setName($topic_name)
		->setTopic($topic_object)
		->save();

	# Return ID
	return $topic_object->getId();

}

function addTopicAdoptee($topic_parent_id, $topic_adoptee_id) {

	# Get parent
	$parent_object = TopicQuery::create()
		->findOneById($topic_parent_id);

	# Add topic adoptee
	$topic_adoptee_object = new TopicAdoptee();
	$topic_adoptee_object->setAdopteeId($topic_adoptee_id)
		->setTopic($parent_object)
		->save();

}

function getTopic($topic_id) {

	# Get topic object
	$topic_object = TopicQuery::create()
		->findOneById($topic_id);

	# Return topic object
	return $topic_object;

}

function getTopicAdoptees($topic_id) {

	# Get topic adoptees IDs
	$topic_adoptees_ids_array = TopicAdopteeQuery::create()
		->filterByTopicId($topic_id)
		->select([
			'AdopteeId',
		])
		->find()
		->toArray();

	# Get topic adoptees array
	$topic_adoptees_array = TopicQuery::create()
		->filterByPrimaryKeys($topic_adoptees_ids_array)
		->find()
		->toArray();

	# Return topic adoptees array
	return $topic_adoptees_array;

}

function getTopicsList($include_adoptees = true) {

	# Get root topic object
	$root_topic_object = TopicQuery::create()
		->findRoot();

	# Get root topic children array
	$root_topic_children_array = $root_topic_object->getChildren()
		->toArray();

	# Define topics list
	$topics_list = [];

	# Build topics list
	getTopicsListRecursor($root_topic_children_array, $topics_list);

	# Return topics list
	return $topics_list;

}

function getTopicsListRecursor($topics_array, &$topics_list, $level = 0) {

	# Sort topics alphabetically
	uasort($topics_array, function ($a, $b) {

		return strcmp($a['Name'], $b['Name']);
	});

	# Handle topics
	foreach ($topics_array as $topic_data) {

		# Add level to topic data
		$topic_data['Level'] = $level;

		# Append topic data to topics list
		$topics_list[] = $topic_data;

		# Get topic object
		$topic_object = getTopic($topic_data['Id']);

		# Get topic children array
		$topic_children_array = $topic_object->getChildren()
			->toArray();

		# Get topic adoptees array
		$topic_adoptees_array = getTopicAdoptees($topic_data['Id']);

		# Merge topic children and adoptees arrays
		$topic_dependants_array = array_merge($topic_children_array, $topic_adoptees_array);

		# Recurse through topic children (if applicable)
		if ($topic_dependants_array) {
			getTopicsListRecursor($topic_dependants_array, $topics_list, $level + 1);
		}

	}

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
		$topics_select_options .= '<option ' . $selected_attr . ' value="' . $topic_data['Id'] . '">' . str_repeat('--', $topic_data['Level']) . ' ' . $topic_data['Name'] . '</option>';
	}

	# Return topics select options string
	return $topics_select_options;

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