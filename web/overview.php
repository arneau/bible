<?

require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

require_once 'components/header.php';

$topics_families = getTopicsFamilies();
$lessons_families = getLessonsFamilies();

echo <<<s
	<div class="page" id="overview_page">
		<div class="columns">
			<div class="column">
				<section class="page_section">
					<h2>Topics</h2>
				</section>
				<section style="max-height: 800px; overflow-x: scroll;">
s;

foreach ($topics_families as $topic_family_data) {

	echo getFamilyHtml($topic_family_data, 'topic');

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

foreach ($lessons_families as $lessons_family_data) {

	echo getFamilyHtml($lessons_family_data, 'lesson');

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