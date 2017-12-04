<?php

/*
 * This script is used to cancel an order from user side but if this order has not bought already
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
    $orderID = $_POST['rowID'];
}
//First query table to find the status of the order, if it has bought or canceled already. Then it wont cancel the order

$query1 = "SELECT stat.orderStatus, stat.orderStatusDescription FROM benneks.stat WHERE stat.orders_orderID='$orderID'";
$result = $user->executeQuery($query1);
$row = mysqli_fetch_row($result);
$orderStatus = $row[0];
$orderStatusDescription = $row[1];
$flag = false;
$msg = "";

switch ($orderStatus) {
    //if the order is on the way to Iran then it should be returned in Iran
    case "در راه ایران-iran yolunda":
        $msg = "این خرید در راه ایران می باشد و بعد از رسیدن به ایران عودت خواهد گردید.";
        $query = "update benneks.stat set stat.orderStatus ='عودت ایران-İade-Iran', stat.orderStatusDescription = 'عودت ایران-İade-Iran' where stat.orders_orderID = '$orderID'";
        if (!$user->executeQuery($query)) {
            echo mysqli_error($user->conn);
        }
        break;
    //if this order has arrived to iran then user must contact to admin for returning this item
    case "رسیده به ایران-ulaşildi":
        $msg = "این خرید به ایران رسیده و برای عودت با مدیر سیستم تماس بگیرید";
        break;
    //before purchasing the order, then order will be canceled
    case NULL :
        $orderStatusDescription = "لغو به درخواست کاربر-müşteri isteginde iptal";
        $orderStatus = "لغو-İptal";
        $msg = "خرید شما لغو گردید";
        $query = "update benneks.stat set stat.orderStatus ='$orderStatus', stat.orderStatusDescription = '$orderStatusDescription' where stat.orders_orderID = '$orderID'";
        if (!$user->executeQuery($query)) {
            echo mysqli_error($user->conn);
        }
        break;
    //if it has already canceled 
    case "لغو-İptal":
        $msg = "این سفارش قبلا لغو شده و احتیاج به لغو مجدد نمی باشد.";
        break;
    //any stage else
    case "رسیده به دفتر-officde" || "انجام شده-tamam" || "عودت ترکیه-İade-Turkey":
        $orderStatus = "عودت ترکیه-İade-Turkey";
        $orderStatusDescription = "عودت ترکیه-İade-Turkey";
        $query = "update benneks.stat set stat.orderStatus ='$orderStatus', stat.orderStatusDescription = '$orderStatusDescription' where stat.orders_orderID = '$orderID'";
        $msg = "سفارش شما در ترکیه عودت گردید.";
        if (!$user->executeQuery($query)) {
            echo mysqli_error($user->conn);
        }
        break;
}


echo $msg;
mysqli_close($user->conn);
?>
