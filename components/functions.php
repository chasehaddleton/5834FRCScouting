<?php
require_once("Settings.php");
$setting = new Settings();
require_once($setting->getAppPath() . "/components/common.php");
require_once($setting->getAppPath() . "/components/ScoutingAPI/Error.php");
require_once($setting->getAppPath() . "/components/Authentication/User.php");


function printHead($title) {
	global $setting;
	$title = ucwords($title . " - " . $setting->getApplicationName());
	$appURL = $setting->getAppURL();

	echo <<<EOF
<!doctype html>
<html class="no-js" lang="en">
	<head>
		<meta charset="utf-8"/>
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<title>$title</title>
		<link rel="stylesheet" href="$appURL/css/foundation.css"/>
		<link rel="stylesheet" href="$appURL/css/app.css"/>
		<link rel="stylesheet" href="$appURL/css/style.css"/>
	</head>
	<body>
EOF;
}

function printNav() {
	global $setting;
	$appURL = $setting->getAppURL();
	$appName = $setting->getApplicationName();

	$out = <<<EOF
		<div class="title-bar" data-responsive-toggle="site-menu" data-hide-for="medium">
			<button class="menu-icon" type="button" data-toggle></button>
			<div class="title-bar-title">FRC Stronghold Scouting App</div>
		</div>

		<div class="top-bar" id="site-menu">
			<div class="top-bar-left hide-for-small-only">
				<ul class="menu">
				    <li class="menu-text">$appName</li>
				</ul>
			</div>
EOF;

	if (isset($_SESSION['userId'])) {
		$out .= <<<EOF
		<div class="top-bar-right">
		    <ul class="menu">
		        <li><a href="$appURL">Home</a></li>
		        <li><a href="$appURL/scout/">Scout</a></li>
		        <li><a href="$appURL/team/">Your Team</a></li>
		        <li><a href="#">Analyze</a></li>
				<li><a href="$appURL/login/logout.php">Logout</a></li>

EOF;
	}

	$out .= <<<EOF
			    </ul>
			 </div>
		</div>
EOF;

	echo $out;
}

function printFooter() {
	$date = date("Y");
	global $setting;
	$appURL = $setting->getAppURL();

	$out = <<<EOF
		<footer>
			<div class="row">
				<div class="small-12 columns">
					<p>
						Copyright $date Chase Haddleton
					</p>
				</div>
			</div>
		</footer>

		<script src="$appURL/js/vendor/jquery.min.js"></script>
		<script src="$appURL/js/vendor/what-input.min.js"></script>
		<script src="$appURL/js/foundation.min.js"></script>
		<script src="$appURL/js/app.js"></script>
	</body>
</html>
EOF;

	echo $out;
}

/**
 * Verify a user's permission level.
 *
 * @param $userLevel int User's user level
 * @param $requiredLevel int Required level to view page
 */
function verifyPermission($userLevel, $requiredLevel) {
	global $setting;

	if (!isset($userLevel) || $userLevel < $requiredLevel) {
		$_SESSION['errorMsg'] = "You are not authorized to be here, please sign in with an account that is authorized to view the page.";
		redirect($setting->getAppURL() . "/login/");
		die("Unauthorized");
	}
}

/**
 * Verify that the user's provided API key is valid.
 *
 * @param $userKey string Key provided from the user.
 * @param $userId int User ID number
 * @param $teamNumber int User's team number
 * @return bool whether or not the API key is valid.
 */
function verifyAPIKey($userKey, $userId, $teamNumber) {
	$genKey = new Authentication\APIKey($userId, $teamNumber);
	return hash_equals($genKey->getKey(), $userKey);
}

/**
 * Verify that the user is real and that they should have API access.
 *
 * @param $keyArr array GET or POST array containing the keys.
 */
function validateAPIAccess($keyArr) {
	$requiredGETKeys = array('APIKey', 'userId', 'scoutTeamNumber');
	if (count(array_diff($requiredGETKeys, array_keys($keyArr))) != 0) {
		errorResponse("Error, apiKey and/or related data missing from request. Must provide APIKey, userId and teamNumber", 2, 401);
	}

	if (!verifyAPIKey($keyArr['APIKey'], $keyArr['userId'], $keyArr['scoutTeamNumber'])) {
		errorResponse("API key is not valid", 12, 403);
	}
}

/**
 * Redirect the user to a specified URL.
 *
 * @param $url string URL to redirect the user to.
 * @param int $statusCode
 */
function redirect($url, $statusCode = 303) {
	header('Location: ' . $url, true, $statusCode);
	die();
}

/**
 * This function logs a message to the log database.
 *
 * @param string $message The message that will be added to the log
 * @param int $userId ID that will be recorded as causing the message to be logged.
 * @param int $severity How severe this error is
 */
