<?

# Require files
require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

# Add lesson tag (if applicable)
if (isset($_GET['add_lesson_tag'])) {

	# Add lesson tag
	addLessonTag($_GET['lesson_id'], $_GET['reference_string']);

}

# Edit lesson summary (if applicable)
if (isset($_GET['edit_lesson_summary'])) {

	# Get lesson object
	$lesson_object = getLesson($_GET['lesson_id']);

	# Set summary and save
	$lesson_object->setSummary($_GET['lesson_summary'])
		->save();

}

# Move lesson (if applicable)
if (isset($_GET['move_lesson'])) {

	# Move lesson to new parent
	moveLesson($_GET['lesson_id'], $_GET['parent_lesson_id']);

}

# Update tag highlighter relevant words (if applicable)
if (isset($_GET['update_tag_highlighter'])) {

	# Set tag highlighter relevant words
	$tag_highlighter = getTagHighlighter($_GET['tag_highlighter_id']);
	$tag_highlighter->setRelevantWords($_GET['tag_highlighter_relevant_words'])
		->save();

}

# Delete lesson (if applicable)
if (isset($_GET['delete_lesson'])) {

	# Get lesson object
	$lesson_object = getLesson($_GET['lesson_id']);

	# Delete lesson object
	$lesson_object->delete();

}

# Delete tag (if applicable)
if (isset($_GET['delete_tag'])) {

	# Get tag object
	$tag_object = getTag($_GET['tag_id']);

	# Delete tag object
	$tag_object->delete();

}

?>