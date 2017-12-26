<?php

/* 
 * script to return a order in Iran
 */
ob_start();
session_start();
require 'src/benneks.php';
// if iran admin session is not set this will get access denied msg
if ($_SESSION['userAccess'] !== '2') {
    echo "اجازه دسترسی ندارید";
    exit();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$user = new user();
date_default_timezone_set("Asia/Tehran");

$orderID = $_POST['rowID'];
$returnReason = $_POST['returnReason'];
$orderStatus = "عودت ایران-İade-Iran";
$orderStatusDescription = $returnReason;
$query = "update benneks.stat set orderStatus = '$orderStatus', orderStatusDescription = '$orderStatusDescription' where stat.orders_orderID = '$orderID'";

if (!$user->executeQuery($query)) {
    echo mysqli_error($user->conn);
} else {
    echo "اطلاعات مرتبط با عودت سفارش شماره " . $orderID . "در سیستم ثبت گردید.";
}
