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
if (isset($_POST['submitButton'])) {
    $status = "انجام شده";
    $statusDescription = "خرید با موفقیت";
    $shoppingDate = $_POST['shoppingDate'];
    $orderID = $_POST['rowID'];
} else  {
    echo "error";
}
$query = "UPDATE benneks.shipment inner JOIN benneks.stat ON shipment.orders_orderID = stat.orders_orderID SET shipment.benneksShoppingDate = '$shoppingDate', stat.orderStatus = '$status', stat.orderStatusDescription = '$statusDescription' WHERE shipment.orders_orderID = '$orderID'";
if (!$user->executeQuery($query)) {
    echo mysqli_error($user->conn);
}
header("Location: admin.php");
?>
