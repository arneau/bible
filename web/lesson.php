<?

# Require files
require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

# Display header
require_once 'components/header.php';

# Get lesson, data, etc
$lesson_object = getLesson($_GET['id']);
$lesson_data = getLessonData($_GET['id']);
$lesson_tags = getLessonTags($_GET['id'], $_GET['order_passages_by']);
if (!$lesson_data['Ancestors']) {
	$lesson_family_root_id = $_GET['id'];
} else {
	$lesson_family_root_id = $lesson_data['Ancestors'][0];
}
$lesson_family = getLessonsTree([
	$lesson_family_root_id
])[0];

$lessons_tree_options = getLessonsTreeOptions($lesson_data['Id']);

# Get topics select options
//$topics_select_options = getTopicsSelectOptions();

# Start page
echo <<<s
	<div class="page" id="lesson_page">
		<section class="page_heading">
			<h1>{$lesson_data['Breadcrumb']} {$lesson_data['Title']}</h1>
			<button class="icon-pencil" onclick="showPopup('edit_lesson_title');"></button>
			<button class="icon-topics" onclick="showPopup('link_lesson_to_topic');"></button>
			<button class="icon-close" onclick="deleteLesson('{$lesson_data['Id']}');"></button>
		</section>
		<script>
			document.title = $('.page_heading h1').text();
		</script>
		<div class="columns">
			<div class="column">
				<section id="passages">
					<input checked id="passages_toggle" type="checkbox" />
					<label class="heading" for="passages_toggle">
						<div class="icon icon-bible"></div>
						<h2>Tagged verses</h2>
					</label>
					<div class="content">
						<div class="actions">
							<div class="columns">
								<div class="column">
									<p>
										<a onclick="showPopup('add_lesson_tag_popup');">Add tag</a>
									</p>
								</div>
								<div class="column">
									<p>
										Order by ...
										<a href="?id={$_GET['id']}&order_passages_by=reference">Reference</a>
										<a href="?id={$_GET['id']}&order_passages_by=vote_count">Vote count</a>
										<a href="?id={$_GET['id']}&order_passages_by=date_tagged">Date tagged</a>
									</p>
								</div>
							</div>
						</div>
s;

# Display lesson tags
if ($lesson_tags) {

	foreach ($lesson_tags as $lesson_tag_data) {
		echo getTagHTML($lesson_tag_data['id']);
	}

}

echo <<<s
					</div>
				</section>
			</div>
			<div class="column">
				<section id="topics">
					<input id="topics_toggle" type="checkbox" />
					<label class="heading" for="topics_toggle">
						<div class="icon icon-topics"></div>
						<h2>Relevant topics</h2>
					</label>
					<div class="content">
						<p>Placeholder</p>
					</div>
				</section>
				<section id="lessons">
					<input checked id="lessons_toggle" type="checkbox" />
					<label class="heading" for="lessons_toggle">
						<div class="icon icon-lessons"></div>
						<h2>Lesson family</h2>
					</label>
					<div class="content">
						<p>
							<a onclick="showPopup('add_lesson');">Add lesson</a>
						</p>
						<div class="lessons">
s;

echo getLessonsTreeLessonHTML($lesson_family);

echo <<<s
						</div>
					</div>
					<script>
						$('[data-lesson-id={$lesson_data['Id']}]').addClass('active');
						$('[data-lesson-id={$lesson_data['Id']}]').parents('.tree_item').each(function () {
							$(this).children('input[type=checkbox].toggler').prop('checked', true);
						});
					</script>
				</section>
				<section id="notes">
					<input id="notes_toggle" type="checkbox" />
					<label class="heading" for="notes_toggle">
						<div class="icon icon-ul"></div>
						<h2>Saved notes</h2>
					</label>
					<div class="content">
						<p>Placeholder</p>
					</div>
				</section>
			</div>
		</div>
	</div>
	<div class="popup" id="edit_lesson_title">
		<div class="box">
			<div class="heading">
				<h3>
					<span class="icon icon-lesson"></span>
					Edit title
				</h3>
			</div>
			<form action="edit_lesson_title" class="content" data-type="api">
				<p>
					<label>Title</label>
					<input name="lesson_title" type="text" value="{$lesson_data['Title']}" />
				</p>
				<p>
					<input name="lesson_id" type="hidden" value="{$lesson_data['Id']}" />
					<button>Submit</button>
				</p>
			</form>
		</div>
	</div>
	<div class="popup" id="add_lesson">
		<div class="box">
			<div class="heading">
				<h3>
					<span class="icon icon-lesson"></span>
					Add lesson
				</h3>
			</div>
			<form action="add_lesson" class="content" data-type="api">
				<p>
					<label>Title</label>
					<input name="lesson_title" type="text" />
				</p>
				<p>
					<label>Parent</label>
					<select name="lesson_parent_id">
						{$lessons_tree_options}
					</select>
				</p>
				<p>
					<button>Submit</button>
				</p>
			</form>
		</div>
	</div>
	<div class="popup" id="link_lesson_to_topic">
		<div class="box">
			<div class="heading">
				<h3>
					<span class="icon icon-lesson"></span>
					Link lesson to topic
				</h3>
			</div>
			<form action="link_lesson_to_topic" class="content" data-type="api">
				<p>
					<label>Topic</label>
					<select name="topic_id">
						{$topics_select_options}
					</select>
				</p>
				<p>
					<input name="lesson_id" type="hidden" value="{$lesson_data['Id']}" />
					<button>Submit</button>
				</p>
			</form>
		</div>
	</div>
	<div class="popup" id="add_lesson_tag_popup">
		<div class="box">
			<div class="heading">
				<h3>
					<span class="icon icon-bible"></span>
					Tag verse
				</h3>
			</div>
			<form action="add_lesson_tag" class="content" data-type="api">
				<p>
					<label>Reference</label>
					<input name="reference_string" type="text" />
				</p>
				<p>
					<input name="lesson_id" type="hidden" value="{$lesson_data['Id']}" />
					<button>Submit</button>
				</p>
			</form>
		</div>
	</div>
s;

# Display footer
require_once 'components/footer.php';

?>