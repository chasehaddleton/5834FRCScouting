<?php
require_once("../components/common.php");
require_once($setting->getAppPath() . "/components/Data/Team.php");

verifyPermission($_SESSION['level'], 0);

printHead("API");
printNav();
?>
	<div class="row">
		<div class="small-12 columns">
			<h3><?php echo $setting->getApplicationName() ?></h3>
			<p>
				Welcome to our API! To learn how to use our API, please view our documentation on GitHub here.
			</p>
		</div>
	</div>

<?php printFooter(); ?>