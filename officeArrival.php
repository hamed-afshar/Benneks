<?php
/*
 * This script will be used to add officeArivaldate into database
 * when turkish admin click on home button in her panel.
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
    $officeArrivalDate = $_POST['officeArrivalDate'];
    $orderID = $_POST['rowID'];
    $query = "UPDATE benneks.orders inner JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID SET shipment.officeArrivalDate = '$officeArrivalDate', stat.orderStatus = 'رسیده به دفتر-officde', stat.orderStatusDescription = 'رسیده به دفتر-officde' WHERE orders.orderID = '$orderID'";
    echo $query;
} elseif (isset($_POST['resetButton'])) {
    $incomingPage = $_POST['incomingPage'];
    $orderID = $_POST['rowID'];
    $query = "UPDATE benneks.orders inner JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID SET shipment.officeArrivalDate = NULL, stat.orderStatus = NULL, stat.orderStatusDescription = NULL WHERE orders.orderID = '$orderID'";
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