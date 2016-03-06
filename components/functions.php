<?php
function printHead($title) {
	$title = ucwords($title . " - " . TEAM_NAME);
	echo <<<EOF
<!DOCTYPE html>
<html>
	<head>
		<link rel="apple-touch-icon" sizes="57x57" href="/css/icon/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="/css/icon/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="/css/icon/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="/css/icon/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="/css/icon/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="/css/icon/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="/css/icon/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="/css/icon/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="/css/icon/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192" href="/css/icon/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/css/icon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="/css/icon/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/css/icon/favicon-16x16.png">
		<link rel="manifest" href="/css/icon/manifest.json">
		<meta name="msapplication-TileColor" content="#333">
		<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
		<meta name="theme-color" content="#333">
		<meta charset="utf-8">
		<meta name="google-site-verification" content="94aiyTY6f0GBzWgLRRiTv5SuAQCxkJljcQKQ688FV7E" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<meta property="og:title" content="R3P2"/>
		<meta property="og:type" content="website"/>
		<meta property="og:image" content="https://riverdalerobotics.com/images/logo.svg"/>
		<meta name="description" content="R3P2 (FRC team #5834) is an FRC robotics team based out of Riverdale Collegiate, located in Toronto, Ontario.">

		<script src="https://use.typekit.net/vox0oyb.js"></script>
		<script>try{Typekit.load({ async: true });}catch(e){}</script>
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<link rel="stylesheet" href="/css/normalize.css">
		<link rel="stylesheet" href="/css/foundation.css">
		<link rel="stylesheet" href="/css/style.css">
		<link rel="stylesheet" href="/css/alert.css">
		<link rel="stylesheet" type="text/css" href="/css/fluidbox.css">
		<script src="/js/vendor/modernizr.js"></script>
		<script type="text/javascript" src="/js/google-analytics.js"></script>

		<title>$title</title>
	</head>
	<body>
EOF;
}

function printNav() {
	$out = <<<EOF
		<div class="fixed">
			<nav class="top-bar" data-topbar role="navigation">
				<ul class="title-area">
					<div class="logo">
                        <a href="/">
                            <img src="/images/h51/logo.png">
                        </a>
                    </div>
					<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
				</ul>

				<section class="top-bar-section">
					<ul class="right">
						<li class="has-dropdown">
							<a href="#">Sponsors</a>
							<ul class="dropdown">
								<li><a href="/sponsor/">Our Sponsors</a></li>
								<li><a href="/sponsor/donors">Our Donors</a></li>
							</ul>
						</li>
						<li><a href="/about/">Who We Are</a></li>
						<li><a href="/studentinfo/">Student Info</a></li>
						<li><a href="/gallery/">Gallery</a></li>
EOF;

	if (isset($_SESSION['level'])) {
		if ($_SESSION['level'] > 0) {
			$out .= '<li><a href="/admin">Control Panel</a></li>';
		}
		if (isset($_SESSION['userid'])) {
			$name = $_SESSION['fullname'];
			$out .= <<<EOF
						<li class="has-dropdown">
							<a href='#!'>$name</a>
							<ul class="dropdown">
								<li><a href="/user/">Profile</a></li>
								<li><a href="/login/logout.php">Logout</a></li>
							</ul>
						</li>
EOF;
		}
	}
	$out .= <<<EOF
					</ul>
				</section>
			</nav>
		</div>
EOF;
	echo $out;
}

function printFooter() {
	$date = date("Y");

	$out = <<<EOF
		<footer>
			<section id="footer-content">
				<div class="row">
					<div class="small-12 medium-6 large-8 columns">
						<p>FRC Team #5834</p>
					</div>

					<div id="address" class="small-12 medium-6 large-4 columns">
						<p>
							<span itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">
								Riverdale Collegiate Institute <br>
								<span itemprop="streetAddress">1094 Gerrard St E</span>, <br>
								<span itemprop="addressLocality">Toronto</span>, <span itemprop="addressRegion">ON</span> <br>
								<span itemprop="postalCode">M4M 2A1</span> <br>
								<span itemprop="addressCountry">CA</span> <br>
								<a href="tel:4163939820">(416) 393-9820</a>
							</span>
						</p>
					</div>
				</div>
			</section>

			<section id="copyright">
				<div class="row">
					<div class="small-8 columns">
						&copy; $date Chase Haddleton
					</div>
EOF;
	if (!isset($_SESSION['userid'])) {
		$out .= <<<EOF
					<div class="small-4 columns text-right" >
						<a href = "/login/" alt = "Login" > Login</a >
					</div >
EOF;
	}
	$out .= <<<EOF
				</div>
			</section>
		</footer>
		<script type="text/javascript" src="/js/vendor/jquery.js"></script>
		<script type="text/javascript" src="/js/foundation.min.js"></script>
		<script type="text/javascript" src="/js/foundation/foundation.interchange.js"></script>
		<script type="text/javascript" src="/js/animation.js"></script>
		<script type="text/javascript" src="/js/masonry-grid.js"></script>
		<script type="text/javascript" src="/js/page-load.js"></script>

	</body>
</html>

EOF;

	echo $out;
}

function verifyPermission($userLevel, $requiredLevel) {
	if ($userLevel < $requiredLevel) {
		$_SESSION['errorMsg'] = "You are not authorized to be here, please sign in.";
		header("Location: /login/");
		die("Unauthorized");
	}
}

/**
 * This function logs a message to the log database.
 *
 * @param string $message The message that will be added to the log
 * @param string $userid ID that will be recorded as causing the message to be logged.
 * @param string $ip IP address of the user that caused the log.
 * @param string $xffip X-FORWARDED-FOR IP address if set, otherwise is the same as $ip.
 * @return void
 */
