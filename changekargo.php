<?php

/* 
 * script to change kargo name in iran side admin
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
$cargoName = $_POST['cargoName'];
$query = "update benneks.orders inner join benneks.shipment on orders.orderID = shipment.orders_orderID inner join benneks.stat on orders.orderID = stat.orders_orderID set shipment.cargoName = '$cargoName', stat.orderStatus = 'رسیده به ایران-İrana galmiş', stat.orderStatusDescription = 'رسیده به ایران-İrana galmiş' where orders.orderID = '$orderID';";
if (!$user->executeQuery($query)) {
    echo mysqli_error($user->conn);
}

echo "کد کارگو شماره ". $cargoName . "برای سفارش شماره " . $orderID . "ثبت گردید.";