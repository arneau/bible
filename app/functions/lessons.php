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

	# Start building HTML
	$list_item_html = <<<s
<div class="list_item">
	<div class="self lesson">
		<div class="expander {$list_item_expandable_class}" onclick="$(this).closest('.list_item').toggleClass('expanded');"></div>
		<a class="link" data-lesson-id="{$list_item_data['Id']}" href="lesson.php?id={$list_item_data['Id']}">
			<h4>{$list_item_data['Summary']}</h4>
			<p>Lessons: {$list_item_children_count} &middot; Tags: {$lesson_data['counts']['tags']}</p>
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

function getLessonsArray($only_return_roots = false) {

	$lessons_array_to_return = [];

	$lessons_objects = NewLessonQuery::create()
		->find();

	foreach ($lessons_objects->toArray() as $lesson_data) {
		$lessons_array_to_return[$lesson_data['Id']] = $lesson_data;
	}

	if ($only_return_roots) {

		$lessons_array_to_return = array_filter($lessons_array_to_return, function ($lesson_data) {
			return $lesson_data['IsRoot'];
		});

	}

	return $lessons_array_to_return;

}

function getLessonsChildrenArray() {

	$lessons_children_array_to_return = [];

	$lessons_parents_objects = NewLessonParentQuery::create()
		->find();

	foreach ($lessons_parents_objects->toArray() as $lesson_parent_data) {
		$lessons_children_array_to_return[$lesson_parent_data['ParentId']][] = $lesson_parent_data['LessonId'];
	}

	return $lessons_children_array_to_return;

}

function getFormattedLessonsArray() {

	$lessons_array_to_return = [];

	$lessons_array = getLessonsArray();
	$root_lessons_array = getLessonsArray(true);
	$lessons_children_array = getLessonsChildrenArray();

	foreach ($root_lessons_array as $lesson_data) {
		$lessons_array_to_return[] = getFormattedLessonsArrayRecursor($lessons_array_to_return, $lessons_array, $lessons_children_array, $lesson_data);
	}

	return $lessons_array_to_return;

}

function getFormattedLessonsArrayRecursor($lessons_array_to_return, $lessons_array, $lessons_children_array, $lesson_data) {

	$lesson_data_to_return = $lesson_data;

	if ($lessons_children_array[$lesson_data['Id']]) {

		foreach ($lessons_children_array[$lesson_data['Id']] as $lesson_child_id) {
			$lesson_child_data = $lessons_array[$lesson_child_id];
			$lesson_data_to_return['Children'][] = getFormattedLessonsArrayRecursor($lessons_array_to_return, $lessons_array, $lessons_children_array, $lesson_child_data);
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