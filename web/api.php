<?

# Require files
require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

if (isset($_GET['action'])) {

	if (isset($_GET['add_topic'])) {
		addTopic($_GET['topic_id'], $_GET['topic_name']);
	}

	if ($_GET['action'] == 'add_lesson') {

		$lesson_id = addLesson($_GET['lesson_title'], $_GET['lesson_parent_id']);

		if ($_GET['topic_id']) {
			addTopicLesson($_GET['topic_id'], $lesson_id);
		}

	}

	// Add topic tag
	if (isset($_GET['add_topic_tag'])) {
		addTopicTag($_GET['topic']['id'], $_GET['reference_string']);
	}

	// Add lesson tag
	if ($_GET['action'] == 'add_lesson_tag') {
		addLessonTag($_GET['lesson_id'], $_GET['reference_string']);
	}

	// Edit lesson title
	if ($_GET['action'] == 'edit_lesson_title') {
		$lesson_object = getLesson($_GET['lesson_id']);
		$lesson_object->setTitle($_GET['lesson_title'])
			->save();
	}

	// Edit topic name
	if (isset($_GET['edit_topic_name'])) {
		$topic_object = getTopic($_GET['topic_id']);
		$topic_object->setTitle($_GET['topic_title'])
			->save();
	}

	if (isset($_GET['link_lesson_to_topic'])) {

		$topic_lesson_object = new TopicLesson();
		$topic_lesson_object->setLessonId($_GET['lesson_id'])
			->setTopicId($_GET['topic_id'])
			->save();

	}

	// Move tag to lesson
	if ($_GET['action'] == 'move_tag_to_lesson') {
		$lesson_tag_object = LessonTagQuery::create()
			->useTagQuery()
			->filterById($_GET['tag_id'])
			->endUse()
			->findOne()
			->setLessonId($_GET['lesson_id'])
			->save();
	}

	// Move lesson
	if ($_GET['action'] =='move_lesson') {
		moveLesson($_GET['lesson_id'], $_GET['parent_lesson_id']);
	}

	if (isset($_GET['add_topic_lesson'])) {
		addTopicLesson($_GET['topic_id'], $_GET['lesson_id']);
	}

	// Update tag highlighter
	if ($_GET['action'] == 'update_tag_highlighter') {
		$tag_highlighter = getTagHighlighter($_GET['tag_highlighter_id']);
		$tag_highlighter->setRelevantWords($_GET['tag_highlighter_relevant_words'])
			->save();
	}

	// Delete lesson
	if ($_GET['action'] == 'delete_lesson') {
		$lesson_object = getLesson($_GET['lesson_id']);
		$lesson_object->delete();
	}

	// Delete tag
	if ($_GET['action'] == 'delete_tag') {
		$tag_object = getTag($_GET['tag_id']);
		$tag_object->delete();
	}

}

?>