<?

# Require files
require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

# Get lessons list
$lessons_list = getLessonsList();

# Display header
require_once 'components/header.php';

# Start page
echo <<<s
	<div class="page" id="lessons_page">
		<section>
			<h1>Lessons</h1>
		</section>
s;

# Display lessons
$is_adoptee = false;
foreach ($lessons_list as $lesson_data) {

	# Define offset
	$offset = 40 * $lesson_data['Level'] . 'px';

	# Echo lesson
	echo <<<s
		<div class="lesson" style="margin-left: $offset;">
			<h3>$lesson_data[Summary]</h3>
		</div>
s;

}

# End page
echo <<<s
	</div>
s;

# Display footer
require_once 'components/footer.php';

?>