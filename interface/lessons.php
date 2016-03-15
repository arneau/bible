<?php

# Require files
require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

# Add lesson (if applicable)
if (isset($_GET['lesson_parent_id']) && isset($_GET['lesson_name'])) {
	addLesson($_GET['lesson_parent_id'], $_GET['lesson_name']);
}

# Get lessons select html
$lessons_select_html = getLessonsSelectHTML('lesson_parent_id', $_GET['lesson_parent_id']);

echo <<<s
<html>
<head></head>
<body>
<form>
	<label>Lesson parent</label>
	$lessons_select_html
	<br/>
	<label>Lesson name</label>
	<input name="lesson_name" type="text" />
	<br/>
	<button type="submit">Add lesson</button>
</form>
</body>
</html>
s;

?>