<?php

/*
 * This script will be used to cancel a order in admin page 
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
    $status = "لغو";
    $statusDescription = $_POST['cancelDetails'];
    $orderID = $_POST['rowID'];
} else  {
    echo "error";
}
$query = "UPDATE benneks.orders inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID SET stat.orderStatus = '$status', stat.orderStatusDescription='$statusDescription' WHERE orders.orderID = '$orderID'";
if (!$user->executeQuery($query)) {
    echo mysqli_error($user->conn);
}
header("Location: admin.php");
?>