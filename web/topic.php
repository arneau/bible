<?

# Require files
require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

# Display header
require_once 'components/header.php';

# Get lesson, data, etc
$topic_object = getTopic($_GET['id']);
$topic_data = getTopicData($_GET['id']);
$topic_tags = getTopicTags($_GET['id'], $_GET['order_passages_by']);

# Get topics select options
$topics_select_options = getTopicsSelectOptions();

# Start page
echo <<<s
	<div class="page" id="lesson_page">
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
						<p>Placeholder</p>
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
					<label>Summary</label>
					<input name="topic_summary" type="text" />
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
s;

# Display footer
require_once 'components/footer.php';

?>