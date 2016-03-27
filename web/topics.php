<?

# Require files
require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

# Get topics list
$topics_list = getTopicsList();

# Display header
require_once 'components/header.php';

# Start page
echo <<<s
	<div class="page" id="topics_page">
		<section>
			<button class="toggle_adoptees">adoptees</button>
			<h1>Topics</h1>
		</section>
s;

# Display topics
$is_adoptee = false;
foreach ($topics_list as $topic_data) {

	# Check if no longer adoptee
	if (isset($adoptees_stop_at_level) && $topic_data['Level'] <= $adoptees_stop_at_level) {
		$is_adoptee = false;
	}

	# Check if adoptee
	if (!$is_adoptee && isset($topic_data['IsAdoptee']) && $topic_data['IsAdoptee']) {
		$is_adoptee = true;
		$adoptees_stop_at_level = $topic_data['Level'];
	}

	# Define adoptee class
	if ($is_adoptee) {
		$adoptee_class = 'adoptee';
	} else {
		$adoptee_class = '';
	}

	# Define offset
	$offset = 40 * $topic_data['Level'] . 'px';

	# Echo topic
	echo <<<s
			<div class="topic $adoptee_class" style="margin-left: $offset;">
				<h3>$topic_data[Name]</h3>
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