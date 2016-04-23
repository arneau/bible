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
		<section class="page_heading">
			<h1>Lessons</h1>
		</section>
		<section>
			<input onKeyUp="filterLessonFamilies(this);" />
			<div class="lesson_family">
s;

# Display lessons
foreach ($lessons_list as $lesson_data) {

	if ($lesson_data['TreeLevel'] == 1) {
echo <<<s
			</div>
			<div class="lesson_family">
s;
	}

	$lesson_family_member_margin = ($lesson_data['TreeLevel'] - 1) * 20;

	echo <<<s
				<a class="lesson" data-lesson-id="{$lesson_data['Id']}" href="lesson.php?id={$lesson_data['Id']}" style="margin-left: {$lesson_family_member_margin}px; width: calc(100% - {$lesson_family_member_margin}px);">
					<div class="content">
						<h3>{$lesson_data['Summary']}</h3>
						<p>
							<span class="icon icon-bible"></span>
							Tagged verses: {$lesson_data['TagCount']}
						</p>
					</div>
				</a>
s;
}

# End page
echo <<<s
			</div>
		</section>
	</div>
s;

# Display footer
require_once 'components/footer.php';

?>