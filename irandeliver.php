<?php

/*
 * This script will be used to add iran delivery date and product weight into database
 * when admin press deliver button in admin page
 * 
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


$orderID = $_GET['orderID'];
$iranArrivalDate = $_GET['iranArrivalDate'];
$cargoName = $_GET['cargoName'];
$action = $_GET['action'];
/* apply relevant action based on the action got from deliver modal.
 * search = search for a cargo code
 */
switch ($action) {
    case "search" :
        $checkQuery = "SELECT shipment.cargoName FROM benneks.orders INNER JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID WHERE orders.orderID = '$orderID'";
        $checkQueryResult = $user->executeQuery($checkQuery);
        $row = mysqli_fetch_row($checkQueryResult);
        if (($row[0]) === $cargoName) {
            $orderStatus = 'رسیده به ایران-İrana galmiş';
            $orderStatusDescription = 'رسیده به ایران-İrana galmiş';
            $sback['result'] = "success-search";
            $sback['msg'] = "کد کارگو  " . $row[0] . " برای این سفارش با شماره " . $row[0] . "صحیح می باشد.";
            $query = "UPDATE benneks.orders inner JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID SET stat.orderStatus = '$orderStatus', stat.orderStatusDescription = '$orderStatusDescription', shipment.iranArrivalDate = '$iranArrivalDate' WHERE orders.orderID = '$orderID'";
        } else if (($row[0]) === NULL) {
            $orderStatus = 'رسیده به ایران-İrana galmiş';
            $orderStatusDescription = 'بدون کد کارگو';
            $sback['result'] = "null-search";
            $sback['msg'] = " برای سفارش شماره " . $orderID . " هیچ کد کارگویی ثبت نشده و لذا کارگو شماره" . $cargoName . " برای آن ثبت می گردد.";
            $query = "UPDATE benneks.orders inner JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID SET stat.orderStatus = '$orderStatus', stat.orderStatusDescription = '$orderStatusDescription', shipment.iranArrivalDate = '$iranArrivalDate', shipment.cargoName = '$cargoName' WHERE orders.orderID = '$orderID'";
        } else {
            $orderStatus = 'عودت ایران-İade-Iran';
            $orderStatusDescription = 'کارگوی تکراری';
            $sback['result'] = "wrong-search";
            $sback['msg'] = "کد سفارش " . $orderID . " قبلا در کارگو شماره " . $row[0] . "به ایران ارسال شده و لذا به لیست عودت در ایران اضافه می شود. ";
            $query = "UPDATE benneks.orders inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID inner JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID SET stat.orderStatus = '$orderStatus', stat.orderStatusDescription = '$orderStatusDescription', shipment.iranArrivalDate = '$iranArrivalDate' where orders.orderID = '$orderID'";
        }
        break;
}
if (!$user->executeQuery($query)) {
    $sback['msg'] = mysqli_error($user->conn);
}

echo json_encode($sback, JSON_PRETTY_PRINT);
?>
