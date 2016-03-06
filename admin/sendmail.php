<?php
require($_SERVER['DOCUMENT_ROOT'] . '/components/common.php');

verifyPermission($_SESSION['level'], 1);

$phpfilename = basename(__FILE__);

printHead("Send Mail");
printNav();
?>

<section id="main">
	<div class="row">
		<div class="small-12 columns">
			<?php
			if (!empty($_POST) || !empty($_GET)) {
				if ($_POST['action'] == "newemail") {
					$team = $_POST['team'];
					$subject = $_POST['subject'];
					$body = $_POST['emailbody'];

					$query = "INSERT INTO mailjobs (mailTime, team, subject, body, createdby) VALUES (:mailTime, :team, :subject, :body, :createdby)";

					$query_params = array(':mailTime' => date('Y-m-d G:i:s'), ":team" => $team, ":subject" => $subject, ":body" => $body, ":createdby" => $_SESSION['userid']);

					$stmt = executeSQL($query, $query_params);

					if ($_POST['team'] == "executive") {
						$query = "SELECT id, fullname, email FROM users";

						$stmt = executeSQL($query, null);
					} else {
						$query = "SELECT id, fullname, email FROM maillist";

						if ($_POST['team'] != "all") {
							$query .= " WHERE team LIKE '%:team%' OR team LIKE '%All%' OR team IS NULL";
						}

						$query .= " UNION DISTINCT SELECT id, fullname, email FROM users";
						$query_params = array(':team' => $_POST['team']);

						$stmt = executeSQL($query, $query_params);
					}

					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
						sendMail($row['fullname'], $row['email'], $subject, $body);
					}

					echo "<h1>Complete</h1>";

				}
			}
			?>
		</div>
	</div>
</section>

<?php printFooter(); ?>
