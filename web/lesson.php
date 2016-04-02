<?

# Require files
require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

# Display header
require_once 'components/header.php';

# Get lesson, data, etc
$lesson_data = getLessonData($_GET['id']);
//$lessons_passages = getLessonTags($_GET['id']);

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
					<h3>Tagged passages</h3>
					<blockquote class="passage">
						<sup>14</sup> <mark>What is man, that he should be clean?</mark> and he which is born of a woman, that he should be righteous?
						<cite>
							<span class="reference">Job 15:14</span> &middot;
							<span class="bible" data-info="King James Version">KJV</span> &middot;
							<span class="vote_count">0</span> votes
							<span class="vote_up">
								<img src="assets/images/arrow_up.png" />
							</span>
							<span class="vote_down">
								<img src="assets/images/arrow_down.png" />
							</span>
						</cite>
					</blockquote>
s;
echo getPassageHTMLByVerseId(30530);
echo <<<s
				</section>
			</div>
			<div class="column">
				<section class="topics">
					<h3>Relevant topics</h3>
				</section>
				<section class="lessons">
					<h3>Lesson family</h3>
				</section>
				<section class="notes">
					<h3>Saved notes</h3>
				</section>
			</div>
		</div>
	</div>
s;

# Display footer
require_once 'components/footer.php';

?>