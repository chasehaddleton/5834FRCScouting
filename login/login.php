<?php
require_once('../components/common.php');
require_once($setting->getAppPath() . '/components/Authentication/User.php');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	$user = new Authentication\User($_POST['email']);

	// Validate that they are a registered user.
	if ($user->exists()) {
		// Validate the password.
		if (password_verify($_POST['password'], $user->password)) {
			// Set required session variables
			$_SESSION['fullName'] = $user->fullName;
			$_SESSION['level'] = $user->level;
			$_SESSION['userId'] = $user->userId;
			$_SESSION['teamNumber'] = $user->teamNumber;
			$_SESSION['APIkey'] = $user->APIkey;

			// Redirect to appropriate page.
			logMessage("Successful login", $_SESSION['userId']);
			redirect($setting->getAppURL());
			die("Redirecting to: App");
		}
	}

	// Create error message that will be displayed to user.
	$_SESSION['errorMsg'] = "Email and/or password is incorrect or the account does not exist.";

	logMessage("Failed login", 0);

	$submitted_email = htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8');
}

printHead("Login");
printNav();
?>

<header class="hero heavy-accent">
	<form action="<?php echo $setting->getAppURL() ?>/login/login.php" method="POST">
		<div class="row">
			<div class="small-12 medium-6 large-4 columns medium-offset-3 large-offset-4">
				<h3>Scout Login:</h3>
				<?php displayMsg(); ?>
			</div>
		</div>

		<div class="row">
			<div class="small-12 medium-6 large-4 columns medium-offset-3 large-offset-4">
				<label>Email
					<input type="text" name="email" placeholder="Email" class="validate" required>
				</label>
			</div>
		</div>

		<div class="row">
			<div class="small-12 medium-6 large-4 columns medium-offset-3 large-offset-4">
				<label>Password
					<input type="password" name="password" placeholder="Password" class="validate" required>
				</label>
			</div>
		</div>

		<div class="row">
			<div class="small-12 medium-6 large-4 columns medium-offset-3 large-offset-4">
				<button class="button" type="submit" name="login">Submit</button>

				<div data-closable class="callout warning">
					Not registered? Click <a href='<?php echo $setting->getAppURL() ?>/register/'>here</a>
					<button class="close-button" aria-label="Dismiss alert" type="button" data-close=""><span
							aria-hidden="true">×</span></button>
				</div>
			</div>
		</div>
	</form>
</header>

<?php printFooter(); ?>
