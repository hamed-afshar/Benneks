<?php

/*
 * This script will be used to add iran delivery date and product weight into database
 * when admin press deliver button in admin page
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
parse_str($_SERVER["QUERY_STRING"]);
$orderID = $_GET['value'];
$benneksDeliverDate = $_POST['benneksDeliverDate'];
$cargoName = $_POST['cargoName'];

//First check the code to find if it has already assigned a cargo name or not
$checkQuery = "SELECT shipment.cargoName FROM benneks.orders INNER JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID WHERE orders.orderID = '$orderID'";
$checkQueryResult = $user->executeQuery($checkQuery);
$row= mysqli_fetch_row($checkQueryResult);
if (isset($row[0])) {
    $sback['msg'] = "برای این سفارش قبلا کد کارگو وارد شده است".$row[0];
} else {   
   $query = "UPDATE benneks.orders inner JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID SET shipment.benneksDeliverDate = '$benneksDeliverDate', shipment.cargoName = '$cargoName' WHERE orders.orderID = '$orderID'";
    if (!$user->executeQuery($query)) {
        echo mysqli_error($user->conn);
    }
    $sback['msg'] = "کد کارگو برای این سفارش با موفقیت ثبت گردید";
    mysqli_close($user->conn);
    //header("Location: admin.php");
}
echo json_encode($sback, JSON_PRETTY_PRINT);
?>
