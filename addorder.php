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
    header("Location: index.php");
    exit();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$user = new user();
date_default_timezone_set("Asia/Tehran");
if (isset($_POST['submitButton'])) {
    $incomingPage = $_POST['incomingPage'];
    $status = "انجام شده-tamam";
    $statusDescription = "خرید با موفقیت-başarıyla satın aldı";
    $shoppingDate = $_POST['shoppingDate'];
    $orderID = $_POST['rowID'];
    $query = "UPDATE benneks.shipment inner JOIN benneks.stat ON shipment.orders_orderID = stat.orders_orderID SET shipment.benneksShoppingDate = '$shoppingDate', stat.orderStatus = '$status', stat.orderStatusDescription = '$statusDescription' WHERE shipment.orders_orderID = '$orderID'";
} elseif (isset($_POST['resetButton'])) {
    $incomingPage = $_POST['incomingPage'];
    $orderID = $_POST['rowID'];
    $query = "UPDATE benneks.orders inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID inner join benneks.shipment ON shipment.orders_orderID = orders.orderID SET"
            . " stat.orderStatus = NULL, stat.orderStatusDescription = NULL, shipment.benneksShoppingDate = NULL WHERE orders.orderID = '$orderID'";
}

if (!$user->executeQuery($query)) {
    echo mysqli_error($user->conn);
}
mysqli_close($user->conn);

switch ($incomingPage) {
    case "turkish-Admin":
        header("Location: turkish-admin.php");
        break;
    case "farsi-Admin":
        header("Location: admin.php");
        break;
}
?>
