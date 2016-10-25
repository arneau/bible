<?

require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

require_once 'components/header.php';

$root_topic_children_objects = TopicQuery::create()
	->findRoot()
	->getChildren();
$topics_and_lessons_list_items = getCategoryListItems($root_topic_children_objects);

$formatted_lessons_array = getFormattedLessonsArray();

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

foreach ($formatted_lessons_array as $formatted_lesson_data) {

	echo getListItemHtml($formatted_lesson_data, 'lesson');

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