function logMessage($message, $userId, $severity = 0) {
	$query = "INSERT INTO Logs (logTime, message, userId, ip, severity) VALUES (:logTime, :message, :userId, :ip, :severity)";
	$query_params = array(':logTime' => date('Y-m-d G:i:s'), 'message' => htmlspecialchars($message), ':userId' => $userId, ':ip' => $_SERVER['REMOTE_ADDR'], ':severity' => $severity);

	executeSQL($query, $query_params);
}


/**
 * Executes an SQL command (with optional parameters) and returns the result. Can handle the use of table prefixes
 * as set in the Settings object for the application.
 *
 * @param string $query The SQL query that you would like to run.
 * @param array $query_params Parameters for the SQL query.
 * @return PDOStatement
 */
function executeSQL($query, $query_params) {
	global $setting;

	if (strpos("FROM information_schema", $query) == false) {
		preg_match("(FROM ([A-z]+))", $query, $matches, PREG_OFFSET_CAPTURE);
		if (empty($matches)) {
			preg_match("(INTO ([A-z]+))", $query, $matches, PREG_OFFSET_CAPTURE);
		}
		if (empty($matches)) {
			preg_match("(UPDATE ([A-z]+))", $query, $matches, PREG_OFFSET_CAPTURE);
		}
		$query = substr_replace($query, $setting->getDbPrefix(), $matches[1][1], 0);
	}

	$stmt = null;

	try {
		global $db;
		$stmt = $db->prepare($query);
		$stmt->execute($query_params);
	} catch (PDOException $ex) {
		$_SESSION['errorMsg'] = "SQL Error, please try again or inform the webmaster.";

		error_log("SQL error. Query: " . $query . "<br> Stack trace: " . $ex->getMessage());

		die("SQL error.");
	}

	return $stmt;
}

/**
 * Executes an SQL command through the executeSQL method and returns the first row of results.
 *
 * @param $query string The SQL query that you would like to run.
 * @param $query_params array Parameters for the SQL query.
 * @return mixed
 */
function executeSQLSingleRow($query, $query_params) {
	$stmt = executeSQL($query, $query_params);

	return $stmt->fetch();
}

/**
 * Create pagination for a page.
 *
 * @param $count int Number of rows in the table.
 * @param $rowsPerPage int Number of rows you would like displayed per page.
 * @param $page int The current page number.
 * @param $phpfilename string The name of the current webpage.
 */
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
 */
function displayMsg() {
	if (isset($_SESSION['Msg'])) {
		echo '<div data-closable class="alert callout">';
		echo $_SESSION['Msg'];
		echo "<button class=\"close-button\" aria-label=\"Dismiss alert\" type=\"button\" data-close><span aria-hidden=\"true\">&times;</span></button>";
		echo '</div>';
		unset($_SESSION['Msg']);
	}

	if (isset($_SESSION['errorMsg'])) {
		echo '<div data-closable class="callout alert">';
		echo $_SESSION['errorMsg'];
		echo "<button class=\"close-button\" aria-label=\"Dismiss alert\" type=\"button\" data-close><span aria-hidden=\"true\">&times;</span></button>";
		echo '</div>';
		unset($_SESSION['errorMsg']);
	}
}

/**
 * Display's a given MySQL table to the user.
 *
 * @param $tableName string Name of the table you would like to display.
 * @param $limitPerPage int Number of rows to show per page.
 * @param $pageNumber int Which page of results to display.
 * @param $phpFileName string Name of the (current file) to direct things to.
 * @param $editDelete boolean Whether or not you want "Edit" and "Delete" buttons to appear.
 */
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
			if (strtolower($col) == "password") {
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

/**
 * Return an error to the user in JSON format.
 *
 * @param $errorMsg string Error message to include in JSON object.
 * @param $errorCode int Error code to send back in the JSON object.
 * @param $httpStatus int Optional HTTP status code
 */
function errorResponse($errorMsg, $errorCode, $httpStatus = 400) {
	$httpHeader = "HTTP/1.1";

	switch ($httpStatus) {
		case 400:
			$httpHeader .= " 400 Bad Request";
			break;
		case 401:
			$httpHeader .= " 401 Unauthorized";
			break;
		case 403:
			$httpHeader .= " 403 Forbidden";
			break;
		case 405:
			$httpHeader .= " 405 Method Not Allowed";
			break;
	}

	header($httpHeader);
	echo json_encode(new ScoutingAPI\Error($errorMsg, $errorCode));
	die();
}

function formatPhoneNumber($phoneNumber) {
	$phoneNumber = preg_replace("/[^0-9]/", "", $phoneNumber);
	$length = strlen($phoneNumber);

	switch ($length) {
		case 7:
			return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phoneNumber);
			break;
		case 10:
			return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phoneNumber);
			break;
		case 11:
			return preg_replace("/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/", "+$1 ($2) $3-$4", $phoneNumber);
			break;
		default:
			return $phoneNumber;
			break;
	}
}