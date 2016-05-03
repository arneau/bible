<?

require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

require_once 'components/header.php';

$root_topic_children_objects = TopicQuery::create()
	->findRoot()
	->getChildren();
$topics_and_lessons_list_items = getCategoryListItems($root_topic_children_objects);

$root_lesson_object_id = LessonQuery::create()
	->findRoot()
	->getId();
$root_lesson_children_objects = LessonQuery::create()
	->filterByPrimaryKeys([
		$root_lesson_object_id,
	])
	->find();
$lessons_list_items = getCategoryListItems($root_lesson_children_objects);

echo <<<s
	<div class="page" id="overview_page">
		<div class="columns">
			<div class="column">
				<section class="page_section">
					<h2>Topics</h2>
				</section>
				<section style="max-height: 800px; overflow-x: scroll;">
s;

foreach ($topics_and_lessons_list_items as $topics_and_lessons_list_item_data) {

	echo getCategoryListItemHTML($topics_and_lessons_list_item_data);

}

echo <<<s
				</section>
			</div>
			<div class="column">
				<section>
					<h2>Lessons</h2>
				</section>
				<section style="max-height: 800px; overflow-x: scroll;">
					<input onKeyUp="filterLessonFamilies(this);" />
					<div class="lesson_family">
s;

foreach ($lessons_list_items as $lessons_list_item_data) {

	if ($lessons_list_item_data['level'] == 1) {
		echo <<<s
					</div>
					<div class="lesson_family">
s;
	}

	echo getCategoryListItemHTML($lessons_list_item_data);

}

echo <<<s
					</div>
				</section>
			</div>
		</div>
	</div>
s;

require_once 'components/footer.php';

?>