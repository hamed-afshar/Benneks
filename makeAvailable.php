<?php

/*
 * This script will be used to make a product available for sellers
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

//if add button clicked
if (isset($_POST['addButton'])) {
    $orderID = $_POST['rowID'];
    $query = "update benneks.orders set orders.sellerSideAvailable = 'yes' where orders.orderID = '$orderID'";
    $msg = "سفارش شما به لیست موجودی ها اضافه شد.";
}

//if remove button clicked
if (isset($_POST['removeButton'])) {
    $orderID = $_POST['rowID'];
    $query = "update benneks.orders set orders.sellerSideAvailable = NULL where orders.orderID = '$orderID'";
    $msg = "سفارش شما از لیست موجودی ها حذف گردید.";
}

// to show error msg
if (!$user->executeQuery($query)) {
    echo mysqli_error($user->conn);
}

echo $msg;
mysqli_close($user->conn);
?>