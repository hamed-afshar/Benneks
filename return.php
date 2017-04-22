<?php

/*
 * add information about return items in this script
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
$orderID = $_POST['rowID'];
$orderStatus = 'عودت';
$returnReason = $_POST['returnReason'];
$returnComment = $_POST['returnComment'];
$query = "UPDATE benneks.stat INNER JOIN benneks.orders ON orders.orderID = stat.orders_orderID SET stat.orderStatus = '$orderStatus', stat.orderStatusDescription = '$returnReason', stat.comment = '$returnComment' WHERE orders.orderID = '$orderID'";
if(!$user->executeQuery($query)) {
    echo mysqli_error($user->conn);
}
mysqli_close($user->conn);
header("Location: admin.php");
?>

