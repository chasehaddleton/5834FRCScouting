<?php
require($_SERVER['DOCUMENT_ROOT'] . '/components/common.php');
require($_SERVER['DOCUMENT_ROOT'] . '/components/Sponsors.php');

verifyPermission($_SESSION['level'], 2);

$phpFileName = basename(__FILE__);
$LIMIT_PER_PAGE = 25;

printHead("edit Sponsors");
printNav();
?>

	<section id="main">
		<div class="row">
			<div class="small-12 columns">
				<?php
				if (isset($_POST['action'])) {
					if ($_POST['action'] == "update") {
						$sponsor = new Sponsors(intval($_POST['id']));
						
						if (isset($_POST['name']) && $_POST['name'] != "") {
							$sponsor->name = $_POST['name'];
						}
						
						if (isset($_POST['amount']) && $_POST['amount'] != "") {
							$sponsor->amount = $_POST['amount'];
						}
						
						if (isset($_POST['uri']) && $_POST['uri'] != "") {
							$sponsor->uri = $_POST['uri'];
						}

						if ($_POST['donor']) {
							$sponsor->donor = 1;
						} else {
							$sponsor->donor = 0;
						}

						if (!empty($_FILES)) {
							$logo = $_FILES['logo'];

							if ($logo['size'] > 0) {
								$newFileName = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(" ", "-", strtolower($_POST['name']))) . "." . getFileExtension($logo['name']);

								$img_uri = "/images/sponsors/" . $newFileName;

								unlink(realpath(UPLOAD_ROOT . $sponsor->img_uri));
								move_uploaded_file($logo['tmp_name'], UPLOAD_ROOT . $img_uri);

								$sponsor->img_uri = $img_uri;
							} else {
								$sponsor->img_uri = null;
							}
						}

						$_SESSION['Msg'] = "Sponsor successfully updated!";
						
						logMessage("Sponsor updated: id = " . intval($_POST['id']), $_SESSION['userid'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR']);
						
						redirectToSelf($phpFileName);

					} elseif ($_POST['action'] == "newrec") {
						$img_uri = null;

						if (!empty($_FILES)) {
							$logo = $_FILES['logo'];
							if ($logo['size'] > 0) {
								$newFileName = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(" ", "-", strtolower($_POST['name']))) . "." . getFileExtension($logo['name']);

								$img_uri = "/images/sponsors/" . $newFileName;

								unlink(realpath(UPLOAD_ROOT . $sponsor->img_uri));
								move_uploaded_file($logo['tmp_name'], UPLOAD_ROOT . $img_uri);
							}
						}

						$query = "INSERT INTO sponsors (name, amount, uri, img_uri, donor) VALUES (:name, :amount, :uri, :img_uri, :donor)";

						$query_params = array(':name' => ucwords($_POST['name']), ':amount' => intval($_POST['amount']), ':uri' => $_POST['uri'], ':img_uri' => $img_uri, ':donor' => (($_POST['donor']) ? 1 : 0));

						try {
							$stmt = $db->prepare($query);
							$result = $stmt->execute($query_params);
						} catch (PDOException $ex) {
							logMessage("Failed to run query: " . $ex->getMessage() . ". Query: " . $query, $_SESSION['userid'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR']);

							$_SESSION['errorMsg'] = "SQL Error, please try again or inform the webmaster. A log was made of this error.";

							redirectToSelf($phpFileName);
						}

						$_SESSION['Msg'] = "Sponsor successfully added!";

						$id = $db->lastInsertid();

						logMessage("Sponsor added: id = " . $id, $_SESSION['userid'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR']);

						redirectToSelf($phpFileName);
					}
				} elseif (isset($_GET['action'])) {
					if ($_GET['action'] == "edit") {
						$sponsor = new Sponsors(intval($_GET['id']));
						$donorData = $sponsor->isDonor();

						echo "<div class='medium-6 large-4 columns medium-offset-3 large-offset-4'>";
						echo "<div class='panel'>";
						echo "<form action='$phpFileName' method='POST' enctype='multipart/form-data'>";
						echo "<h3>Sponsor</h3>";
						echo "<input type='hidden' name='action' value='update'>";
						echo "<input type='hidden' name='id' value='$sponsor->id'>";
						echo "<label>Name<input type='text' name='name' placeholder='$sponsor->name' value='$sponsor->name'></label>";
						echo "<label>Sponsorship Amount<br><small id=\"donationHelpText\">Please enter \" - 1\" for in kind donations</small><input type='number' name='amount' placeholder='$sponsor->amount' value='$sponsor->amount' aria-describedby=\"donationHelpText\"></label>";
						echo "<label>Donor?<br><small id=\"donorHelpText\">This is for people that personally donate</small><br><input type='checkbox' name='donor' value='true' $donorData aria-describedby=\"donorHelpText\"></label>";
						echo "<label>Link<input type='url' name='uri' value='$sponsor->uri'></label>";
						echo "<label>Logo<input type='file' name='logo'></label>";
						echo "<button class='no-bottom-margin' type='update' value='update'>Submit</button>";
						echo "</form>";
						echo "</div>";
						echo "</div>";
					} elseif ($_GET['action'] == "delete") {
						$sponsor = new Sponsors(intval($_GET['id']));
						unlink(realpath(UPLOAD_ROOT . $sponsor->img_uri));
						$sponsor->delete();

						$_SESSION['Msg'] = "Sponsor successfully deleted!";
						logMessage("Sponsor deleted: id = " . intval($_GET['id']), $_SESSION['userid'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR']);

						redirectToSelf($phpfilename . "?page=" . intval($_GET['page']));
					}
				} else {
					displayMsg();

					if (!isset($_GET['page'])) {
						$_GET['page'] = 1;
					}

					displayTable("sponsors", $LIMIT_PER_PAGE, $_GET['page'], $phpFileName, true);

					echo "<div class='row'>";
					echo "<div class='small-10 columns'>";

					$query = "SELECT COUNT(*) AS count FROM sponsors";

					$stmt = executeSQL($query, null);

					$count = $stmt->fetch();
					$count = intval($count['count']);

					pagination($count, $LIMIT_PER_PAGE, $_GET['page'], $phpFileName);

					echo "</div>";
					?>
					<div class='small-2 columns'>
						<a class='button round' data-reveal-id='sponsorModal'>Add</a>
					</div>
					<div id="sponsorModal" class="reveal-modal" data-reveal aria-labelledby="modalTitle"
					     aria-hidden="true" role="dialog">
						<form action='<?php echo $phpFileName ?>' method='POST' enctype='multipart/form-data'>
							<h3>Sponsor</h3>

							<input type='hidden' name='action' value='newrec'>
							<label>Name
								<input type='text' name='name'>
							</label>

							<label>Sponsorship Amount
								<br>
								<small id="donationHelpText">Please enter " - 1" for in kind donations</small>
								<input type='number' name='amount' aria-describedby="sponsorshipHelpText">
							</label>

							<label>Donor?
								<br>
								<small id="donorHelpText">This is for people that personally donate</small>
								<br>
								<input type='checkbox' name='donor' aria-describedby="donorHelpText">
							</label>

							<label>Link
								<input type='url' name='uri'>
							</label>

							<label> Logo
								<input type='file' name='logo'>
							</label>

							<button class='no-bottom-margin' type='newrec' value='newrec'>Submit</button>
						</form>

						<a class="close-reveal-modal" aria-label="Close">&#215;</a>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>

<?php printFooter(); ?>