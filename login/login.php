<?php
require($_SERVER['DOCUMENT_ROOT'] . '/components/common.php');
require($_SERVER['DOCUMENT_ROOT'] . '/components/Users.php');

if (!empty($_POST)) {
	$user = new Users($_POST['email']);

	// Validate that they are a registered user.
	if ($user->id) {
		// Validate the password.
		if (password_verify($_POST['password'], $user->password)) {
			$login_ok = true;
		}
	} else {
		$login_ok = false;
	}

	if ($login_ok) {
		// Set required session variables
		$_SESSION['fullname'] = $user->fullname;
		$_SESSION['level'] = $user->level;
		$_SESSION['userid'] = $user->id;

		// Redirect to appropriate page.
		if ($_SESSION['level'] > 0) {
			header("Location: /admin/pages.php");

			logMessage("Successful login", $_SESSION['userid'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR']);

			die("Redirecting to: /admin/pages.php");
		} else {
			header("Location: /");

			logMessage("Successful login", $_SESSION['userid'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR']);

			die("Redirecting to: /");
		}
	} else {
		// Create error message that will be displayed to user.
		$_SESSION['errorMsg'] = "Email and/or password is incorrect or the account does not exist.";

		logMessage("Failed login", 0, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR']);

		$submitted_email = htmlentities($_POST['Email'], ENT_QUOTES, 'UTF-8');
	}
}
?>

<?php
printHead("Login");
printNav();
?>

<section id="main">
	<div class="row">
		<div class="small-12 medium-6 large-4 columns medium-offset-3 large-offset-4">
			<div class="panel">

				<?php displayMsg(); ?>

				<form action="login.php" method="POST">
					<h4>Executive Login:</h4>
					<input type="text" name="email" placeholder="Email"
					       value="<?php if (isset($submitted_email)) echo $submitted_email; ?>" class="validate"
					       required>

					<input type="password" name="password" placeholder="Password" class="validate" required>

					<button class="expand" type="submit" name="login">Submit</button>
				</form>
				<div data-alert class="alert-box info">
					Not registered? Click <a href="/register/">here</a>
					<a href="#" class="close">&times;</a>
				</div>
			</div>
		</div>
	</div>
</section>

<?php printFooter(); ?>
