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

function getTopicsSelectHTML($select_name = 'topic_parent_id', $selected_topic_id = false) {

	# Get root topic
	$root_topic = TopicQuery::create()
		->findRoot();

	# Get topics
	$topics_objects = $root_topic->getBranch();

	# Begin HTML to return
	$html_to_return = '<select name="' . $select_name . '">';

	# Iterate through topics
	foreach ($topics_objects as $topic_object) {
		if ($selected_topic_id && $selected_topic_id == $topic_object->getId()) {
			$selected_attr = 'selected';
		} else {
			$selected_attr = '';
		}
		$html_to_return .= '<option ' . $selected_attr . ' value="' . $topic_object->getId() . '">' . str_repeat('-', $topic_object->getLevel()) . ' ' . $topic_object->getName() . '</option>';
	}

	# End HTML to return
	$html_to_return .= '</select>';

	# Return HTML
	return $html_to_return;

}