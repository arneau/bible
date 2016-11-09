<?php

use Propel\Runtime\Propel;

function fetchTreeAssets($fetch_lessons_assets = false, $fetch_topics_assets = false) {

	if ($fetch_lessons_assets) {

		global $lessons_datas, $lessons_children, $lessons_tags;

		if (is_null($lessons_datas)) {
			$lessons_datas = getLessonsDatas();
		}
		if (is_null($lessons_children)) {
			$lessons_children = getLessonsChildren();
		}
		if (is_null($lessons_tags)) {
			$lessons_tags = getLessonsTags();
		}

	}

	if ($fetch_topics_assets) {

		global $topics_datas, $topics_children, $topics_lessons, $topics_tags;

		if (is_null($topics_datas)) {
			$topics_datas = getTopicsDatas();
		}
		if (is_null($topics_children)) {
			$topics_children = getTopicsChildren();
		}
		if (is_null($topics_lessons)) {
			$topics_lessons = getTopicsLessons();
		}
		if (is_null($topics_tags)) {
			$topics_tags = getTopicsTags();
		}

	}

}

function getLessonsTree($root_lessons_ids = []) {

	fetchTreeAssets(true);

	if (!$root_lessons_ids) {
		$root_lessons_ids = getRootLessonsIds();
	}

	$lessons_tree = [];
	foreach ($root_lessons_ids as $root_lesson_id) {
		$lessons_tree[] = getLessonsTreeLesson($root_lesson_id);
	}

	return $lessons_tree;

}

function getLessonsTreeLesson($lesson_id) {

	global $lessons_datas, $lessons_children, $lessons_tags;

	$lesson_data = $lessons_datas[$lesson_id];

	if ($lessons_children[$lesson_id]) {

		foreach ($lessons_children[$lesson_id] as $lesson_child_id) {
			$lesson_data['Children'][] = getLessonsTreeLesson($lesson_child_id);
		}

	}

	$lesson_data['Tags'] = $lessons_tags[$lesson_id];

	return $lesson_data;

}

