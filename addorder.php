<?php
/* 
 * This script will be used to add order shopping date into database
 * whn admin press add button in admin page
 * 
 */
ob_start();
session_start();
require 'src/benneks.php';
// if session is not set this will redirect to login page
if (!isset($_SESSION['user'])) {
    header("Location: register.php");
    exit();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$user = new user();
date_default_timezone_set("Asia/Tehran");
$shoppingDate = $_POST['shoppingDate'];
$orderID = //something
$query = "INSERT INTO benneks.orders(benneksShoppingDate) VALUES('$shoppingDate') WHERE orderID = '$orderID'";
?>
