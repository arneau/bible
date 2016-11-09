<?

require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

require_once 'components/header.php';

$topics_tree = getTopicsTree();
$lessons_tree = getLessonsTree();

echo <<<s
	<div class="page" id="overview_page">
		<div class="columns">
			<div class="column">
				<section class="page_section">
					<h2>Topics</h2>
				</section>
				<section style="max-height: 800px; overflow-x: scroll;">
s;

foreach ($topics_tree as $topics_tree_topic_data) {
	echo getTopicsTreeTopicHTML($topics_tree_topic_data);
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

foreach ($lessons_tree as $lessons_tree_lesson_data) {
	echo getLessonsTreeLessonHTML($lessons_tree_lesson_data);
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