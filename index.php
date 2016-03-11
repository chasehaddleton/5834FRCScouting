<?php
include_once("components/common.php");

verifyPermission($_SESSION['level'], 0);

printHead("Index");
printNav();
?>
	<div class="row">
		<div class="large-12 columns">
			<h2>Welcome to 5834's Scouting App</h2>
			<?php displayMsg(); ?>
		</div>
	</div>

	<div class="row">
		<div class="large-12 columns">
			<div class="callout large">
				<h4>This page is under construction</h4>
				<p>But hopefully things will be done soon. Stay tuned.</p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
			<h4>Team <?php echo $_SESSION['teamNumber'] ?>'s dashboard </h4>

		</div>
	</div>

<?php printFooter(); ?>