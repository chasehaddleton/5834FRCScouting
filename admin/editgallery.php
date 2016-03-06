<?php
require($_SERVER['DOCUMENT_ROOT'] . '/components/common.php');

verifyPermission($_SESSION['level'], 1);

$phpfilename = basename(__FILE__);
$LIMIT_PER_PAGE = 20;

printHead("Edit Galleries");
printNav();
?>

<section id="main">
	<div class="row">
		<div class="small-12 columns">
			<?php
			if (isset($_POST['action'])) {
				if ($_POST['action'] == "update") {

					$query = "UPDATE gallery SET event = :event, year = :year WHERE id = :id";

					$query_params = array(':id' => $_POST['id'], ':event' => $_POST['event'], ':year' => $_POST['year']);

					$stmt = executeSQL($query, $query_params);

					$_SESSION['Msg'] = "Event successfully updated!";

					logMessage("Event updated: id = " . $_POST['id'], $_SESSION['userid'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR']);

					redirectToSelf($phpfilename);

				} elseif ($_POST['action'] == "newrec") {
					$query = "INSERT INTO gallery (event, year) VALUES (:event, :year)";

					$query_params = array(':event' => $_POST['event'], ':year' => $_POST['year']);

					$stmt = executeSQL($query, $query_params);

					$_SESSION['Msg'] = "Event successfully added!";

					$id = $db->lastInsertid();

					logMessage("Event added: id = " . $id, $_SESSION['userid'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR']);

					redirectToSelf($phpfilename);
				}
			} elseif (isset($_GET['action'])) {
				if ($_GET['action'] == "edit") {
					$query = "SELECT * FROM gallery WHERE id = :id";
					$query_params = array(':id' => $_GET['id']);

					$stmt = executeSQL($query, $query_params);

					$row = $stmt->fetch();
					$id = $row["id"];
					$event = $row["event"];
					$year = $row["year"];

					echo "<div class='medium-6 large-4 columns medium-offset-3 large-offset-4'>";
					echo "<div class='panel'>";
					echo "<form action='$phpfilename' method='POST'>";
					echo "<input type='hidden' name='action' value='update'>";
					echo "<input type='hidden' name='id' value='$id'>";
					echo "<input type='text' name='event' placeholder='Event' value='$event'>";
					echo "<input type='number' name='year' placeholder='Year' value='$year'>";
					echo "<button class='no-bottom-margin' type='newrec' value='newrec'>Submit</button>";
					echo "</form>";
					echo "</div>";
					echo "</div>";

				} elseif ($_GET['action'] == "Delete") {

					$query = "DELETE FROM gallery WHERE id = :id";
					$query_params = array(':id' => $_GET['id']);

					$stmt = executeSQL($query, $query_params);

					$_SESSION['Msg'] = "Gallery successfully deleted!";

					logMessage("Gallery deleted: id = " . $_GET['id'], $_SESSION['userid'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR']);

					redirectToSelf($phpfilename . "?page=" . intval($_GET['page']));
				}
			} else {
				displayMsg();

				$query = "SELECT * FROM gallery LIMIT " . $LIMIT_PER_PAGE;

				if (!isset($_GET['page'])) {
					$_GET['page'] = 1;
				}

				displayTable("gallery", $LIMIT_PER_PAGE, $_GET['page'], $phpfilename, true);

				echo "<div class='row'>";
				echo "<div class='small-10 columns'>";

				$query = "SELECT COUNT(*) FROM gallery";

				$stmt = executeSQL($query, null);

				$count = $stmt->fetch();
				$count = intval($count['count']);

				pagination($count, $LIMIT_PER_PAGE, $_GET['page'], $phpfilename);

				echo "</div>";
				?>
				<div class='small-2 columns'>
					<a class='button round' data-reveal-id='galleryModal'>Add</a>
				</div>

				<div id="galleryModal" class="reveal-modal" data-reveal aria-labelledby="modalTitle"
				     aria-hidden="true"
				     role="dialog">

					<h5>Add Gallery</h5>

					<form action='<?php echo $phpfilename ?>' method='POST'>
						<input type='hidden' name='action' value='newrec'>
						<input type='text' name='event' placeholder='Event Name'>
						<input type='number' name='year' placeholder='Year'>
						<button class='no-bottom-margin' type='newrec' value='newrec'>Submit</button>
					</form>

					<a class="close-reveal-modal" aria-label="Close">&#215;</a>
				</div>
			<?php } ?>
		</div>
	</div>
</section>

<?php printFooter(); ?>
