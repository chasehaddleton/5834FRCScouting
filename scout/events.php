<?php
require_once('../components/common.php');

verifyPermission($_SESSION['level'], 0);

printHead("Events");
printNav();
?>

<div class="row">
	<div class="small-12 columns">
		<h3>Events</h3>
		<p>This page will show event data and stuff.... Soon</p>
	</div>
</div>

<?php printFooter() ?>