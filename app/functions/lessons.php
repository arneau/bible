<?php

function addLesson($lesson_parent_id, $lesson_name) {

	# Get parent
	$parent_object = LessonQuery::create()
		->findOneById($lesson_parent_id);

	# Add lesson
	$lesson_object = new Lesson();
	$lesson_object->setSummary($lesson_name)
		->insertAsLastChildOf($parent_object)
		->save();

	# Return ID
	return $lesson_object->getId();

}

function getLesson($lesson_id) {

	# Get lesson object
	$lesson_object = LessonQuery::create()
		->findOneById($lesson_id);

	# Return lesson object
	return $lesson_object;

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
	$lessons_list = getLessonsList(false);

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