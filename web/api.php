<?

# Require files
require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

# Add lesson tag (if applicable)
if (isset($_GET['add_lesson_tag'])) {

	# Get verse object
	$verse_object = getVerseByReference($_GET['reference_string']);

	# Add lesson tag
	addLessonTag($_GET['lesson_id'], $verse_object->getId());

}

# Edit lesson summary (if applicable)
if (isset($_GET['edit_lesson_summary'])) {

	# Get lesson object
	$lesson_object = getLesson($_GET['lesson_id']);

	# Set summary and save
	$lesson_object->setSummary($_GET['lesson_summary'])
		->save();

}

# Update tag translation relevant words (if applicable)
if (isset($_GET['update_tag_translation_relevant_words'])) {
	$tag_translation = getTagTranslation($_GET['tag_translation']);
	$tag_translation->setRelevantWords($_GET['relevant_words'])
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