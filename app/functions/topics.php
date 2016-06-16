<?php

use Propel\Runtime\Propel;

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

function addTopicLesson($topic_id, $lesson_id) {

	$topic_lesson_object = new TopicLesson();
	$topic_lesson_object->setTopicId($topic_id)
		->setLessonId($lesson_id)
		->save();

}

function deleteTopic($topic_id) {

	# Get topic object
	$topic_object = getTopic($topic_id);

	# Delete topic
	$topic_object->delete();

}

function getTopic($topic_id) {

	# Get topic object
	$topic_object = TopicQuery::create()
		->findOneById($topic_id);

	# Return topic object
	return $topic_object;

}

function getTopicData($topic_id) {

	# Get topic object
	$topic_object = getTopic($topic_id);

	# Get topic data
	$topic_data = $topic_object->toArray();

	# Get all but root ancestors
	$topic_ancestors_objects = $topic_object->getAncestors();
	if ($topic_ancestors_objects) {
		$topic_ancestors_datas = $topic_ancestors_objects->toArray();
		unset($topic_ancestors_datas[0]);
	}

	# Define topic summary
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

	# Return topic data
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

	# Add IsAdoptee to each adoptee
	array_walk($topic_adoptees_array, function (&$topic_adoptee_data) {

		$topic_adoptee_data['IsAdoptee'] = true;
	});

	# Return topic adoptees array
	return $topic_adoptees_array;

}

function getTopicsList($include_adoptees = false) {

	# Get root topic object
	$root_topic_object = TopicQuery::create()
		->findRoot();

	# Get root topic children array
	$root_topic_children_array = $root_topic_object->getChildren()
		->toArray();

	# Define topics list
	$topics_list = [];

	# Build topics list
	getTopicsListRecursor($topics_list, $root_topic_children_array, $include_adoptees);

	# Return topics list
	return $topics_list;

}

function getTopicsListRecursor(&$topics_list, $topics_array, $include_adoptees = false, $list_level = 0) {

	# Sort topics alphabetically
	uasort($topics_array, function ($a, $b) {

		return strcmp($a['Name'], $b['Name']);

	});

	# Handle topics
	foreach ($topics_array as $topic_data) {

		# Add level to topic data
		$topic_data['ListLevel'] = $list_level;

		# Append topic data to topics list
		$topics_list[] = $topic_data;

		# Get topic object
		$topic_object = getTopic($topic_data['Id']);

		# Get topic children array
		$topic_children_array = $topic_object->getChildren()
			->toArray();

		if ($include_adoptees) {
			$topic_adoptees_array = getTopicAdoptees($topic_data['Id']);
			$topic_dependants_array = array_merge($topic_children_array, $topic_adoptees_array);
		} else {
			$topic_dependants_array = $topic_children_array;
		}

		# Recurse through topic children (if applicable)
		if ($topic_dependants_array) {
			getTopicsListRecursor($topics_list, $topic_dependants_array, $include_adoptees, $list_level + 1);
		}

	}

}

function getCategoryListItems($root_objects, $include_topics = true, $include_adoptees = false) {

	$category_list_items = [];
	getCategoryListItemRecursor($category_list_items, $root_objects, $include_topics, $include_adoptees);

	return $category_list_items;

}

function getCategoryListItemRecursor(&$category_list_items, $level_objects, $include_topics = false, $include_adoptees = false, $list_level = 0) {

	if (get_class($level_objects[0]) == 'Topic') {

		$level_objects = TopicQuery::create()
			->filterByPrimaryKeys($level_objects->getPrimaryKeys())
			->orderByName()
			->find();
	}
	if (get_class($level_objects[0]) == 'Lesson') {

		$level_objects = LessonQuery::create()
			->filterByPrimaryKeys($level_objects->getPrimaryKeys())
			->orderBySummary()
			->find();
	}

	foreach ($level_objects as $level_object) {

		$level_object_data = getCategoryListItemData($level_object, $list_level);

		$category_list_items[] = $level_object_data;

		if (get_class($level_object) == 'Topic') {

			if ($include_topics) {

				$level_object_lessons_objects = $level_object->getLessons();
				if ($level_object_lessons_objects->count()) {
					getCategoryListItemRecursor($category_list_items, $level_object_lessons_objects, $include_topics, $include_adoptees, $list_level + 1);
				}

			}

			$level_object_topics_objects = $level_object->getChildren();
			if ($level_object_topics_objects->count()) {
				getCategoryListItemRecursor($category_list_items, $level_object_topics_objects, $include_topics, $include_adoptees, $list_level + 1);
			}

		}

		if (get_class($level_object) == 'Lesson') {

			$level_object_lessons_objects = $level_object->getChildren();
			if ($level_object_lessons_objects->count()) {
				getCategoryListItemRecursor($category_list_items, $level_object_lessons_objects, $include_topics, $include_adoptees, $list_level + 1);
			}

		}

	}

}

function getCategoryListItemData($list_item_object, $list_item_level) {

	$list_item_data = [
		'id' => $list_item_object->getId(),
		'level' => $list_item_level,
	];

	if (get_class($list_item_object) == 'Topic') {

		$list_item_data['lessons_count'] = $list_item_object->getTopicLessons()
			->count();
		$list_item_data['tags_count'] = $list_item_object->getTopicTags()
			->count();
		$list_item_data['topics_count'] = $list_item_object->getChildren()
			->count();
		$list_item_data['title'] = $list_item_object->getName();
		$list_item_data['type'] = 'topic';

	}
	if (get_class($list_item_object) == 'Lesson') {

		$list_item_data['lessons_count'] = $list_item_object->getChildren()
			->count();
		$list_item_data['tags_count'] = $list_item_object->getLessonTags()
			->count();
		$list_item_data['title'] = $list_item_object->getSummary();
		$list_item_data['type'] = 'lesson';

	}

	return $list_item_data;

}

function getCategoryListItemHTML($category_list_item_data) {

	if ($category_list_item_data['type'] == 'topic') {
		$category_list_item_link = 'topic.php?id=' . $category_list_item_data['id'];
		$category_list_item_counts = 'Topics: ' . $category_list_item_data['topics_count'] . ' &middot; Lessons: ' . $category_list_item_data['lessons_count'] . ' &middot; Tags: ' . $category_list_item_data['tags_count'];
	}
	if ($category_list_item_data['type'] == 'lesson') {
		$category_list_item_link = 'lesson.php?id=' . $category_list_item_data['id'];
		$category_list_item_counts = 'Lessons: ' . $category_list_item_data['lessons_count'] . ' &middot; Tags: ' . $category_list_item_data['tags_count'];
	}

	$topic_list_item_html = <<<s
<a class="{$category_list_item_data['type']}_list_item" data-{$category_list_item_data['type']}-id="{$category_list_item_data['id']}" data-list-item-level="level_{$category_list_item_data['level']}" href="{$category_list_item_link}">
	<h4>{$category_list_item_data['title']}</h4>
	<p>{$category_list_item_counts}</p>
</a>
s;

	return $topic_list_item_html;

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

function moveTopic($topic_id, $topic_parent_id) {

	# Get topic object
	$topic_object = getTopic($topic_id);

	# Get parent topic object
	$parent_topic_object = getTopic($topic_parent_id);

	# Rename topic and save
	$topic_object->moveToLastChildOf($parent_topic_object)
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