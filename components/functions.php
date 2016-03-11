<?php
require_once("Settings.php");
$setting = new Settings();
require_once($setting->getAppPath() . "/components/common.php");
require_once($setting->getAppPath() . "/components/Error.php");

function printHead($title) {
	$title = ucwords($title . " - 5834 Scouting");
	$setting = new Settings();
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
	</head>
	<body>
EOF;
}

function printNav() {
	$setting = new Settings();
	$appURL = $setting->getAppURL();

	$out = <<<EOF
		<div class="top-bar">
			<div class="top-bar-left">
				<ul class="dropdown menu" data-dropdown-menu>
					<li class="menu-text">FRC Stronghold Scouting App</li>
		        </ul>
		    </div>
		    <div class="top-bar-right">
			    <ul class="menu">
			        <li><a href="#">Scout</a></li>
			        <li><a href="#">Analyze</a></li>
			        <li><a href="$appURL/login/">Login</a></li>
			    </ul>
			 </div>
		</div>
EOF;

	echo $out;
}

function printFooter() {
	$date = date("Y");
	$setting = new Settings();
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

function verifyPermission($userLevel, $requiredLevel) {
	if ($userLevel < $requiredLevel) {
		$_SESSION['errorMsg'] = "You are not authorized to be here, please sign in.";
		header("Location: /login/");
		die("Unauthorized");
	}
}

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
 * @return void
 */
function logMessage($message, $userId, $severity = 0) {
	$setting = new Settings();

	$query = "INSERT INTO " . $setting->getDbPrefix() . "Logs (logTime, message, userId, ip, severity) VALUES (:logTime, :message, :userId, :ip, :severity)";
	$query_params = array(':logTime' => date('Y-m-d G:i:s'), 'message' => htmlspecialchars($message), ':userId' => $userId, ':ip' => $_SERVER['REMOTE_ADDR'], ':severity' => $severity);

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
		$_SESSION['errorMsg'] = "SQL Error, please try again or inform the webmaster.";

		error_log("SQL error. Query: " . $query . "<br> Stack trace: " . $ex->getMessage());

		die("SQL error.");
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

function errorResponse($errorMsg, $errorCode) {
	echo json_encode(new ScoutingAPI\Error($errorMsg, $errorCode));
	die();
}