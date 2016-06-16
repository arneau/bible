<?

require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

require_once 'components/header.php';

echo <<<s
	<div class="page" id="read_page">
		<section class="page_section">
			<h2>God's Word</h2>
		</section>
		<section class="page_section">
			<form>
				<p>
					<input name="reference" type="text" value="{$_GET['reference']}" />
				</p>
			</form>
		</section>
s;

if ($_GET['reference']) {

	# Define passage HTML data
	$passage_html_data = [
		'bible_code' => 'kjv',
		'reference_string' => $_GET['reference'],
		'show_tags' => true,
	];

	# Get passage HTML
	$passage_html = getPassageHTML($passage_html_data);

	# Echo passage HTML
echo <<<s
		<section>
			{$passage_html}
		</section>
s;

}

echo <<<s
	</div>
s;

require_once 'components/footer.php';

?>