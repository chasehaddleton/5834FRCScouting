<?php
require_once("../common.php");

verifyPermission($_SESSION['level'], 0);

printHead("API");
printNav();
?>
	<div class="row">
		<div class="small-12 columns">
			<h3><?php echo $setting::applicationName ?></h3>
			<p>
				Welcome to our API! To learn how to use our API, please view our documentation on GitHub here.
			</p>
		</div>
	</div>

<?php printFooter(); ?>