function logMessage($message, $userid, $ip, $xffip) {
	require($_SERVER['DOCUMENT_ROOT'] . "/components/common.php");

	$query = "INSERT INTO logs (logTime, message, userid, ip, xffip) VALUES (:logTime, :message, :userid, :ip, :xffip)";
	$query_params = array(':logTime' => date('Y-m-d G:i:s'), 'message' => htmlspecialchars($message), ':userid' => $userid, ':ip' => $ip, ':xffip' => $xffip);

	executeSQL($query, $query_params);
}

/**
 * This function executes an SQL command with parameters and returns the result.
 *
 * @param string $query The SQL query that you would like to run.
 * @param array $query_params Parameters for the SQL query.
 * @return PDOStatement
 */
function executeSQL($query, $query_params) {
	$stmt = null;

	try {
		global $db;
		$stmt = $db->prepare($query);
		$stmt->execute($query_params);
	} catch (PDOException $ex) {
		logMessage("Failed to run query: " . $ex->getMessage() . ". Query: " . $query, $_SESSION['userid'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR']);

		$_SESSION['errorMsg'] = "SQL Error, please try again or inform the webmaster.";
	}

	return $stmt;
}

function pagination($count, $rowsPerPage, $page, $phpfilename) {
	$maxPages = ceil($count / $rowsPerPage);
	$page = intval($page);

	echo '<ul class="pagination">';

	if ($page != 1) {
		// Generate previous arrow button.
		$listItem = "<li class='arrow'><a href='$phpfilename?page=1'>&laquo;</a></li>";
		$listItem .= "<li class='arrow";
		if (1 == $page) {
			$listItem .= " unavailable";
		}
		$listItem .= "'><a href='$phpfilename?page=" . ($page - 1) . "'>Prev</a></li>";
		echo $listItem;
	}

	// Calculate start and end page number.
	if ($page > 3) {
		$start = $page - 2;
	} else {
		$start = 1;
	}
	if ($start + 4 > $maxPages) {
		$end = $maxPages;
	} else {
		$end = ($start + 4);
	}

	// Generate pagination buttons.
	for ($i = $start; $i <= $end; $i++) {
		$listItem = "<li";
		if ($i == $page) {
			$listItem .= " class='current' ";
		}
		$listItem .= "><a href='$phpfilename?page=$i'>$i</a></li>";

		echo $listItem;
	}

	if ($page != $maxPages && $maxPages != 0) {
		// Generate next button and last arrow button.
		$listItem = "<li class='arrow";
		if ($maxPages == $page) {
			$listItem .= " unavailable";
		}
		$listItem .= "'><a href='$phpfilename?page=" . ($page + 1);
		$listItem .= "'>Next</a></li>";

		$listItem .= "<li class='arrow'><a href='$phpfilename?page=$maxPages'>&raquo;</a></li>";
		echo $listItem;
	}

	echo '</ul>';
}

/**
 * This function echos the content of the message variables to the forum in alert boxes.
 *
 * @return void
 */
function displayMsg() {
	if (isset($_SESSION['Msg'])) {
		echo '<div data-alert class="alert-box info">';
		echo $_SESSION['Msg'];
		echo '<a href="#" class="close">&times;</a>';
		echo '</div>';

		unset($_SESSION['Msg']);
	}

	if (isset($_SESSION['errorMsg'])) {
		echo '<div data-alert class="alert-box alert">';
		echo $_SESSION['errorMsg'];
		echo '<a href="#" class="close">&times;</a>';
		echo '</div>';
		unset($_SESSION['errorMsg']);
	}
}

function displayTable($tableName, $limitPerPage, $pageNumber, $phpFileName, $editDelete) {

	/*
	 * Generate and display table header
	 */
	echo "<h2>$tableName</h2>";
	echo "<table class='highlight'>";
	echo "<thead>";

	$query = "SELECT column_name FROM information_schema.columns WHERE table_name = :tableName";
	$query_params = array(':tableName' => $tableName);

	$stmt = executeSQL($query, $query_params);
	$results = $stmt->fetchAll();

	foreach ($results as $row) {
		echo "<th>" . ucwords($row['column_name']) . "</th>";
	}

	if ($editDelete) {
		echo "<th>Edit</th>";
		echo "<th>Delete</th>";
	}

	echo "</thead>";
	echo "<tbody>";

	/*
	 * Generate and display the table data
	 */

	if (!$limitPerPage) $limitPerPage = 20;

	$query = "SELECT * FROM $tableName LIMIT $limitPerPage";

	if ($pageNumber && intval($pageNumber) > 1) {
		$query .= " OFFSET " . $limitPerPage * (intval($pageNumber) - 1);;
	}

	$stmt = executeSQL($query, null);
	$results = $stmt->fetchAll();

	foreach ($results as $row) {
		$id = $row['id'];

		echo "<tr>";

		foreach ($row as $col => $data) {
			if ($col == "password") {
				$data = "<i>Confidential<i>";
			}
			echo "<td>$data</td>";
		}

		if ($editDelete) {
			echo "<td><a class='button no-bottom-margin' href='$phpFileName?action=edit&id=$id&page=$pageNumber'>Edit</a></td>";
			echo "<td><a class='button no-bottom-margin' href='$phpFileName?action=delete&id=$id&page=$pageNumber'>Delete</a></td>";
		}

		echo "</tr>";
	}

	echo "</tbody>";
	echo "</table>";
	echo "</div>";
	echo "</div>";
}