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

	# Return ID
	return $topic_object->getId();

}

function getTopic($topic_id) {

	# Get topic object
	$topic_object = TopicQuery::create()
		->findOneById($topic_id);

	# Return topic object
	return $topic_object;

}

function getTopicsList() {

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

function getTopicsListRecursor($topics_array, &$topics_list) {

	# Sort topics alphabetically
	uasort($topics_array, function ($a, $b) {

		return strcmp($a['Name'], $b['Name']);
	});

	# Handle topics
	foreach ($topics_array as $topic_data) {

		# Append topic data to topics list
		$topics_list[] = $topic_data;

		# Get topic object
		$topic_object = getTopic($topic_data['Id']);

		# Get topic children array
		$topic_children_array = $topic_object->getChildren()
			->toArray();

		# Recurse through topic children (if applicable)
		if ($topic_children_array) {
			getTopicsListRecursor($topic_children_array, $topics_list);
		}

	}

}

function getTopicsSelectHTML($select_name = 'topic_parent_id', $selected_topic_id = false) {

	# Get topics list
	$topics_list = getTopicsList();

	# Begin HTML to return
	$html_to_return = '<select name="' . $select_name . '"><option value="1">Topics</option>';

	# Iterate through topics
	foreach ($topics_list as $topic_data) {
		if ($selected_topic_id && $selected_topic_id == $topic_data['Id']) {
			$selected_attr = 'selected';
		} else {
			$selected_attr = '';
		}
		$html_to_return .= '<option ' . $selected_attr . ' value="' . $topic_data['Id'] . '">' . str_repeat('-', $topic_data['TreeLevel']) . ' ' . $topic_data['Name'] . '</option>';
	}

	# End HTML to return
	$html_to_return .= '</select>';

	# Return HTML
	return $html_to_return;

}