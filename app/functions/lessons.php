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

	# Get all but root ancestors
	$lesson_ancestors_objects = $lesson_object->getAncestors();
	if ($lesson_ancestors_objects) {
		$lesson_ancestors_datas = $lesson_ancestors_objects->toArray();
		unset($lesson_ancestors_datas[0]);
	}

	# Define lesson summary
	$lesson_data['summary'] = [
		'default' => $lesson_data['Summary'],
		'formatted' => '',
	];
	if ($lesson_ancestors_datas) {
		foreach ($lesson_ancestors_datas as $lesson_ancestor_data) {
			$lesson_data['summary']['formatted'] .= '<span>' . $lesson_ancestor_data['Summary'] . ' / </span>';
		}
	}
	$lesson_data['summary']['formatted'] .= $lesson_data['Summary'];

	# Define lesson counts
	$lesson_data['counts']['lessons'] = $lesson_object->getChildren()
		->count();
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

function getListItemHtml($item_id, $item_type, $item_options = []) {

	if ($item_type == 'lesson') {
		return getLessonListItemHTML($item_id, $item_options);
	}

}

function getLessonListItemHTML($lesson_id, $list_item_options = []) {

	# Get lesson object, data and children
	$lesson_object = getLesson($lesson_id);
	$lesson_data = getLessonData($lesson_id);
	$lesson_children = $lesson_object->getChildren();

	# Start building HTML
	$lesson_html = <<<s
<div class="list_item">
	<div class="self lesson">
		<div class="expander {$lesson_data['expandable']}" onclick="$(this).closest('.list_item').toggleClass('expanded');"></div>
		<a class="link" data-lesson-id="{$lesson_data['Id']}" href="lesson.php?id={$lesson_data['Id']}">
			<h4>{$lesson_data['Summary']}</h4>
			<p>Lessons: {$lesson_data['counts']['lessons']} &middot; Tags: {$lesson_data['counts']['tags']}</p>
		</a>
	</div>
s;

	# Handle children
	if ($lesson_children->count()) {

		$lesson_html .= <<<s
	<div class="children">
s;

		# Iterate through children
		foreach ($lesson_children as $lesson_child_object) {

			# Append each child's HTML to parent's HTML
			$lesson_html .= getLessonListItemHTML($lesson_child_object->getId());

		}

		$lesson_html .= <<<s
	</div>
s;

	}

	$lesson_html .= <<<s
</div>
s;

	return $lesson_html;

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