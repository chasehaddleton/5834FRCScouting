<?php
require($_SERVER['DOCUMENT_ROOT'] . '/components/common.php');
require($_SERVER['DOCUMENT_ROOT'] . '/components/Users.php');

verifyPermission($_SESSION['level'], 2);

$phpFileName = basename(__FILE__);
$LIMIT_PER_PAGE = 25;

printHead("Users");
printNav();
?>

	<section id="main">
		<div class="row">
			<div class="small-12 columns">
				<?php
				if (isset($_POST['action'])) {
					if ($_POST['action'] == "update") {

						$user = new Users(intval($_POST['id']));

						if (isset($_POST['name']) && $_POST['name'] != "") {
							$user->fullname = $_POST['name'];
						}

						if (isset($_POST['email']) && $_POST['email'] != "") {
							$user->email = $_POST['email'];
						}

						if (isset($_POST['team']) && $_POST['team'] != "") {
							$user->team = $_POST['team'];
						}

						if (isset($_POST['password']) && $_POST['password'] != "") {
							$user->password = $_POST['password'];
						}

						if (isset($_POST['level']) && $_POST['level'] != "") {
							$user->level = intval($_POST['level']);
						}

						$_SESSION['Msg'] = "User successfully updated!";

						logMessage("User updated: id = " . intval($_POST['id']), $_SESSION['userid'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR']);

						redirectToSelf($phpFileName);

					} elseif ($_POST['action'] == "newrec") {
						$query = "INSERT INTO users (fullname, email, team, password, level) VALUES (:fullname, :email, :team, :password, :level)";

						$hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

						$query_params = array(':fullname' => $_POST['name'], ':email' => $_POST['email'], ':team' => $_POST['team'], ':password' => $hash, ':level' => intval($_POST['level']));

						try {
							$stmt = $db->prepare($query);
							$result = $stmt->execute($query_params);
						} catch (PDOException $ex) {
							logMessage("Failed to run query: " . $ex->getMessage() . ". Query: " . $query, $_SESSION['userid'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR']);

							$_SESSION['errorMsg'] = "SQL Error, please try again or inform the webmaster. A log was made of this error.";

							redirectToSelf($phpFileName);
						}

						$_SESSION['Msg'] = "User successfully added!";

						$id = $db->lastInsertid();

						logMessage("User added: id = " . $id, $_SESSION['userid'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR']);

						redirectToSelf($phpFileName);
					}
				} elseif (isset($_GET['action'])) {
					if ($_GET['action'] == "edit") {
						$user = new Users(intval($_GET['id']));

						echo "<div class='medium-6 large-4 columns medium-offset-3 large-offset-4'>";
						echo "<div class='panel'>";
						echo "<form action='$phpFileName' method='POST'>";
						echo "<input type='hidden' name='action' value='update'>";
						echo "<input type='hidden' name='id' value='$user->id'>";
						echo "<input type='text' name='name' placeholder='Name' value='$user->fullname'>";
						echo "<input type='text' name='email' placeholder='Email' value='$user->email'>";
						echo "<select name='team'><option value='$user->team'>$user->team</option><option value='All'>All</option><option value='Business'>Business</option><option value='Construction'>Construction</option><option value='Programming'>Programming</option></select>";
						echo "<input type='password' name='password' placeholder='New Password'>";
						echo "<input type='number' name='level' value='$user->level'>";
						echo "<button class='no-bottom-margin' type='update' value='update'>Submit</button>";
						echo "</form>";
						echo "</div>";
						echo "</div>";

					} elseif ($_GET['action'] == "delete") {
						$user = new Users(intval($_GET['id']));

						$user->delete();

						$_SESSION['Msg'] = "User successfully deleted!";

						logMessage("User deleted: id = " . $_GET['id'], $_SESSION['userid'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR']);

						redirectToSelf($phpfilename . "?page=" . intval($_GET['page']));
					}
				} else {
					displayMsg();

					if (!isset($_GET['page'])) {
						$_GET['page'] = 1;
					}

					displayTable("users", $LIMIT_PER_PAGE, $_GET['page'], $phpFileName, true);

					echo "<div class='row'>";
					echo "<div class='small-10 columns'>";

					$query = "SELECT COUNT(*) AS count FROM users";

					$stmt = executeSQL($query, null);

					$count = $stmt->fetch();
					$count = intval($count['count']);

					pagination($count, $LIMIT_PER_PAGE, $_GET['page'], $phpFileName);

					echo "</div>";
					?>
					<div class='small-2 columns'>
						<a class='button round' data-reveal-id='userModal'>Add</a>
					</div>
					<div id="userModal" class="reveal-modal" data-reveal aria-labelledby="modalTitle"
					     aria-hidden="true"
					     role="dialog">

						<form action='<?php echo $phpFileName ?>' method='POST'>
							<input type='hidden' name='action' value='newrec'>
							<input type='text' name='name' placeholder='Full Name'>
							<input type='text' name='email' placeholder='Email'>
							<select name='team'>
								<option value='All'>All</option>
								<option value='Business'>Business</option>
								<option value='Construction'>Construction</option>
								<option value='Programming'>Programming</option>
							</select>
							<input type='text' name='password' placeholder='Password'>
							<input type='number' name='level' placeholder='Level'>
							<button class='no-bottom-margin' type='newrec' value='newrec'>Submit</button>
						</form>

						<a class="close-reveal-modal" aria-label="Close">&#215;</a>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>

<?php printFooter(); ?>