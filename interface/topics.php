<?php

# Require files
require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

# Add topic (if applicable)
if (isset($_GET['name'])) {

	var_dump('add topic');

	$topic_object = new Topic();
	$topic_object->setName($_GET['name'])
		->setIsRoot($_GET['is_root'])
		->save();

}

# Get topics tree
$topics_tree = getTopicsTree();
//var_dump($topics_tree);

?>

<html>
<head></head>
<body>
<form>
	<label>Topic name</label>
	<input name="name" type="text" />
	<label>Is root?</label>
	<input name="is_root" type="checkbox" />
	<button type="submit">Add topic</button>
</form>
</body>
</html>