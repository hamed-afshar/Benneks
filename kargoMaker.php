<?php

/*
 * This script is responsible for making a kargo list for sending items to Iran.
 */
ob_start();
session_start();
require 'src/benneks.php';
// if Admin session is not set this will get access denied msg
if ($_SESSION['userAccess'] !== '2') {
    echo "اجازه دسترسی ندارید";
    exit();
}
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
$user = new user();
date_default_timezone_set("Asia/Tehran");
ini_set('memory_limit', '512M');
// Query to extract the last kargo code from database
$lastKargoQuery = "select max(cargoName) from benneks.shipment where cargoName REGEXP '^[0-9]{3}+$';";
if (!$user->executeQuery($lastKargoQuery)) {
    echo mysqli_error($user->conn);
}

$lastKargoQueryResult = $user->executeQuery($lastKargoQuery);
$row = mysqli_fetch_array($lastKargoQueryResult);
echo $row[0];
?>