function getLessonsTreeLessonHTML($lesson_data) {

	$lesson_children_count = count($lesson_data['Children']);
	$lesson_tags_count = count($lesson_data['Tags']);

	$lesson_html = <<<s
<div class="tree_item">
s;

	if ($lesson_children_count) {
		$lesson_html .= <<<s
	<input class="toggler" id="toggler[lesson_{$lesson_data['Id']}]" type="checkbox" />
s;
	}

	$lesson_html .= <<<s
	<div class="item lesson">
s;

	if ($lesson_children_count) {
		$lesson_html .= <<<s
		<label class="toggler" for="toggler[lesson_{$lesson_data['Id']}]"></label>
s;
	}

	$lesson_html .= <<<s
		<a class="link" data-lesson-id="{$lesson_data['Id']}" href="lesson.php?id={$lesson_data['Id']}">
			<h4>{$lesson_data['Title']}</h4>
			<p>Lessons: {$lesson_children_count} &middot; Tags: {$lesson_tags_count}</p>
		</a>
	</div>
s;

	if ($lesson_children_count) {

		$lesson_html .= <<<s
	<div class="children">
s;

		foreach ($lesson_data['Children'] as $lesson_child_data) {
			$lesson_html .= getLessonsTreeLessonHTML($lesson_child_data);
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

function getTopicsTree($root_topics_ids = []) {

	fetchTreeAssets(true, true);

	if (!$root_topics_ids) {
		$root_topics_ids = getRootTopicsIds();
	}

	$topics_tree = [];
	foreach ($root_topics_ids as $root_topic_id) {
		$topics_tree[] = getTopicsTreeTopic($root_topic_id);
	}

	return $topics_tree;

}

function getTopicsTreeTopic($topic_id) {

	global $topics_datas, $topics_children, $topics_lessons, $topics_tags;

	$topic_data = $topics_datas[$topic_id];

	if ($topics_children[$topic_id]) {

		foreach ($topics_children[$topic_id] as $topic_child_id) {
			$topic_data['Children'][] = getTopicsTreeTopic($topic_child_id);
		}

	}

	if ($topics_lessons[$topic_id]) {

		foreach ($topics_lessons[$topic_id] as $topic_lesson_id) {
			$topic_data['Lessons'][] = getLessonsTreeLesson($topic_lesson_id);
		}

	}

	$topic_data['Tags'] = $topics_tags[$topic_id];

	return $topic_data;

}

function getTopicsTreeTopicHTML($topic_data) {

	$topic_children_count = count($topic_data['Children']);
	$topic_lessons_count = count($topic_data['Lessons']);
	$topic_tags_count = count($topic_data['Tags']);

	$topic_html = <<<s
<div class="tree_item">
s;

	if ($topic_children_count || $topic_lessons_count) {
		$topic_html .= <<<s
	<input class="toggler" id="toggler[topic_{$topic_data['Id']}]" type="checkbox" />
s;
	}

	$topic_html .= <<<s
	<div class="item topic">
s;

	if ($topic_children_count || $topic_lessons_count) {
		$topic_html .= <<<s
		<label class="toggler" for="toggler[topic_{$topic_data['Id']}]"></label>
s;
	}

	$topic_html .= <<<s
		<a class="link" data-topic-id="{$topic_data['Id']}" href="topic.php?id={$topic_data['Id']}">
			<h4>{$topic_data['Title']}</h4>
			<p>Topics: {$topic_children_count} &middot; Lessons: {$topic_lessons_count} &middot; Tags: {$topic_tags_count}</p>
		</a>
	</div>
s;

	if ($topic_children_count || $topic_lessons_count) {

		$topic_html .= <<<s
	<div class="children">
s;

		if ($topic_children_count) {
			foreach ($topic_data['Children'] as $topic_child_data) {
				$topic_html .= getTopicsTreeTopicHTML($topic_child_data);
			}
		}

		if ($topic_lessons_count) {
			foreach ($topic_data['Lessons'] as $topic_lesson_data) {
				$topic_html .= getLessonsTreeLessonHTML($topic_lesson_data);
			}
		}

		$topic_html .= <<<s
	</div>
s;

	}

	$topic_html .= <<<s
</div>
s;

	return $topic_html;

}

function getLessonsFamiliesOptions($lesson_to_select_id) {

	$lessons_families = getLessonsArray();

	$options_html = '';
	foreach ($lessons_families as $lessons_family_data) {
		$options_html .= getLessonFamilyOptions($lessons_family_data, $lesson_to_select_id);
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

function getLessonFamilyOptions($member_data) {

	$member_children_count = count($member_data['Children']);
	$member_tags_count = count($member_data['Tags']);

	$member_html = <<<s
<div class="family_tree_part">
s;

	if ($member_children_count) {
		$member_html .= <<<s
	<input class="toggler" id="toggler[lesson_{$member_data['Id']}]" type="checkbox" />
s;
	}

	$member_html .= <<<s
	<div class="member lesson">
s;

	if ($member_children_count) {
		$member_html .= <<<s
		<label class="toggler" for="toggler[lesson_{$member_data['Id']}]"></label>
s;
	}

	$member_html .= <<<s
		<a class="link" data-lesson-id="{$member_data['Id']}" href="lesson.php?id={$member_data['Id']}">
			<h4>{$member_data['Title']}</h4>
			<p>Lessons: {$member_children_count} &middot; Tags: {$member_tags_count}</p>
		</a>
	</div>
s;

	if ($member_children_count) {

		$member_html .= <<<s
	<div class="children">
s;

		foreach ($member_data['Children'] as $member_child_data) {
			$member_html .= getLessonsTreeHTMLRecursor($member_child_data);
		}

		$member_html .= <<<s
	</div>
s;

	}

	$member_html .= <<<s
</div>
s;

	return $member_html;

}