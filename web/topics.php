<?

# Require files
require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

# Get topics list
$topics_list = getTopicsList();

require_once 'components/header.php';

?>

<div class="page" id="topics_page">
	<h1>Topics</h1>

	<?

	var_dump($topics_list);
	die;

	?>

</div>

<?

require_once 'components/footer.php';

?>