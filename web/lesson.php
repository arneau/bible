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

# Build lesson title
$lesson_ancestors = $lesson_object->getAncestors()->toArray();
unset($lesson_ancestors[0]);
foreach ($lesson_ancestors as $lesson_ancestor) {
	$lesson_title_html .= '<span>' . $lesson_ancestor['Summary'] . ' / </span>';
}
$lesson_title_html .= $lesson_data['Summary'];

# Start page
echo <<<s
	<div class="page" id="lesson_page">
		<section class="page_heading">
			<h1>{$lesson_title_html}</h1>
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
						<div class="buttons">
							<button class="icon-bible-add" onclick="showPopup('add_lesson_popup');"></button>
						</div>
s;

# Display lesson tags
$lesson_tags = getLessonTags($lesson_data['Id']);
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
					<input id="lessons_toggle" type="checkbox" />
					<label class="heading" for="lessons_toggle">
						<div class="icon icon-lessons"></div>
						<h2>Lesson family</h2>
					</label>
					<div class="content">
s;

# Display lesson family
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
						<a class="lesson" href="?id={$lesson_family_member_data['Id']}" style="margin-left: {$lesson_family_member_margin}px; width: calc(100% - {$lesson_family_member_margin}px);">
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
	<div class="popup" id="add_lesson_popup">
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