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

function getLessonData($lesson_id) {

	# Get lesson object
	$lesson_object = getLesson($lesson_id);

	# Get lesson data
	$lesson_data = $lesson_object->toArray();

	# Return lesson data
	return $lesson_data;

}

function getLessonTags($lesson_id, $bible_code = 'kjv') {

	# Get lesson object
	$lesson_object = getLesson($lesson_id);

	# Get lesson tags objects
	$lesson_tags_objects = $lesson_object->getLessonTags();

	# Handle lesson tags objects
	$lesson_tags_to_return = [];
	foreach ($lesson_tags_objects as $lesson_tag_object) {

		# Get tag object
		$tag_object = $lesson_tag_object->getTag();

		# Get tag translation object
		$tag_translation_object = TagTranslationQuery::create()
			->filterByBible(getBibleByCode($bible_code))
			->filterByTag($tag_object)
			->findOne();

		# Append lesson tag to lesson tags to return
		$lesson_tags_to_return[] = [
			'bible' => [
				'code' => $bible_code,
			],
			'id' => $tag_object->getId(),
			'relevant_words' => $tag_translation_object->getRelevantWords(),
			'verse' => [
				'id' => $tag_object->getVerseId(),
			],
			'vote_count' => $tag_object->getVoteCount(),
		];

	}

	# Return lesson tags
	return $lesson_tags_to_return;

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