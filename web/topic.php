<?

# Require files
require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

# Display header
require_once 'components/header.php';

# Get lesson and data
$topic_object = getTopic($_GET['id']);
$topic_data = getTopicData($_GET['id']);

# Get topics select options
//$topics_select_options = getTopicsSelectOptions();

# Start page
echo <<<s
	<div class="page" id="topic_page">
		<section class="page_heading">
			<h1>{$topic_data['name']['formatted']}</h1>
			<button class="icon-pencil" onclick="showPopup('edit_topic');"></button>
			<button class="icon-topics" onclick="showPopup('link_lesson_to_topic');"></button>
			<button class="icon-close" onclick="deleteTopic('{$topic_data['Id']}');"></button>
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
						<div class="actions">
							<div class="columns">
								<div class="column">
									<p>
										<a onclick="showPopup('add_topic_tag');">Add tag</a>
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
$topic_tags = getTopicTags($_GET['id'], $_GET['order_passages_by']);
if ($topic_tags) {
	foreach ($topic_tags as $lesson_tag_data) {
		echo getTagHTML($lesson_tag_data['id']);
	}
}

echo <<<s
					</div>
				</section>
			</div>
			<div class="column">
				<section id="topics_and_lessons">
					<input checked id="topics_and_lessons_toggle" type="checkbox" />
					<label class="heading" for="topics_and_lessons_toggle">
						<div class="icon icon-topics"></div>
						<h2>Topics & Lessons</h2>
					</label>
					<div class="content">
						<p class="actions">
							<a onclick="showPopup('add_topic');">Add topic</a>
							&middot;
							<a onclick="showPopup('add_lesson');">Add lesson</a>
						</p>
						<div class="list">
s;

# Get root ID
$topic_ancestors_objects = $topic_object->getAncestors();
if ($topic_ancestors_objects[1]) {
	$topic_root_id = $topic_ancestors_objects[1]->getId();
} else {
	$topic_root_id = $topic_object->getId();
}

# Display topic and lesson family
$topics_objects = TopicQuery::create()
	->filterByPrimaryKeys([
		$topic_root_id
	])
	->find();
if ($topics_objects->count()) {
	$topic_children_list_items = getCategoryListItems($topics_objects);
	foreach ($topic_children_list_items as $topic_children_list_item_data) {
		echo getCategoryListItemHTML($topic_children_list_item_data);
	}
}

echo <<<s
						</div>
						<script>
							$('[data-topic-id={$topic_data['Id']}]').addClass('active');
						</script>
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
	<div class="popup" id="edit_topic">
		<div class="box">
			<div class="heading">
				<h3>
					<span class="icon icon-topics"></span>
					Edit topic
				</h3>
			</div>
			<form action="edit_topic" class="content" data-type="api">
				<p>
					<label>Name</label>
					<input name="topic[name]" type="text" value="{$topic_data['Name']}" />
				</p>
				<p>
					<input name="topic[id]" type="hidden" value="{$topic_data['Id']}" />
					<button>Submit</button>
				</p>
			</form>
		</div>
	</div>
	<div class="popup" id="add_topic">
		<div class="box">
			<div class="heading">
				<h3>
					<span class="icon icon-topic"></span>
					Add topic
				</h3>
			</div>
			<form action="add_topic" class="content" data-type="api">
				<p>
					<label>Name</label>
					<input name="topic_name" type="text" />
				</p>
				<p>
					<input name="topic_id" type="hidden" value="{$topic_data['Id']}" />
					<button>Submit</button>
				</p>
			</form>
		</div>
	</div>
	<div class="popup" id="add_topic_tag">
		<div class="box">
			<div class="heading">
				<h3>
					<span class="icon icon-bible"></span>
					Tag verse
				</h3>
			</div>
			<form action="add_topic_tag" class="content" data-type="api">
				<p>
					<label>Reference</label>
					<input name="reference_string" type="text" />
				</p>
				<p>
					<input name="topic[id]" type="hidden" value="{$topic_data['Id']}" />
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
					<label>Summary</label>
					<input name="lesson_summary" type="text" />
				</p>
				<p>
					<input name="topic_id" type="hidden" value="{$topic_data['Id']}" />
					<button>Submit</button>
				</p>
			</form>
		</div>
	</div>
s;

# Display footer
require_once 'components/footer.php';

?>