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

$action = $_GET['action'];
$orderID = $_GET['orderID'];
$incomingPage = $_GET['incomingPage'];
$statusDescription = $_GET['cancelDetails'];
/* apply relevant action based on the action got from cancel modal.
 * submit = to cancel order.
 * reset = to delete all information and set it as defualt
 */

//first check to see if this order ID has already asgined a cargo code or not. if yes then it is not allowed to cancel the order or reset it
$checkQuery = "select stat.orderStatus from benneks.stat where orders_orderID =  '$orderID'";
$checkQueryResult = $user->executeQuery($checkQuery);
$row = mysqli_fetch_row($checkQueryResult);

switch ($action) {
    case "submit" :
        if ($row[0] === "در راه ایران-iran yolunda" ) {
            $sback['result'] = "exsist";
            $findKargoQuery = "select shipment.cargoName from benneks.shipment where orders_orderID = '$orderID'";
            $findKargoQueryResult = $user->executeQuery($findKargoQuery);
            $res = mysqli_fetch_row($findKargoQueryResult);
            $kargoNo = $res[0];
            $sback['msg'] = "Bu Sipariş daha onçe kargodan irana gunderdilar-kargo $kargoNo" . " iptal imkansız ";
            break;
        }
        if ($row[0] === "رسیده به دفتر-officde") {
            $sback['result'] = "exsist";
            $sback['msg'] = "این سفارش به دفتر رسیده و لغو آن امکان پذیر نمی باشد و شما به جای لغو می توانید این سفارش را عودت دهید.";
            break;
        }
        if ($row[0] === "عودت ترکیه-İade-Turkey") {
            $sback['result'] = "exsist";
            $sback['msg'] = "این سفارش به دفتر رسیده و عودت شده است پس عملیات لغو امکان پذیر نمی باشد.";
            break;
        }
        $status = "لغو-İptal";
        $query = "UPDATE benneks.orders inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID INNER JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID SET stat.orderStatus = '$status', stat.orderStatusDescription='$statusDescription', shipment.benneksShoppingDate = null WHERE orders.orderID = '$orderID'";
        if (!$user->executeQuery($query)) {
            $sback['msg'] = "خطایی در وارد کردن اطلاعات رخ داده است!";
        }
        $sback['result'] = "not-exsist";
        $sback['msg'] = "Sipariş İptali ";
        mysqli_close($user->conn);
        break;
}
echo json_encode($sback, JSON_PRETTY_PRINT);

