<?php
require_once("../components/common.php");
require_once($setting->getAppPath() . "/components/Data/Team.php");

verifyPermission($_SESSION['level'], 0);

printHead("Team Directory");
printNav();

$query = "SELECT fullName, email, phoneNumber FROM Users WHERE teamNumber = :teamNumber ORDER BY fullName ASC";
$query_params = array(':teamNumber' => $_SESSION['teamNumber']);
$stmt = executeSQL($query, $query_params);
$results = $stmt->fetchAll();
?>

	<div class="row">
		<div class="small-12 columns">
			<h3>Team Directory</h3>
			<?php displayMsg(); ?>
			<ul class="accordion" data-accordion data-multi-expand="true" data-allow-all-closed="true">
				<?php
				foreach ($results as $user) {
					echo "<li class=\"accordion-item\" data-accordion-item>";
					echo "<a href=\"#\" class=\"accordion-title\">" . $user['fullName'] . "</a>";
					echo "<div class=\"accordion-content\" data-tab-content>";
					echo "Email: " . $user['email'];
					if (intval($user['phoneNumber']) != -1) {
						echo "<br>Phone Number: " . $user['phoneNumber'];
					}
					echo "</div>";
					echo "</li>";
				}
				?>
			</ul>
		</div>
	</div>

<?php printFooter(); ?>