<?php

use Propel\Runtime\Propel;

function addLesson($lesson_ancestor_id, $lesson_summary) {

	$ancestor_object = LessonQuery::create()
		->findOneById($lesson_ancestor_id);

	$lesson_object = new Lesson();
	$lesson_object->insertAsLastDescendantOf($ancestor_object)
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

	# Get lessons ancestors array
	$lessons_ancestors_array = getLessonsAncestorsArray();

	# Get lesson ancestors
	$current_lesson_object = $lesson_object;
	$found_first_ancestor = false;
	$lesson_ancestors_array = [];
	while (!$found_first_ancestor) {
		if ($current_lesson_object->getIsRoot()) {
			$found_first_ancestor = true;
		} else {
			$current_lesson_object = getLesson($lessons_ancestors_array[$current_lesson_object->getId()][0]);
			$lesson_ancestors_array[] = $current_lesson_object->getId();
		}
	}
	$lesson_ancestors_array = array_reverse($lesson_ancestors_array);

	# Define lesson ancestors
	$lesson_data['Ancestors'] = $lesson_ancestors_array;

	# Define lesson summary
	$lesson_data['Breadcrumb'] = '';
	if ($lesson_data['Ancestors']) {
		foreach ($lesson_data['Ancestors'] as $lesson_ancestor_id) {
			$lesson_ancestor_object = getLesson($lesson_ancestor_id);
			$lesson_data['Breadcrumb'] .= '<span>' . $lesson_ancestor_object->getTitle() . ' / </span>';
		}
	}

	# Define lesson counts
	//	$lesson_data['counts']['lessons'] = $lesson_object->getDescendants()
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

function getLessonsAncestorsArray() {

	$lessons_ancestors_objects = LessonAncestorQuery::create()
		->find();

	$lessons_ancestors_array_to_return = [];
	foreach ($lessons_ancestors_objects as $lesson_ancestor_object) {
		$lessons_ancestors_array_to_return[$lesson_ancestor_object->getLessonId()][] = $lesson_ancestor_object->getAncestorId();
	}

	return $lessons_ancestors_array_to_return;

}

function getRootLessonsIds() {

	$lessons_object = LessonQuery::create()
		->filterByIsRoot(1)
		->find();

	return $lessons_object->getPrimaryKeys();

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

function getLessonsDescendantsArray() {

	$lessons_ancestors_objects = LessonAncestorQuery::create()
		->find();

	$lessons_descendants_array_to_return = [];
	foreach ($lessons_ancestors_objects as $lesson_ancestor_object) {
		$lessons_descendants_array_to_return[$lesson_ancestor_object->getAncestorId()][] = $lesson_ancestor_object->getLessonId();
	}

	return $lessons_descendants_array_to_return;

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

function getLessonsList() {

	# Get root lesson object
	$root_lesson_object = LessonQuery::create()
		->findRoot();

	# Get root lesson descendants array
	$root_lesson_descendants_array = $root_lesson_object->getDescendants()
		->toArray();

	# Define lessons list
	$lessons_list = [];

	# Build lessons list
	getLessonsListRecursor($root_lesson_descendants_array, $lessons_list);

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

		# Get lesson descendants array
		$lesson_descendants_array = $lesson_object->getDescendants()
			->toArray();

		# Recurse through lesson descendants (if applicable)
		if ($lesson_descendants_array) {
			getLessonsListRecursor($lesson_descendants_array, $lessons_list, $level + 1);
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

function moveLesson($lesson_id, $lesson_ancestor_id) {

	# Get lesson object
	$lesson_object = getLesson($lesson_id);

	# Get ancestor lesson object
	$ancestor_lesson_object = getLesson($lesson_ancestor_id);

	# Rename lesson and save
	$lesson_object->moveToLastDescendantOf($ancestor_lesson_object)
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