<?php
require($_SERVER['DOCUMENT_ROOT'] . "/components/common.php");

if (!empty($_POST)) {

	// Validate that the user entered an email.
	if (empty($_POST['email'])) {
		if (!empty($_POST['name'])) {
			$submitted_fullname = htmlentities($_POST['name'], ENT_QUOTES, 'UTF-8');
		}

		$_SESSION['errorMsg'] = "Please enter your email address.";

		redirectToSelf($phpfilename);
	}

	// Validate that the user entered a name.
	if (empty($_POST['name'])) {
		if (!empty($_POST['email'])) {
			$submitted_email = htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8');
		}

		$_SESSION['errorMsg'] = "Please enter your name.";

		redirectToSelf($phpfilename);
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

		redirectToSelf($phpfilename);
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

		redirectToSelf($phpfilename);
	}

	// Validate the user's email to guarantee it has not been used for registration before.
	$query = "SELECT 1 FROM users WHERE email = :email";

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

		redirectToSelf($phpfilename);
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

		redirectToSelf($phpfilename);
	}

	// Prepare the password.
	$pass = $_POST['password'];
	$hash = password_hash($pass, PASSWORD_DEFAULT);

	$query = "INSERT INTO users (fullname, email, password) VALUES (:name, :email, :password)";
	$query_params = array(':name' => htmlspecialchars($_POST['name']), ':email' => htmlspecialchars($_POST['email']), ':password' => $hash);
	
	try {
		// Execute the query to create the user
		$stmt = $db->prepare($query);
		$result = $stmt->execute($query_params);
	} catch (PDOException $ex) {
		logMessage("Failed to run query: " . $ex->getMessage() . ". Query: " . $query, 0, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR']);

		die("Failed to run query");
	}

	$id = $db->lastInsertId();

	// This redirects the user back to the login page after they register
	header("Location: /login/");

	logMessage("Account created", $id, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR']);

	die("Redirecting to login.php");
}
?>
<?php
printHead("Register");
printNav();
?>

<section id="main">
	<div class="row">
		<div class="small-12 medium-6 large-4 columns medium-offset-3 large-offset-4">
			<div class="panel">

				<?php displayMsg(); ?>

				<form action="register.php" method="POST" data-abide>
					<h5>Register:</h5>

					<input type="text" name="name" placeholder="Full name"
					       value="<?php print $submitted_fullname;
					       unset($submitted_fullname) ?>" class="validate" required>

					<input type="email" name="email" placeholder="email" value="<?php print $submitted_email;
					unset($submitted_email) ?>" class="validate" required>

					<input type="password" name="password" placeholder="password" class="validate" required>

					<input type="password" name="re-password" placeholder="Retype password" class="validate"
					       required>

					<button class="expand" type="submit" name="action">Submit</button>

				</form>
				<div data-alert class="alert-box info">
					Registered? Click <a href="/login">here</a> to login
					<a href="#" class="close">&times;</a>
				</div>
			</div>
		</div>
	</div>
</section>

<?php printFooter(); ?>
