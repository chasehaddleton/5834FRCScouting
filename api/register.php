<?php
require_once('../components/common.php');

// Check all page conditions.

if ($_SERVER['REQUEST_METHOD'] != "POST") {
	errorResponse("Error, must POST to this page.", 1);
}

$requiredPOSTKeys = array('email', 'password', 'name', 'teamNumber');

if (count(array_diff($requiredPOSTKeys, array_keys($_POST))) != 0) {
	errorResponse("Error, must POST name, email, team number, and password", 10);
}

// Do the stuff for this page.

$query = "SELECT 1 FROM Users WHERE email = :email";
$query_params = array(':email' => $_POST['email']);

$row = executeSQLSingleRow($query, $query_params);

// Return an error message if the email address is already in use.
if ($row) {
	errorResponse("Error, This email address is already registered", 11);
}

// Prepare the password.
$hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

$query = "INSERT INTO Users (fullName, email, teamNumber, password, uniqId, phoneNumber) VALUES (:name, :email, :teamNumber, :password, :uniqId, :phoneNumber)";
$query_params = array(':name' => htmlspecialchars($_POST['name']), ':email' => htmlspecialchars($_POST['email']),
	':teamNumber' => intVal($_POST['teamNumber']), ':password' => $hash, ':uniqId' => uniqid('', true),
	':phoneNumber' => htmlspecialchars($_POST['phoneNumber']));

executeSQL($query, $query_params);

$id = $db->lastInsertId();
logMessage("Account created through API", $id);

$out = array("Success");
echo json_encode($out);