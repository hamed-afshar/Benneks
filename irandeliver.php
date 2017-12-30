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
$cargoArray = explode(",", $cargoName);
/* apply relevant action based on the result get from query
 */
$checkQuery = "SELECT shipment.cargoName, stat.orderStatus, shipment.iranArrivalDate FROM benneks.stat INNER JOIN benneks.shipment ON stat.orders_orderID = shipment.orders_orderID WHERE shipment.orders_orderID = '$orderID'";
$checkQueryResult = $user->executeQuery($checkQuery);
$row = mysqli_fetch_row($checkQueryResult);
switch (TRUE) {
// if everything is ok
case ((in_array($row[0], $cargoArray)) AND $row[2] === NULL) :
$orderStatus = 'رسیده به ایران-İrana galmiş';
$orderStatusDescription = 'رسیده به ایران-İrana galmiş';
$sback['result'] = "success-search";
$sback['msg'] = "کد کارگو " . $row[0] . " برای این سفارش با شماره " . $row[0] . "صحیح می باشد.";
$query = "UPDATE benneks.orders inner JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID SET stat.orderStatus = '$orderStatus', stat.orderStatusDescription = '$orderStatusDescription', shipment.iranArrivalDate = '$iranArrivalDate' WHERE orders.orderID = '$orderID'";
break;
// if it has not assigned a cargo name
case ($row[0] === NULL):
$orderStatus = 'رسیده به ایران با مشکل-İrana galmiş';
$sback['result'] = "null-search";
$sback['msg'] = " برای این کد هیچ کد کارگویی ثبت نشده و لذاکد کارگو " . $cargoArray[0] . "برای آن ثبت می گردد.";
$query = "UPDATE benneks.orders inner JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID SET stat.orderStatus = '$orderStatus', shipment.iranArrivalDate = '$iranArrivalDate', shipment.cargoName = '$cargoArray[0]' WHERE orders.orderID = '$orderID'";
break;
// if it has already assgined a cargoname
case (!in_array($row[0], $cargoArray)) :
$orderStatus = "رسیده به ایران با مشکل-İrana galmiş";
$orderStatusDescription = "کارگو تکراری";
$sback['result'] = "wrong-search";
$sback['msg'] = "کد سفارش " . $orderID . "قبلا در کارگو" . $row[0] . "به ایران ارسال شده";
$query = "UPDATE benneks.orders inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID inner JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID SET stat.orderStatus = '$orderStatus', stat.orderStatusDescription = '$orderStatusDescription', shipment.iranArrivalDate = '$iranArrivalDate' where orders.orderID = '$orderID'";
break;
// if it is repeated in the same cargo name
case ((in_array($row[0], $cargoArray)) AND $row[2] !== NULL ) :
$orderStatus = "رسیده به ایران با مشکل-İrana galmiş";
$orderStatusDescription = "کد تکراری در کارگو";
$query = "UPDATE benneks.orders inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID inner JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID SET stat.orderStatus = '$orderStatus', stat.orderStatusDescription = '$orderStatusDescription', shipment.iranArrivalDate = '$iranArrivalDate' where orders.orderID = '$orderID'";
$sback['result'] = "wrong-search";
$sback['msg'] = "کد سفارش " . $orderID . "در کارگو " . $cargoName . "چند بار کد خرده است";
break;
// if it has returned in the iran
case ($row[1] === "عودت ایران-İade-Iran") :
$orderStatus = "رسیده به ایران با مشکل-İrana galmiş";
$sback['result'] = "wrong-search";
$sback['msg'] = "این کد در ایران مرجوع گردیده است";
$query = "UPDATE benneks.orders inner JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID SET stat.orderStatus = '$orderStatus', shipment.iranArrivalDate = '$iranArrivalDate', shipment.cargoName = '$cargoArray[0]' WHERE orders.orderID = '$orderID'";
break;
}
if (!$user->executeQuery($query)) {
    $sback['msg'] = mysqli_error($user->conn);
}

//counter for totall arrived orders in to the Iran based on the cargoName in input field
$cargoArrayString = implode(",",$cargoArray);
$query2 = "SELECT count(*) from benneks.shipment WHERE iranArrivalDate = '$iranArrivalDate' and cargoName IN ($cargoArrayString);";
$queryResult2 = $user->executeQuery($query2);
$row = mysqli_fetch_row($queryResult2);
$sback['counterMsg'] = "تعداد شمارش شده تا به حال: " . $row[0];

// counter for problem in arrival orders
$query3 = "SELECT count(*) from benneks.stat inner join benneks.shipment on stat.orders_orderID = shipment.orders_orderID where stat.orderStatus = 'رسیده به ایران با مشکل-İrana galmiş' and iranArrivalDate = '$iranArrivalDate' and cargoName IN ($cargoArrayString);";
$queryResult3 = $user->executeQuery($query3);
$row = mysqli_fetch_row($queryResult3);
$sback['counterErrorMsg'] = "اشتباهات: " . $row[0];
echo json_encode($sback, JSON_PRETTY_PRINT);
?>
