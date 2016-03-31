<?php

# Require files
require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

# Add lesson (if applicable)
if (isset($_GET['lesson_parent_id']) && isset($_GET['lesson_name'])) {
	addLesson($_GET['lesson_parent_id'], $_GET['lesson_name']);
}

# Rename lesson (if applicable)
if (isset($_GET['rename_lesson'])) {
	renameLesson($_GET['lesson_id'], $_GET['lesson_summary']);
}

# Get lessons select options
$lessons_select_options = getLessonsSelectOptions($_GET['lesson_parent_id']);

echo <<<s
<html>
<head></head>
<body>
<h1>Add lesson</h1>
<form>
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
<h1>Rename lesson</h1>
<form>
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