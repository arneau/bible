<?

# Require files
require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

# Display header
require_once 'components/header.php';

# Get lesson, data, etc
$lesson_data = getLessonData($_GET['id']);
$lessons_tags = getLessonTags($_GET['id']);

# Start page
echo <<<s
	<div class="page" id="lesson_page">
		<section>
			<h1>
				$lesson_data[Summary]
				<span>(Lesson)</span>
			</h1>
		</section>
		<div class="columns">
			<div class="column">
				<section class="passages">
					<div class="icon icon-verses"></div>
					<h3>Tagged verses</h3>
s;

# Display lesson tags
if ($lessons_tags) {

	foreach ($lessons_tags as $lesson_tag_data) {
		echo getTagHTML($lesson_tag_data['id']);
	}

}

echo <<<s
				</section>
			</div>
			<div class="column">
				<section class="topics">
					<div class="icon icon-topics"></div>
					<h3>Relevant topics</h3>
				</section>
				<section class="lessons">
					<div class="icon icon-lessons"></div>
					<h3>Lesson family</h3>
				</section>
				<section class="notes">
					<div class="icon icon-edit"></div>
					<h3>Saved notes</h3>
				</section>
			</div>
		</div>
	</div>
s;

# Display footer
require_once 'components/footer.php';

?>