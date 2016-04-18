<?php

# Require files
require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

# Add lesson (if applicable)
if (isset($_POST['lesson_parent_id']) && isset($_POST['lesson_name'])) {
	addLesson($_POST['lesson_parent_id'], $_POST['lesson_name']);
}

# Move lesson (if applicable)
if (isset($_POST['move_lesson'])) {
	moveLesson($_POST['lesson_id'], $_POST['lesson_parent_id']);
}

# Rename lesson (if applicable)
if (isset($_POST['rename_lesson'])) {
	renameLesson($_POST['lesson_id'], $_POST['lesson_summary']);
}

# Get lessons select options
$lessons_select_options = getLessonsSelectOptions($_POST['lesson_parent_id']);

echo <<<s
<html>
<head></head>
<body>
<h1>Add lesson</h1>
<form method="post">
	<label>Lesson parent</label>
	<select name="lesson_parent_id">
		$lessons_select_options
	</select>
	<br/>
	<label>Lesson name</label>
	<input name="lesson_name" style="width: 300px;" type="text" />
	<br/>
	<input name="add_lesson" type="submit" value="Add lesson" />
</form>
<h1>Move lesson</h1>
<form method="post">
	<label>Lesson</label>
	<select name="lesson_id">
		$lessons_select_options
	</select>
	<br/>
	<label>New parent</label>
	<select name="lesson_parent_id">
		$lessons_select_options
	</select>
	<br/>
	<input name="move_lesson" type="submit" value="Move lesson" />
</form>
<h1>Rename lesson</h1>
<form method="post">
	<label>Topic</label>
	<select name="lesson_id">
		$lessons_select_options
	</select>
	<br/>
	<label>New name</label>
	<input name="lesson_summary" type="text" />
	<br/>
	<input name="rename_lesson" type="submit" value="Rename lesson" />
</form>
</body>
</html>
s;

?>