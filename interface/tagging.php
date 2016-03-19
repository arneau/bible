<?php

# Require files
require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

# Add topic tag (if applicable)
if (isset($_GET['add_topic_tag'])) {
	addTopicTag($_GET['topic_id'], $_GET['verse_id'], $_GET['bible_code'], $_GET['relevant_words']);
}

# Add lesson tag (if applicable)
if (isset($_GET['add_lesson_tag'])) {
	addLessonTag($_GET['lesson_id'], $_GET['verse_id'], $_GET['bible_code'], $_GET['relevant_words']);
}

# Get passage data
$passage_data = getPassageData($_GET['reference_string']);

# Get topics select options
$topics_select_options = getTopicsSelectOptions($_GET['topic_parent_id']);

# Get lessons select options
$lessons_select_options = getLessonsSelectOptions($_GET['topic_parent_id']);

echo <<<s
<html>
<head></head>
<body>
<h1>{$passage_data['reference']['string']}</h1>

s;
foreach ($passage_data['verses'] as $passage_verse_data) {
echo <<<s
<p>
	<b>{$passage_verse_data['text']}</b>
</p>
<p>
	<b>Topics:</b>

s;
	foreach ($passage_verse_data['tags']['topics'] as $topic_tag_data) {
		echo $topic_tag_data['topic']['name'] . ', ';
	}
echo <<<s
</p>
<p>
	<b>Lessons:</b>

s;
	foreach ($passage_verse_data['tags']['lessons'] as $lesson_tag_data) {
		echo $lesson_tag_data['lesson']['name'] . ', ';
	}
echo <<<s
</p>
<form action="tagging.php?reference_string=$_GET[reference_string]">
	<label>Topic</label>
	<select name="topic_id">
		$topics_select_options
	</select>
	<br/>
	<label>Relevant words</label>
	<input name="relevant_words" type="text" value="1-{$passage_verse_data['word_count']}" />
	<input name="bible_code" type="hidden" value="{$passage_data['bible']['code']}" />
	<input name="verse_id" type="hidden" value="{$passage_data['verses'][0]['id']}" />
	<br/>
	<input name="add_topic_tag" type="submit" value="Add tag" />
</form>
<form action="tagging.php?reference_string=$_GET[reference_string]">
	<label>Lesson</label>
	<select name="lesson_id">
		$lessons_select_options
	</select>
	<br/>
	<label>Relevant words</label>
	<input name="relevant_words" type="text" value="1-{$passage_verse_data['word_count']}" />
	<input name="bible_code" type="hidden" value="{$passage_data['bible']['code']}" />
	<input name="verse_id" type="hidden" value="{$passage_data['verses'][0]['id']}" />
	<br/>
	<input name="add_lesson_tag" type="submit" value="Add tag" />
</form>

s;
}
echo <<<s
</body>
</html>
s;

?>