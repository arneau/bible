<?php

use Propel\Runtime\Propel;

function addLesson($lesson_parent_id, $lesson_summary) {

	$parent_object = LessonQuery::create()
		->findOneById($lesson_parent_id);

	$lesson_object = new Lesson();
	$lesson_object->insertAsLastChildOf($parent_object)
		->setSummary($lesson_summary)
		->save();

	return $lesson_object->getId();

}

function getLesson($lesson_id) {

	$lesson_object = LessonQuery::create()
		->findOneById($lesson_id);

	return $lesson_object;

}

function getLessonData($lesson_id) {

	# Get lesson object
	$lesson_object = getLesson($lesson_id);

	# Get lesson data
	$lesson_data = $lesson_object->toArray();

	# Get lessons parents array
	$lessons_parents_array = getLessonsParentsArray();

	# Get lesson ancestors
	$current_lesson_object = $lesson_object;
	$found_first_ancestor = false;
	$lesson_ancestors_array = [];
	while (!$found_first_ancestor) {
		if ($current_lesson_object->getIsRoot()) {
			$found_first_ancestor = true;
		} else {
			$current_lesson_object = getLesson($lessons_parents_array[$current_lesson_object->getId()][0]);
			$lesson_ancestors_array[] = $current_lesson_object->getId();
		}
	}
	$lesson_ancestors_array = array_reverse($lesson_ancestors_array);

	# Define lesson ancestors
	$lesson_data['Ancestors'] = $lesson_ancestors_array;

	# Define lesson summary
	$lesson_data['FormattedSummary'] = '';
	if ($lesson_data['Ancestors']) {
		foreach ($lesson_data['Ancestors'] as $lesson_ancestor_id) {
			$lesson_ancestor_object = getLesson($lesson_ancestor_id);
			$lesson_data['FormattedSummary'] .= '<span>' . $lesson_ancestor_object->getSummary() . ' / </span>';
		}
	}
	$lesson_data['FormattedSummary'] .= $lesson_data['Summary'];

	# Define lesson counts
	//	$lesson_data['counts']['lessons'] = $lesson_object->getChildren()
	//		->count();
	$lesson_data['counts']['tags'] = $lesson_object->getLessonTags()
		->count();

	# Define expandable status
	if ($lesson_data['counts']['lessons']) {
		$lesson_data['expandable'] = 'expandable';
	} else {
		$lesson_data['expandable'] = '';
	}

	# Return lesson data
	return $lesson_data;

}

function getListItemHtml($list_item_data, $list_item_type, $list_item_options = []) {

	if ($list_item_type == 'lesson') {
		return getLessonListItemHTML($list_item_data, $list_item_options);
	}

}

function getLessonListItemHTML($list_item_data, $list_item_options = []) {

	# Define additional list item data
	$list_item_children_count = count($list_item_data['Children']);
	if ($list_item_children_count) {
		$list_item_expandable_class = 'expandable';
	} else {
		$list_item_expandable_class = '';
	}
	$list_item_tags_count = count($list_item_data['Tags']);

	# Start building HTML
	$list_item_html = <<<s
<div class="list_item">
	<div class="self lesson">
		<div class="expander {$list_item_expandable_class}" onclick="$(this).closest('.list_item').toggleClass('expanded');"></div>
		<a class="link" data-lesson-id="{$list_item_data['Id']}" href="lesson.php?id={$list_item_data['Id']}">
			<h4>{$list_item_data['Summary']}</h4>
			<p>Lessons: {$list_item_children_count} &middot; Tags: {$list_item_tags_count}</p>
		</a>
	</div>
s;

	# Handle children
	if ($list_item_children_count) {

		$list_item_html .= <<<s
	<div class="children">
s;

		# Iterate through children
		foreach ($list_item_data['Children'] as $list_item_child) {

			# Append each child's HTML to parent's HTML
			$list_item_html .= getLessonListItemHTML($list_item_child);

		}

		$list_item_html .= <<<s
	</div>
s;

	}

	$list_item_html .= <<<s
</div>
s;

	return $list_item_html;

}

function getLessonTags($lesson_id, $order_by = 'vote_count') {

	# Get lesson object
	$lesson_object = getLesson($lesson_id);

	# Get lesson tags IDs
	$lesson_tags_ids = $lesson_object->getLessonTags()
		->getPrimaryKeys();

	# Get tag verse column to sort by
	$tag_verse_column_to_order_by = Propel::getDatabaseMap()
		->getTableByPhpName('TagVerse')
		->getColumnByPhpName('VerseId')
		->getFullyQualifiedName();

	# Get applicable tag objects
	$tags_objects = TagQuery::create()
		->useLessonTagQuery()
		->filterByPrimaryKeys($lesson_tags_ids)
		->endUse()
		->_if($order_by == 'vote_count')
		->orderByVoteCount()
		->_elseif($order_by == 'date_tagged')
		->orderById('DESC')
		->_endif()
		->joinWithTagVerse()
		->addAscendingOrderByColumn($tag_verse_column_to_order_by)
		->find();

	# Handle lesson tags objects
	$lesson_tags_to_return = [];
	foreach ($tags_objects as $tag_object) {

		# Append lesson tag to lesson tags to return
		$lesson_tags_to_return[] = [
			'id' => $tag_object->getId(),
		];

	}

	# Return lesson tags
	return $lesson_tags_to_return;

}

function getLessonsParentsArray() {

	$lessons_parents_objects = LessonParentQuery::create()
		->find();

	$lessons_parents_array_to_return = [];
	foreach ($lessons_parents_objects as $lesson_parent_object) {
		$lessons_parents_array_to_return[$lesson_parent_object->getLessonId()][] = $lesson_parent_object->getParentId();
	}

	return $lessons_parents_array_to_return;

}

