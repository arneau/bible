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
		usort($lesson_data['Children'], function ($a, $b) {
//			if ($a['Title'] < $b['Title']) {
//				return -1;
//			} elseif ($a['Title'] > $b['Title']) {
//				return 1;
//			} else {
//				return 0;
//			}
			return $a['Title'] <=> $b['Title'];
		});
	}

	$lesson_data['Tags'] = $lessons_tags[$lesson_id];

	return $lesson_data;

}

function getLessonsTreeLessonHTML($lesson_data) {

	$lesson_children_count = count($lesson_data['Children']);
	$lesson_tags_count = count($lesson_data['Tags']);

	$lesson_html = <<<s
<div class="tree_item" data-type="lesson">
s;

	if ($lesson_children_count) {
		$lesson_html .= <<<s
	<input class="toggler" id="toggler[lesson_{$lesson_data['Id']}]" type="checkbox" />
s;
	}

	$lesson_html .= <<<s
	<div class="self">
s;

	if ($lesson_children_count) {
		$lesson_html .= <<<s
		<label class="toggler" for="toggler[lesson_{$lesson_data['Id']}]"></label>
s;
	}

	$lesson_html .= <<<s
		<a class="link lesson" data-lesson-id="{$lesson_data['Id']}" href="lesson.php?id={$lesson_data['Id']}">
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

function getLessonsTreeOptions($lesson_to_select_id = false) {

	$lessons_tree = getLessonsTree();

	$options_html = <<<s
<option value="0">None</option>
s;

	foreach ($lessons_tree as $lesson_data) {
		$options_html .= getLessonsTreeLessonOption($lesson_data, 0, $lesson_to_select_id);
	}

	return $options_html;

}

function getLessonsTreeLessonOption($lesson_data, $lesson_level = 0, $lesson_to_select_id = false) {

	$lesson_option_selected_attribute = ($lesson_data['Id'] == $lesson_to_select_id) ? 'selected' : '';

	$lesson_option_prefix = str_repeat('&dash;', $lesson_level);

	$lesson_option_html = <<<s
<option {$lesson_option_selected_attribute} value="{$lesson_data['Id']}">{$lesson_option_prefix} {$lesson_data['Title']}</option>
s;

	if (count($lesson_data['Children'])) {
		foreach ($lesson_data['Children'] as $lesson_child_data) {
			$lesson_option_html .= getLessonsTreeLessonOption($lesson_child_data, $lesson_level + 1, $lesson_to_select_id);
		}
	}

	return $lesson_option_html;

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
<div class="tree_item" data-type="topic">
s;

	if ($topic_children_count || $topic_lessons_count) {
		$topic_html .= <<<s
	<input class="toggler" id="toggler[topic_{$topic_data['Id']}]" type="checkbox" />
s;
	}

	$topic_html .= <<<s
	<div class="self">
s;

	if ($topic_children_count || $topic_lessons_count) {
		$topic_html .= <<<s
		<label class="toggler" for="toggler[topic_{$topic_data['Id']}]"></label>
s;
	}

	$topic_html .= <<<s
		<a class="link topic" data-topic-id="{$topic_data['Id']}" href="topic.php?id={$topic_data['Id']}">
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