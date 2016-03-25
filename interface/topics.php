<?php

# Require files
require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

# Add topic (if applicable)
if (isset($_GET['add_topic'])) {
	addTopic($_GET['topic_parent_id'], $_GET['topic_name']);
}

# Add topic adoptee (if applicable)
if (isset($_GET['add_topic_adoptee'])) {
	addTopicAdoptee($_GET['topic_parent_id'], $_GET['topic_adoptee_id']);
}

# Move topic (if applicable)
if (isset($_GET['move_topic'])) {
	moveTopic($_GET['topic_id'], $_GET['topic_parent_id']);
}

# Rename topic (if applicable)
if (isset($_GET['rename_topic'])) {
	renameTopic($_GET['topic_id'], $_GET['topic_name']);
}

# Delete topic (if applicable)
if (isset($_GET['delete_topic'])) {
	deleteTopic($_GET['topic_id']);
}

# Get topics select options
$topics_select_options = getTopicsSelectOptions($_GET['topic_parent_id']);

echo <<<s
<html>
<head></head>
<body>
<h1>Add topic</h1>
<form>
	<label>Topic parent</label>
	<select name="topic_parent_id">
		$topics_select_options
	</select>
	<br/>
	<label>Topic name</label>
	<input name="topic_name" type="text" />
	<br/>
	<input name="add_topic" type="submit" value="Add topic" />
</form>
<h1>Add topic adoptee</h1>
<form>
	<label>Topic parent</label>
	<select name="topic_parent_id">
		$topics_select_options
	</select>
	<br/>
	<label>Topic adoptee</label>
	<select name="topic_adoptee_id">
		$topics_select_options
	</select>
	<br/>
	<input name="add_topic_adoptee" type="submit" value="Add topic adoptee" />
</form>
<h1>Move topic</h1>
<form>
	<label>Topic</label>
	<select name="topic_id">
		$topics_select_options
	</select>
	<br/>
	<label>New parent</label>
	<select name="topic_parent_id">
		$topics_select_options
	</select>
	<br/>
	<input name="move_topic" type="submit" value="Move topic" />
</form>
<h1>Rename topic</h1>
<form>
	<label>Topic</label>
	<select name="topic_id">
		$topics_select_options
	</select>
	<br/>
	<label>New name</label>
	<input name="topic_name" type="text" />
	<br/>
	<input name="rename_topic" type="submit" value="Rename topic" />
</form>
<h1>Delete topic</h1>
<form>
	<label>Topic</label>
	<select name="topic_id">
		$topics_select_options
	</select>
	<br/>
	<input name="delete_topic" type="submit" value="Delete topic" />
</form>
</body>
</html>
s;

?>