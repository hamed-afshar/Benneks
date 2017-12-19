<?php

/*
 * php script to add customer and member details into the system
 */
ob_start();
session_start();
require 'src/benneks.php';
// if session is not set this will redirect to login page
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$user = new user();
date_default_timezone_set("Asia/Tehran");

//set member information
$customerName = $_GET['customerName'];
$customerTel = $_GET['customerTel'];
$customerTelegramID = $_GET['customerTelegramID'];
$customerCode = $_GET['customerTel'];;
//on order submition we need to insert customer information details in to the members table but first need to check if this user has already exist
//$checkQuery = "SELECT customerCode from benneks.members WHERE members.customerCode = '$customerCode'";
//$checkQueryResult = $user->executeQuery($checkQuery);
//$row = mysqli_fetch_row($checkQueryResult);
$memberQuery = "INSERT INTO benneks.members(customerCode, customerName, customerTel, customerTelegramID) VALUES ('$customerCode', '$customerName', '$customerTel', '$customerTelegramID')";

if (!$user->executeQuery($memberQuery)) {
    $sback['result'] = "exsist";
    $sback['msg'] = mysqli_error($user->conn);
}

echo json_encode($sback, JSON_PRETTY_PRINT);
?>
