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
/* apply relevant action based on the result get from query
 */
$checkQuery = "SELECT shipment.cargoName, stat.orderStatus FROM benneks.stat INNER JOIN benneks.shipment ON stat.orders_orderID = shipment.orders_orderID WHERE shipment.orders_orderID = '$orderID'";
$checkQueryResult = $user->executeQuery($checkQuery);
$row = mysqli_fetch_row($checkQueryResult);
switch (TRUE) {
    case ($row[0] === $cargoName) :

        $orderStatus = 'رسیده به ایران-İrana galmiş';
        $orderStatusDescription = 'رسیده به ایران-İrana galmiş';
        $sback['result'] = "success-search";
        $sback['msg'] = "کد کارگو " . $row[0] . " برای این سفارش با شماره " . $row[0] . "صحیح می باشد.";
        $query = "UPDATE benneks.orders inner JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID SET stat.orderStatus = '$orderStatus', stat.orderStatusDescription = '$orderStatusDescription', shipment.iranArrivalDate = '$iranArrivalDate' WHERE orders.orderID = '$orderID'";
        break;
    case ($row[0] === NULL):
        $orderStatus = 'رسیده به ایران-İrana galmiş';
        $orderStatusDescription = 'به ایران رسیده اما در ترکیه کد کارگویی برای آن ثبت نشده و لدا کد کارگور' . $cargoName . "برای آن ثبت گردید";
        $sback['result'] = "null-search";
        $sback['msg'] = " برای سفارش شماره " . $orderID . " هیچ کد کارگویی ثبت نشده و لذا کارگو شماره" . $cargoName . " برای آن ثبت می گردد.";
        $query = "UPDATE benneks.orders inner JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID SET stat.orderStatus = '$orderStatus', stat.orderStatusDescription = '$orderStatusDescription', shipment.iranArrivalDate = '$iranArrivalDate', shipment.cargoName = '$cargoName' WHERE orders.orderID = '$orderID'";
        break;
    case ($row[1] === 'عودت ترکیه-İade-Turkey' or $row[1] === 'لغو-İptal'):
        $orderStatus = 'رسیده به ایران-İrana galmiş';
        $orderStatusDescription = 'این کد در ترکیه عودت و یا لغو شده و نباید به ایران ارسال میشده';
        $sback['result'] = "return-search";
        $sback['msg'] = "این کد در ترکیه عودت شده و نباید به ایران ارسال میشده "; 
        $query = "UPDATE benneks.orders inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID inner JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID SET stat.orderStatus = '$orderStatus', stat.orderStatusDescription = '$orderStatusDescription', shipment.iranArrivalDate = '$iranArrivalDate', shipment.cargoName = '$cargoName' WHERE orders.orderID = '$orderID'";
        
        break;
    case ($row[0] !== $cargoName) :
        $orderStatus = "رسیده به ایران-İrana galmiş";
        $orderStatusDescription = "این کد قبلا در کارگو شماره " . $row[0] . "به ایران ارسال شده و کد کارگوی آن به " . $cargoName . "تغییر نمود.";
        $sback['result'] = "wrong-search";
        $sback['msg'] = "کد سفارش " . $orderID . " قبلا در کارگو شماره " . $row[0] . "به ایران ارسال شده اما کد کارگو " . $cargoName . " برای آن ثبت خواهد گردید";
        $query = "UPDATE benneks.orders inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID inner JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID SET stat.orderStatus = '$orderStatus', stat.orderStatusDescription = '$orderStatusDescription', shipment.iranArrivalDate = '$iranArrivalDate', shipment.cargoName = '$cargoName' where orders.orderID = '$orderID'";
        
}
if (!$user->executeQuery($query)) {
    $sback['msg'] = mysqli_error($user->conn);
}

//counter for arrived orders in to the Iran
$query2 = "SELECT count(*) from benneks.shipment WHERE cargoName = '$cargoName' AND iranArrivalDate = '$iranArrivalDate'";

$queryResult2 = $user->executeQuery($query2);
$row = mysqli_fetch_row($queryResult2);
$sback['counterMsg'] = "تعداد شمارش شده تا به حال: " . $row[0];

echo json_encode($sback, JSON_PRETTY_PRINT);
?>
