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

# Start page
echo <<<s
	<div class="page" id="lesson_page">
		<section class="page_heading">
			<h1>{$lesson_data['summary']['formatted']}</h1>
			<button class="icon-pencil" onclick="showPopup('edit_lesson_summary_popup');"></button>
			<button class="icon-close" onclick="deleteLesson('{$lesson_data['Id']}');"></button>
		</section>
		<div class="columns">
			<div class="column">
				<section id="passages">
					<input checked id="passages_toggle" type="checkbox" />
					<label class="heading" for="passages_toggle">
						<div class="icon icon-bible"></div>
						<h2>Tagged verses</h2>
					</label>
					<div class="content">
						<div>
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
s;

# Display lesson family
$lesson_ancestors = $lesson_object->getAncestors()->toArray();
unset($lesson_ancestors[0]);
if ($lesson_ancestors) {
	$lesson_family_root = getLesson($lesson_ancestors[1]['Id']);
} else {
	$lesson_family_root = $lesson_object;
}
if ($lesson_family_root->getDescendants()) {
	$lesson_family_members_array = array_merge([
		$lesson_family_root->toArray()
	], $lesson_family_root->getDescendants()->toArray());
} else {
	$lesson_family_members_array[] = $lesson_family_root->toArray();
}
foreach ($lesson_family_members_array as $lesson_family_member_data) {
	$lesson_family_member_margin = ($lesson_family_member_data['TreeLevel'] - 1) * 20;
	if ($lesson_family_member_data['Id'] == $lesson_data['Id']) {
		$lesson_family_member_class = ' class="current"';
	} else {
		$lesson_family_member_class = '';
	}
	echo <<<s
						<a class="lesson" data-lesson-id="{$lesson_family_member_data['Id']}" href="?id={$lesson_family_member_data['Id']}" style="margin-left: {$lesson_family_member_margin}px; width: calc(100% - {$lesson_family_member_margin}px);">
							<div class="content">
								<h3{$lesson_family_member_class}>{$lesson_family_member_data['Summary']}</h3>
								<p><span class="icon icon-bible"></span>Tagged verses: {$lesson_family_member_data['TagCount']}</p>
							</div>
						</a>
s;
}

echo <<<s
					</div>
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
	<div class="popup" id="edit_lesson_summary_popup">
		<div class="box">
			<div class="heading">
				<h3>
					<span class="icon icon-lesson"></span>
					Edit summary
				</h3>
			</div>
			<form action="edit_lesson_summary" class="content" data-type="api">
				<p>
					<label>Summary</label>
					<input name="lesson_summary" type="text" value="{$lesson_data['Summary']}" />
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
					<label>Verse</label>
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