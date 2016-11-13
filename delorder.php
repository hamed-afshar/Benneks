<?php

/* 
 * This script is used to delete an order from user side but if this order has not bought already
 */
ob_start();
session_start();
require 'src/benneks.php';
// if session is not set this will redirect to login page
if (!isset($_SESSION['user'])) {
    header("Location: register.php");
    exit();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$user = new user();
date_default_timezone_set("Asia/Tehran");
if (isset($_POST['submitButton'])) {
    $orderID = $_POST['rowID'];
}
//First query table to find the status of the order, if it has bought or canceled already. Then it wont delete the order
$query1 = "SELECT stat.orderStatus, stat.orderStatusDescription FROM benneks.stat WHERE stat.orders_orderID='$orderID'";
$result = $user->executeQuery($query1);
$row = mysqli_fetch_row($result);
$orderStatus = $row[0];
$orderStatusDescription = $row[1];
$flag = false;
$msg = "";
//echo $orderStatus;
switch($orderStatus) {
    case "انجام شده" : 
        $msg = "خرید شما انجام شده است لذا امکان لغو سفارش وجود ندارد";
        $flag = false;
        break;
    case "لغو" :
        $msg = "خرید شما قبلا به دلیل " . $orderStatusDescription . "لغو گردیده است";
        $flag = false;
        break;
    default :
        $msg = "سفارش شما با موفقیت لغو گردید.";
        $flag = true;
}
    
if($flag == true ) {
    $query2 = "DELETE FROM benneks.orders WHERE orders.orderID = '$orderID'";
    $user->executeQuery($query2);
}
echo $msg;
//header("Location: orderlist.php");
?>
