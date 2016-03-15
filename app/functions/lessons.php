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

function getLessonsListRecursor($lessons_array, &$lessons_list) {

	# Sort lessons alphabetically
	uasort($lessons_array, function ($a, $b) {

		return strcmp($a['Summary'], $b['Summary']);
	});

	# Handle lessons
	foreach ($lessons_array as $lesson_data) {

		# Append lesson data to lessons list
		$lessons_list[] = $lesson_data;

		# Get lesson object
		$lesson_object = getLesson($lesson_data['Id']);

		# Get lesson children array
		$lesson_children_array = $lesson_object->getChildren()
			->toArray();

		# Recurse through lesson children (if applicable)
		if ($lesson_children_array) {
			getLessonsListRecursor($lesson_children_array, $lessons_list);
		}

	}

}

function getLessonsSelectHTML($select_name = 'lesson_parent_id', $selected_lesson_id = false) {

	# Get lessons list
	$lessons_list = getLessonsList();

	# Begin HTML to return
	$html_to_return = '<select name="' . $select_name . '"><option value="1">Lessons</option>';

	# Iterate through lessons
	foreach ($lessons_list as $lesson_data) {
		if ($selected_lesson_id && $selected_lesson_id == $lesson_data['Id']) {
			$selected_attr = 'selected';
		} else {
			$selected_attr = '';
		}
		$html_to_return .= '<option ' . $selected_attr . ' value="' . $lesson_data['Id'] . '">' . str_repeat('-', $lesson_data['TreeLevel']) . ' ' . $lesson_data['Summary'] . '</option>';
	}

	# End HTML to return
	$html_to_return .= '</select>';

	# Return HTML
	return $html_to_return;

}