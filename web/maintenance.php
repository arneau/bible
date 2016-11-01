<?

# Display errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

# Require files
require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

# Seperate verse IDs from tags
if (0) {

	$tags_objects = TagQuery::create()
		->find();

	foreach ($tags_objects as $tag_object) {

		$tag_verse_object = new TagVerse();
		$tag_verse_object->setTag($tag_object)
			->setVerseId($tag_object->getVerseId())
			->save();

	}

}

# Copy relevant words from tag translations to tag highlighters and rework numbering
if (0) {

	global $verse_translation_data;

	$tags_translations_objects = TagTranslationQuery::create()
		->find();

	foreach ($tags_translations_objects as $tag_translation_object) {

		$tag_object = $tag_translation_object->getTag();

		$tag_first_verse_data = $tag_object->getTagVerses()
									->toArray()[0];

		$verse_translation_object = VerseTranslationQuery::create()
			->filterByBibleId(1)
			->filterByVerseId($tag_first_verse_data['VerseId'])
			->findOne();

		$verse_translation_data = getVerseTranslationData($verse_translation_object->getId());

		$relevant_words = getNumbersArrayFromString($tag_translation_object->getRelevantWords());

		array_walk($relevant_words, function (&$relevant_word) {

			global $verse_translation_data;

			$relevant_word = $relevant_word + $verse_translation_data['previous_verses_word_count'];

		});

		$relevant_words = getNumbersStringFromArray($relevant_words);

		$tag_highlighter_object = new TagHighlighter();
		$tag_highlighter_object->setBibleId(1)
			->setRelevantWords($relevant_words)
			->setTag($tag_object)
			->save();

	}

}

# Update verse translations to include previous verses word count with DB
if (0) {

	$verse_translation_objects = VerseTranslationQuery::create()
		->where('ID > 26000')
		->find();

	foreach ($verse_translation_objects as $verse_translation_object) {

		$verse_object = $verse_translation_object->getVerse();

		$previous_verses_word_count = 0;
		if ($verse_object->getVerseNumber() > 1) {

			$previous_verses_data = VerseQuery::create()
				->filterByBook($verse_object->getBook())
				->filterByChapterNumber($verse_object->getChapterNumber())
				->filterByVerseNumber(range(1, $verse_object->getVerseNumber() - 1))
				->joinWithVerseTranslation()
				->find()
				->toArray();

			foreach ($previous_verses_data as $previous_verse_data) {
				$previous_verses_word_count += $previous_verse_data['VerseTranslations'][0]['WordCount'];
			}

		}

		$verse_translation_object->setPreviousVersesWordCount($previous_verses_word_count)
			->save();

	}

}

# Delete tags without tag verses
if (0) {

	$tags_objects = TagQuery::create()
		->find();

	foreach ($tags_objects as $tag_object) {

		$tag_verses_objects = $tag_object->getTagVerses();

		if (!$tag_verses_objects->toArray()) {
			$tag_object->delete();
		}

	}

}

# Migrate lessons from old structure to new
if (0) {

	function recurseThroughLessons($lesson_id, $parent_id = null) {

		$lesson_object = getLesson($lesson_id);
		$lesson_data = $lesson_object->toArray();

		$new_lesson_object = new NewLesson();
		$new_lesson_object->setSummary($lesson_data['Summary'])
			->setIsRoot(is_null($parent_id))
			->save();

		if ($parent_id) {

			$new_lesson_parent_object = new NewLessonParent();
			$new_lesson_parent_object->setLessonId($new_lesson_object->getId())
				->setParentId($parent_id)
				->save();

		}

		$lesson_children_ids = $lesson_object->getChildren()
			->getPrimaryKeys();
		$lesson_children = LessonQuery::create()
			->filterByPrimaryKeys($lesson_children_ids)
			->orderBySummary()
			->find();

		foreach ($lesson_children as $lesson_child) {
			recurseThroughLessons($lesson_child->getId(), $new_lesson_object->getId());
		}

		$lesson_tags = $lesson_object->getLessonTags();

		foreach ($lesson_tags as $lesson_tag) {
			$lesson_tag->setNewLessonId($new_lesson_object->getId())
				->save();
		}

		$lesson_topics_lessons = TopicLessonQuery::create()
			->filterByLessonId($lesson_id)
			->find();

		foreach ($lesson_topics_lessons as $lesson_topic_lesson) {
			$lesson_topic_lesson->setNewLessonId($new_lesson_object->getId())
				->save();
		}

	}

	$root_lesson_object = LessonQuery::create()
		->findRoot();
	$root_lesson_children_ids = $root_lesson_object->getChildren()
		->getPrimaryKeys();
	$root_lesson_children = LessonQuery::create()
		->filterByPrimaryKeys($root_lesson_children_ids)
		->orderBySummary()
		->find();

	foreach ($root_lesson_children as $root_lesson_child) {
		recurseThroughLessons($root_lesson_child->getId());
	}

}

?>