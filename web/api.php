<?

# Require files
require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

if (isset($_GET['add_lesson'])) {
	addLesson($_GET['lesson_id'], $_GET['lesson_summary']);
}

if (isset($_GET['add_topic_tag'])) {
	addTopicTag($_GET['topic']['id'], $_GET['reference_string']);
}

if (isset($_GET['add_lesson_tag'])) {
	addLessonTag($_GET['lesson_id'], $_GET['reference_string']);
}

if (isset($_GET['edit_topic'])) {

	# Get topic object
	$topic_object = getTopic($_GET['topic']['id']);

	# Set data and save
	$topic_object->setName($_GET['topic']['name'])
		->save();

}

if (isset($_GET['edit_lesson_summary'])) {

	# Get lesson object
	$lesson_object = getLesson($_GET['lesson_id']);

	# Set summary and save
	$lesson_object->setSummary($_GET['lesson_summary'])
		->save();

}

if (isset($_GET['link_lesson_to_topic'])) {

	$topic_lesson_object = new TopicLesson();
	$topic_lesson_object->setLessonId($_GET['lesson_id'])
		->setTopicId($_GET['topic_id'])
		->save();

}

if (isset($_GET['move_tag_to_lesson'])) {

	$lesson_tag_object = LessonTagQuery::create()
		->useTagQuery()
		->filterById($_GET['tag_id'])
		->endUse()
		->findOne()
		->setLessonId($_GET['lesson_id'])
		->save();

}

if (isset($_GET['move_lesson'])) {

	# Move lesson to new parent
	moveLesson($_GET['lesson_id'], $_GET['parent_lesson_id']);

}

if (isset($_GET['add_topic_lesson'])) {
	addTopicLesson($_GET['topic_id'], $_GET['lesson_id']);
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