<?php
require_once("components/common.php");
require_once($setting->getAppPath() . "/components/Team.php");

verifyPermission($_SESSION['level'], 0);

printHead("Index");
printNav();
?>
	<header class="hero heavy-accent">
		<div class="row">
			<div class="large-12 columns">
				<h2>Welcome to 5834's Scouting App
					<small>Version 0.01</small>
				</h2>
				<?php displayMsg(); ?>
			</div>
		</div>

		<div class="row">
			<div class="large-12 columns">
				<div class="callout">
					<h4>This page is under construction</h4>
					<p>But hopefully things will be done soon. Stay tuned.</p>
				</div>
			</div>
		</div>
	</header>


<?php if (isset($_SESSION['userId'])) {
	$team = new Team($_SESSION['teamNumber']);
	?>
	<div class="row">
		<div class="small-12 columns">
			<h4>Team <?php echo $_SESSION['teamNumber'] ?>'s dashboard </h4>
			<p>
				Your team has <?php echo $team->getNumberOfScoutMembers(); ?> person registered for this app.
				<br>
				<small>
					Click <a href="<?php echo $setting->getAppURL() ?>/team/">here</a> to see a directory.
				</small>
			</p>
		</div>
	</div>
<?php } ?>

<?php printFooter(); ?>