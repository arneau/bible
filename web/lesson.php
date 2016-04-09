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
		<section>
			<h1>
				{$lesson_title_html}
				<span>(Lesson)</span>
			</h1>
		</section>
		<div class="columns">
			<div class="column">
				<section class="page_section" id="passages">
					<div class="heading">
						<div class="icon icon-bible"></div>
						<h2>Tagged verses</h2>
					</div>
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
				<section class="page_section" id="topics">
					<div class="heading">
						<div class="icon icon-topics"></div>
						<h2>Relevant topics</h2>
					</div>
					<div class="content">
						<p>Placeholder</p>
					</div>
				</section>
				<section class="page_section" id="lessons">
					<div class="heading">
						<div class="icon icon-lessons"></div>
						<h2>Lesson family</h2>
					</div>
					<div class="content">
						<ul>
s;

# Display lesson family
if ($lesson_ancestors) {
	$lesson_family_root = getLesson($lesson_ancestors[1]['Id']);
} else {
	$lesson_family_root = $lesson_object;
}
$lesson_family_members_array[] = $lesson_family_root->toArray();
$lesson_family_members_array = array_merge($lesson_family_members_array, $lesson_family_root->getDescendants()->toArray());
foreach ($lesson_family_members_array as $lesson_family_member_data) {
	$lesson_family_member_padding = ($lesson_family_member_data['TreeLevel'] - 1) * 20;
	if ($lesson_family_member_data['Id'] == $lesson_data['Id']) {
		$lesson_family_member_class = ' class="current"';
	} else {
		$lesson_family_member_class = '';
	}
	echo <<<s
						<li style="margin-left: {$lesson_family_member_padding}px;">
							<a{$lesson_family_member_class} href="?id={$lesson_family_member_data['Id']}">{$lesson_family_member_prefix} {$lesson_family_member_data['Summary']}</a>
						</li>
s;
}

echo <<<s
						</ul>
					</div>
				</section>
				<section class="page_section" id="notes">
					<div class="heading">
						<div class="icon icon-ul"></div>
						<h2>Saved notes</h2>
					</div>
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