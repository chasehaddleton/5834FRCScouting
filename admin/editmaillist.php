<?php
require($_SERVER['DOCUMENT_ROOT'] . '/components/common.php');

verifyPermission($_SESSION['level'], 1);

$phpfilename = basename(__FILE__);
$LIMIT_PER_PAGE = 20;

printHead("Edit Mailing List");
printNav();
?>

<section id="main">
	<div class="row">
		<div class="small-12 columns">
			<?php
			if (isset($_POST['action'])) {
				if ($_POST['action'] == "update") {

					$query = "UPDATE maillist SET fullname = :fullname, email = :email, team = :team WHERE id = :id";

					$query_params = array(':id' => $_POST['id'], ':fullname' => $_POST['name'], ':email' => $_POST['email'], ':team' => $_POST['team']);

					$stmt = executeSQL($query, $query_params);

					$_SESSION['Msg'] = "User successfully updated!";

					logMessage("Client updated: id = " . $_POST['id'], $_SESSION['userid'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR']);

					redirectToSelf($phpfilename);

				} elseif ($_POST['action'] == "newrec") {
					$query = "INSERT INTO maillist (fullname, email, team) VALUES (:fullname, :email, :team)";

					$query_params = array(':fullname' => $_POST['name'], ':email' => $_POST['email'], ':team' => $_POST['team']);

					$stmt = executeSQL($query, $query_params);

					$_SESSION['Msg'] = "User successfully added!";

					$id = $db->lastInsertid();

					logMessage("Client added: id = " . $id, $_SESSION['userid'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR']);

					redirectToSelf($phpfilename);
				}
			} elseif (isset($_GET['action'])) {
				if ($_GET['action'] == "edit") {
					$query = "SELECT * FROM maillist WHERE id = :id";
					$query_params = array(':id' => $_GET['id']);

					$stmt = executeSQL($query, $query_params);

					$row = $stmt->fetch();
					$id = $row["id"];
					$fullName = $row["fullname"];
					$email = $row["email"];
					$team = $row['team'];

					echo "<div class='medium-6 large-4 columns medium-offset-3 large-offset-4'>";
					echo "<div class='panel'>";
					echo "<form action='$phpfilename' method='POST'>";
					echo "<input type='hidden' name='action' value='update'>";
					echo "<input type='hidden' name='id' value='$id'>";
					echo "<input type='text' name='name' placeholder='Name' value='$fullName'>";
					echo "<input type='text' name='email' placeholder='Email' value='$email'>";
					echo "<select name='team'><option value='$team'>$team</option><option value='All'>All</option><option value='Business'>Business</option><option value='Construction'>Construction</option><option value='Programming'>Programming</option></select>";
					echo "<button class='no-bottom-margin' type='NewRec' value='NewRec'>Submit</button>";
					echo "</form>";
					echo "</div>";
					echo "</div>";

				} elseif ($_GET['action'] == "delete") {

					$query = "DELETE FROM maillist WHERE id = :id";
					$query_params = array(':id' => $_GET['id']);

					$stmt = executeSQL($query, $query_params);

					$_SESSION['Msg'] = "User successfully deleted!";

					logMessage("User deleted: id = " . $_GET['id'], $_SESSION['userid'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR']);

					redirectToSelf($phpfilename . "?page=" . intval($_GET['page']));
				}
			} else {
				displayMsg();

				if (!isset($_GET['page'])) {
					$_GET['page'] = 1;
				}

				displayTable("maillist", $LIMIT_PER_PAGE, $_GET['page'], $phpfilename, true);

				echo "<div class='row'>";
				echo "<div class='small-10 columns'>";

				$query = "SELECT COUNT(*) FROM maillist";

				$stmt = executeSQL($query, null);

				$count = $stmt->fetch();
				$count = (int)$count['COUNT(*)'];

				pagination($count, $LIMIT_PER_PAGE, $_GET['page'], $phpfilename);

				echo "</div>";
				?>
				<div class='small-2 columns'>
					<a class='button round' data-reveal-id='clientModal'>Add</a>
				</div>

				<div id="clientModal" class="reveal-modal" data-reveal aria-labelledby="modalTitle"
				     aria-hidden="true"
				     role="dialog">

					<form action='<?php echo $phpfilename ?>' method='POST'>
						<input type='hidden' name='action' value='newrec'>
						<input type='text' name='name' placeholder='Full Name'>
						<input type='text' name='email' placeholder='Email'>
						<select name='team'>
							<option value='' selected="selected" disabled="disabled">Select Team</option>
							<option value='All'>All</option>
							<option value='Business'>Business</option>
							<option value='Construction'>Construction</option>
							<option value='Programming'>Programming</option>
						</select>
						<button class="no-bottom-margin" type='NewRec' value='NewRec'>Submit</button>
					</form>

					<a class="close-reveal-modal" aria-label="Close">&#215;</a>
				</div>
			<?php } ?>
		</div>
	</div>
</section>

<?php printFooter(); ?>
