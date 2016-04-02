<?

# Require files
require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

# Display header
require_once 'components/header.php';

# Start page
echo <<<s
	<div class="page" id="lesson_page">
		<section>
			<h1>
				Lesson:
				<span>By Jesus are all things</span>
			</h1>
		</section>
		<div class="columns">
			<div class="column">
				<section class="passages">
					<h3>Relevant Passages</h3>
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
					<blockquote class="passage">
						<sup>9</sup> Who can say, I have made my heart clean, I am pure from my sin?
						<cite>Proverbs 20:9 | KJV</cite>
					</blockquote>
					<blockquote class="passage">
						<sup>8</sup> If we say that we have no sin, we deceive ourselves, and the truth is not in us.
						<cite>1 John 1:8 | KJV</cite>
					</blockquote>
				</section>
			</div>
			<div class="column">
				<section>asd</section>
				<section>asd</section>
			</div>
		</div>
	</div>
s;

# Display footer
require_once 'components/footer.php';

?>