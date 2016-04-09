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

# Update tag translation relevant words (if applicable)
if (isset($_GET['update_tag_translation_relevant_words'])) {
	$tag_translation = getTagTranslation($_GET['tag_translation']);
	$tag_translation->setRelevantWords($_GET['relevant_words'])
		->save();
}

?>