function getLessonsChildrenArray() {

	$lessons_parents_objects = LessonParentQuery::create()
		->find();

	$lessons_children_array_to_return = [];
	foreach ($lessons_parents_objects as $lesson_parent_object) {
		$lessons_children_array_to_return[$lesson_parent_object->getParentId()][] = $lesson_parent_object->getLessonId();
	}

	return $lessons_children_array_to_return;

}

function getLessonsTagsArray() {

	$lessons_tags_objects = LessonTagQuery::create()
		->find();

	$lessons_tags_array_to_return = [];
	foreach ($lessons_tags_objects as $lesson_tag_object) {
		$lessons_tags_array_to_return[$lesson_tag_object->getLessonId()][] = $lesson_tag_object->getTagId();
	}

	return $lessons_tags_array_to_return;

}

function getLessonsDatas($lessons_ids = []) {

	$lessons_array_to_return = [];

	$lessons_objects = LessonQuery::create()
		->_if($lessons_ids)
		->filterByPrimaryKeys($lessons_ids)
		->_endif()
		->find();

	foreach ($lessons_objects as $lesson_object) {
		$lessons_array_to_return[$lesson_object->getId()] = $lesson_object->toArray();
	}

	return $lessons_array_to_return;

}

function getRootLessonsIds() {

	$lessons_object = LessonQuery::create()
		->filterByIsRoot(1)
		->find();

	return $lessons_object->getPrimaryKeys();

}

function getLessonsTree($lesson_ids = []) {

	$lessons_datas_array = getLessonsDatas();
	$lessons_children_array = getLessonsChildrenArray();
	$lessons_tags_array = getLessonsTagsArray();

	if ($lesson_ids) {
		$lessons_datas = getLessonsDatas($lesson_ids);
	} else {
		$root_lessons_ids = getRootLessonsIds();
		$lessons_datas = getLessonsDatas($root_lessons_ids);
	}

	$formatted_lessons_array_to_return = [];
	foreach ($lessons_datas as $lesson_data) {
		$formatted_lessons_array_to_return[] = getLessonsTreePartData($lessons_datas_array, $lessons_children_array, $lessons_tags_array, $lesson_data);
	}

	return $formatted_lessons_array_to_return;

}

function getLessonsTreePartData($lessons_array, $lessons_children_array, $lessons_tags_array, $lesson_data) {

	$lesson_data_to_return = $lesson_data;

	$lesson_data_to_return['Tags'] = $lessons_tags_array[$lesson_data['Id']];

	if ($lessons_children_array[$lesson_data['Id']]) {

		foreach ($lessons_children_array[$lesson_data['Id']] as $lesson_child_id) {
			$lesson_child_data = $lessons_array[$lesson_child_id];
			$lesson_data_to_return['Children'][] = getLessonsTreePartData($lessons_array, $lessons_children_array, $lessons_tags_array, $lesson_child_data);
		}

	}

	return $lesson_data_to_return;

}

function getLessonsList() {

	# Get root lesson object
	$root_lesson_object = LessonQuery::create()
		->findRoot();

	# Get root lesson children array
	$root_lesson_children_array = $root_lesson_object->getChildren()
		->toArray();

	# Define lessons list
	$lessons_list = [];

	# Build lessons list
	getLessonsListRecursor($root_lesson_children_array, $lessons_list);

	# Return lessons list
	return $lessons_list;

}

function getLessonsListRecursor($lessons_array, &$lessons_list, $level = 0) {

	# Sort lessons alphabetically
	uasort($lessons_array, function ($a, $b) {

		return strcmp($a['Summary'], $b['Summary']);
	});

	# Handle lessons
	foreach ($lessons_array as $lesson_data) {

		# Add level to lesson data
		$lesson_data['Level'] = $level;

		# Append lesson data to lessons list
		$lessons_list[] = $lesson_data;

		# Get lesson object
		$lesson_object = getLesson($lesson_data['Id']);

		# Get lesson children array
		$lesson_children_array = $lesson_object->getChildren()
			->toArray();

		# Recurse through lesson children (if applicable)
		if ($lesson_children_array) {
			getLessonsListRecursor($lesson_children_array, $lessons_list, $level + 1);
		}

	}

}

function getLessonsSelectOptions($selected_lesson_id = false) {

	# Get lessons list
	$lessons_list = getLessonsList();

	# Begin lessons select options string
	$lessons_select_options = '<option value="1">None</option>';

	# Iterate through lessons
	foreach ($lessons_list as $lesson_data) {
		if ($selected_lesson_id && $selected_lesson_id == $lesson_data['Id']) {
			$selected_attr = 'selected';
		} else {
			$selected_attr = '';
		}
		$lessons_select_options .= '<option ' . $selected_attr . ' value="' . $lesson_data['Id'] . '">' . str_repeat('--', $lesson_data['Level']) . ' ' . $lesson_data['Summary'] . '</option>';
	}

	# Return lessons select options string
	return $lessons_select_options;

}

function moveLesson($lesson_id, $lesson_parent_id) {

	# Get lesson object
	$lesson_object = getLesson($lesson_id);

	# Get parent lesson object
	$parent_lesson_object = getLesson($lesson_parent_id);

	# Rename lesson and save
	$lesson_object->moveToLastChildOf($parent_lesson_object)
		->save();

	# Return lesson object
	return $lesson_object;

}

function renameLesson($lesson_id, $lesson_summary) {

	# Get lesson object
	$lesson_object = getLesson($lesson_id);

	# Rename lesson and save
	$lesson_object->setSummary($lesson_summary)
		->save();

	# Return lesson object
	return $lesson_object;

}