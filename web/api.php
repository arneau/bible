<?

# Require files
require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

# Get words to highlight (if applicable)
if (isset($_GET['get_words_to_highlight'])) {
	$words_to_highlight = getWordsToHighlight($_GET['words_to_highlight_string']);
	$words_to_highlight = json_encode($words_to_highlight);
	echo $words_to_highlight;
}

# Update tag translation relevant words (if applicable)
if (isset($_GET['update_tag_translation_relevant_words'])) {
	$tag_translation = getTagTranslation($_GET['tag_translation']);
	$tag_translation->setRelevantWords($_GET['relevant_words'])
		->save();
}

?>