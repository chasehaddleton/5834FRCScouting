<?php
require_once("../components/Settings.php");

$setting = new Settings();

require_once($setting->getAppPath() . '/components/common.php');

if (!empty($_POST)) {
	// Validate that the user entered an email.
	if (empty($_POST['email'])) {
		if (!empty($_POST['name'])) {
			$submitted_fullname = htmlentities($_POST['name'], ENT_QUOTES, 'UTF-8');
		}

		$_SESSION['errorMsg'] = "Please enter your email address.";

		redirect($self);
	}

	// Validate that the user entered a name.
	if (empty($_POST['name'])) {
		if (!empty($_POST['email'])) {
			$submitted_email = htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8');
		}

		$_SESSION['errorMsg'] = "Please enter your name.";

		redirect($self);
	}

	// Validate that the user entered a password.
	if (empty($_POST['password'])) {
		if (!empty($_POST['email'])) {
			$submitted_email = htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8');
		}

		if (!empty($_POST['name'])) {
			$submitted_fullname = htmlentities($_POST['name'], ENT_QUOTES, 'UTF-8');
		}

		$_SESSION['errorMsg'] = "Please enter a password.";

		redirect($self);
	}

	// Validate that the user entered a team number.
	if (empty($_POST['teamNumber'])) {
		if (!empty($_POST['email'])) {
			$submitted_email = htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8');
		}

		if (!empty($_POST['name'])) {
			$submitted_fullname = htmlentities($_POST['name'], ENT_QUOTES, 'UTF-8');
		}

		$_SESSION['errorMsg'] = "Please enter a teamNumber.";

		redirect($self);
	}

	// Validate the user's email address.
	if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		if (!empty($_POST['email'])) {
			$submitted_email = htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8');
		}

		if (!empty($_POST['name'])) {
			$submitted_fullname = htmlentities($_POST['name'], ENT_QUOTES, 'UTF-8');
		}

		$_SESSION['errorMsg'] = "The email address " . $_POST['email'] . " is invalid.";

		redirect($self);
	}

	// Validate the user's email to guarantee it has not been used for registration before.
	$query = "SELECT 1 FROM Users WHERE email = :email";
	$query_params = array(':email' => $_POST['email']);

	$stmt = executeSQL($query, $query_params);

	$row = $stmt->fetch();

	// Return an error message if the email address is already in use.
	if ($row) {
		if (!empty($_POST['email'])) {
			$submitted_email = htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8');
		}

		if (!empty($_POST['name'])) {
			$submitted_fullname = htmlentities($_POST['name'], ENT_QUOTES, 'UTF-8');
		}

		$_SESSION['errorMsg'] = "This email address is already registered";

		redirect($self);
	}

	// Validate that their passwords match.
	if ($_POST['password'] != $_POST['re-password']) {
		if (!empty($_POST['email'])) {
			$submitted_email = htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8');
		}

		if (!empty($_POST['name'])) {
			$submitted_fullname = htmlentities($_POST['name'], ENT_QUOTES, 'UTF-8');
		}

		$_SESSION['errorMsg'] = "The entered passwords do not match.";

		redirect($self);
	}

	// Prepare the password.
	$pass = $_POST['password'];
	$hash = password_hash($pass, PASSWORD_DEFAULT);

	$query = "INSERT INTO Users (fullName, email, teamNumber, password, uniqId) VALUES (:name, :email, :teamNumber, :password, :uniqId)";
	$query_params = array(':name' => htmlspecialchars($_POST['name']), ':email' => htmlspecialchars($_POST['email']),
		':teamNumber' => intVal($_POST['teamNumber']), ':password' => $hash, ':uniqId' => uniqid('', true));
	
	executeSQL($query, $query_params);

	$id = $db->lastInsertId();

	logMessage("Account created", $id);

	redirect($setting->getAppURL() . "/login/");
}

printHead("Register");
printNav();
?>

<section id="main">
	<form action="<?php echo $setting->getAppURL() ?>/register/register.php" method="POST" data-abide>
		<div class="row">
			<div class="small-12 medium-6 large-4 columns medium-offset-3 large-offset-4">
				<h3>Register:</h3>
				<?php displayMsg(); ?>
			</div>
		</div>

		<div class="row">
			<div class="small-12 medium-6 large-4 columns medium-offset-3 large-offset-4">
				<label>Full Name
					<input type="text" name="name" placeholder="Full name" class="validate" required>
				</label>
			</div>
		</div>

		<div class="row">
			<div class="small-12 medium-6 large-4 columns medium-offset-3 large-offset-4">
				<label>Email
					<input type="email" name="email" placeholder="email" class="validate" required>
				</label>
			</div>
		</div>

		<div class="row">
			<div class="small-12 medium-6 large-4 columns medium-offset-3 large-offset-4">
				<label>Team Number
					<input type="number" name="teamNumber" placeholder="XXXX" class="validate" required min="1"
					       max="7000">
				</label>
			</div>
		</div>

		<div class="row">
			<div class="small-12 medium-6 large-4 columns medium-offset-3 large-offset-4">
				<label>Password
					<input type="password" name="password" placeholder="password" class="validate" required>
				</label>
			</div>
		</div>

		<div class="row">
			<div class="small-12 medium-6 large-4 columns medium-offset-3 large-offset-4">
				<label>Retype Password
					<input type="password" name="re-password" placeholder="Retype password" class="validate"
					       required>
				</label>
			</div>
		</div>

		<div class="row">
			<div class="small-12 medium-6 large-4 columns medium-offset-3 large-offset-4">
				<button class="button" type="submit" name="action">Submit</button>

				<div data-closable class="callout warning">
					Registered? Click <a href="<?php echo $setting->getAppURL() ?>/login/">here</a> to login
					<button class="close-button" aria-label="Dismiss alert" type="button" data-close=""><span
							aria-hidden="true">×</span></button>
				</div>
			</div>
		</div>
	</form>
</section>

<?php printFooter(); ?>
