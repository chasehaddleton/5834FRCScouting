<?php
require($_SERVER['DOCUMENT_ROOT'] . '/components/common.php');

verifyPermission($_SESSION['level'], 1);

$phpFileName = basename(__FILE__);
$LIMIT_PER_PAGE = 10;

printHead("Mail Users");
printNav();
?>

<section id="main">
	<div class="row">
		<div class="small-12 column">
			<?php displayMsg(); ?>

			<h2>Mail Jobs</h2>
			<table class='highlight'>
				<thead>
				<th>ID</th>
				<th>Date</th>
				<th>Team</th>
				<th>Subject</th>
				<th>Body</th>
				<th>Created By</th>
				</thead>
				<tbody>

				<?php
				$query = "SELECT mailjobs.id, mailjobs.mailTime, mailjobs.team, mailjobs.subject, mailjobs.body, users.fullname FROM mailjobs
                        LEFT JOIN users 
                        ON users.id = mailjobs.createdby 
                        ORDER BY id DESC
                        LIMIT " . $LIMIT_PER_PAGE;

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
				?>
				</tbody>
			</table>
		</div>
	</div>

	<div class='row'>
		<div class='small-10 columns'>
			<?php
			$query = "SELECT COUNT(*) AS count FROM mailjobs";

			$stmt = executeSQL($query, null);

			$count = $stmt->fetch();
			$count = intval($count['count']);

			pagination($count, $LIMIT_PER_PAGE, $_GET['page'], $phpFileName);
			?>
		</div>
		<div class='small-2 columns'>
			<a class='button round' data-reveal-id='emailModal'>Add</a>
		</div>
	</div>
	<div id="emailModal" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true"
	     role="dialog">

		<h5>New Email:</h5>

		<?php
		$_SESSION['Msg'] = "Currently only 'All' and 'Executive' works";
		displayMsg();
		?>

		<form action='sendmail.php' method='POST'>
			<input type='hidden' name='action' value='newemail'>

			<select name='team'>
				<option disabled="disabled" selected="selected">Email Team:</option>
				<option value='all'>All</option>
				<option value='business'>Business</option>
				<option value='construction'>Construction</option>
				<option value='programming'>Programming</option>
				<option value='executive'>Executive</option>
			</select>

			<input type="text" name="subject" placeholder="Subject" class="validate" required>
						<textarea placeholder="Email Body" id="emailbody" name="emailbody" class="validate"
						          required></textarea>
			<button type='submit' value='newemail' class="text-center no-bottom-margin">Submit</button>
		</form>

		<a class="close-reveal-modal" aria-label="Close">&#215;</a>
	</div>

</section>

<?php printFooter(); ?>
