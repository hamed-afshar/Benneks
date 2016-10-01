<?php
ini_set('display_errors', 1);
error_reporting(E_ALL|E_STRICT);
require '../src/benneks.php';
function checkUsername($keyValue) {
	$user = new user();
	$username = $keyValue;
	$query = "SELECT users.userName FROM users WHERE userName = '". $username ."'";
	$result = $user->existUser($query);
	if(mysqli_num_rows($result)) {
		$sback['icon'] = "remove-icon";
	}
	else {
		$sback['icon'] = "success-icon";
	}
	echo json_encode($sback, JSON_PRETTY_PRINT);
}

function checkEmail($keyValue)  {
	$user = new user();
	$email = $keyValue;
	$query = "SELECT users.userEmail FROM users WHERE userEmail = '". $email ."'";
	$result = $user->existUser($query);
	if(mysqli_num_rows($result)) {
		$sback['icon'] = "remove-icon";
	}
	else {
		$sback['icon'] = "success-icon";
	}
	echo json_encode($sback, JSON_PRETTY_PRINT);
}


// To determine URI entery value and choose the corresponding function.
parse_str($_SERVER["QUERY_STRING"]);
switch(strtolower($input)){
	case "username":
		checkUsername($value);
		break;
	case "email" :
		checkEmail($value);
		break;
}
?>