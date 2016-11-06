<?php

/*
 * This script will be used to add iran delivery date and product weight into database
 * when admin press deliver button in admin page
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
if (isset($_POST['submitButton'])) {
    $benneksDeliverDate = $_POST['benneksDeliverDate'];
    $productsWeight = $_POST['productsWeight'];
    $orderID = $_POST['rowID'];
} else  {
    echo "error";
}
$query = "UPDATE benneks.orders SET orders.benneksDeliverDate = '$benneksDeliverDate', orders.productsWeight = '$productsWeight' WHERE orders.orderID = '$orderID'";
if (!$user->executeQuery($query)) {
    echo mysqli_error($user->conn);
}
header("Location: admin.php");
?>
