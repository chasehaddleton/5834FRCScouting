<?php
require_once("../components/Settings.php");
$setting = new Settings();

require_once($setting->getAppPath() . '/components/common.php');

verifyPermission($_SESSION['level'], 2);

$phpfilename = basename(__FILE__);
$LIMIT_PER_PAGE = 25;

printHead("Logs");
printNav();
?>

<section id="main">
	<div class="row">
		<div class="col s12 m8 offset-m2 l12">
			<h2>Logs</h2>

			<?php displayMsg(); ?>

			<table class='highlight'>
				<thead>
				<tr>
					<th>ID</th>
					<th>Date</th>
					<th>Message</th>
					<th>User</th>
					<th>IP</th>
					<th>XFF IP</th>
				</tr>
				</thead>
				<tbody>
				<?php
				$query = "SELECT logs.id, logs.logTime, logs.message, users.fullname, logs.ip, logs.severity
							FROM logs
							LEFT JOIN users ON users.id = logs.userID
							ORDER BY logs.id DESC LIMIT " . $LIMIT_PER_PAGE;

				if (isset($_GET['page'])) {
					if (intval($_GET['page']) > 1) {
						$offset = $LIMIT_PER_PAGE * (intval($_GET['page']) - 1);
						$query .= " OFFSET " . $offset;
					}
				} else {
					$_GET['page'] = 1;
				}

				$stmt = executeSQL($query, null);
				$results = $stmt->fetchAll();

				foreach ($results as $row) {
					echo "<tr>";
					foreach ($row as $col => $data) {
						echo "<td>$data</td>";
					}
					echo "</tr>";
				}

				echo "</tbody>";
				echo "</table>";
				echo "</div>";

				$query = "SELECT COUNT(*) AS count FROM logs";

				$stmt = executeSQL($query, null);

				$count = $stmt->fetch();
				$count = intval($count['count']);

				pagination($count, $LIMIT_PER_PAGE, $_GET['page'], $phpfilename);
				?>
		</div>
</section>

<?php printFooter(); ?>
