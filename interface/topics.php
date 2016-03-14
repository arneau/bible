<?php

# Require files
require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

# Add topic (if applicable)
if (isset($_GET['topic_parent_id']) && isset($_GET['topic_name'])) {
	addTopic($_GET['topic_parent_id'], $_GET['topic_name']);
}

# Get topics select html
$topics_select_html = getTopicsSelectHTML('topic_parent_id', $_GET['topic_parent_id']);

echo <<<s
<html>
<head></head>
<body>
<form>
	<label>Topic parent</label>
	$topics_select_html
	<br/>
	<label>Topic name</label>
	<input name="topic_name" type="text" />
	<br/>
	<button type="submit">Add topic</button>
</form>
</body>
</html>
s;

?>