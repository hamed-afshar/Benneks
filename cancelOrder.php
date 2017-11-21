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
    header("Location: index.php");
    exit();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$user = new user();
date_default_timezone_set("Asia/Tehran");

$orderID = $_GET['orderID'];
$action = $_GET['action'];
$incomingPage = $_GET['incomingPage'];
$statusDescription = $_GET['cancelDetails'];
/* apply relevant action based on the action got from deliver modal.
 * submit = to cancel order.
 * reset = to delete all information and set it as defualt
 */

switch ($action) {
    case "submit" :
        //first check to see if this order ID has already asgined a cargo code or not. if yes then it is not allowed to cancel the order
        $checkQuery = "select benneks.cargoName from benneks.shipment where orders_orderID ='$orderID'";
        $checkQueryResult = $user->executeQuery($checkQuery);
        $row = mysqli_fetch_row($checkQueryResult);
        if (isset($row[0])) {
            $sback['result'] = "exsist";
            $sback['msg'] = "کد کارگو شماره " . $row[0] . "قبلا برای این سفارش وارد شده است و شما نمیتوانید این سفارش را لغو نمایید.";
            echo json_encode($sback, JSON_PRETTY_PRINT);
        } else {
            $status = "لغو-İptal";
            $query = "UPDATE benneks.orders inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID INNER JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID SET stat.orderStatus = '$status', stat.orderStatusDescription='$statusDescription', shipment.benneksShoppingDate = null WHERE orders.orderID = '$orderID'";
            $user->executeQuery($query);
            $sback['result'] = "not-exsist";
            $sback['msg'] = "با موفقیت لغو گردید";
            mysqli_close($user->conn);
        }
        break;
    case "reset" :
        $query = "UPDATE benneks.orders inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID inner join benneks.shipment ON shipment.orders_orderID = orders.orderID SET"
                . " stat.orderStatus = NULL, stat.orderStatusDescription = NULL, shipment.benneksShoppingDate = NULL WHERE orders.orderID = '$orderID'";
        $user->executeQuery($query);
        mysqli_close($user->conn);
        break;
}

