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

/* apply relevant action based on the action got from deliver modal.
 * submit = to add office delivery information.
 * reset = to delete all information and set them to one stage back
 */
$action = $_GET['action'];
$orderID = $_GET['orderID'];
$officeArrivalDate = $_GET['officeArrivalDate'];
$incomingPage = $_GET['incomingPage'];

//first check to see if this order ID has already cancealed by the user
$checkQuery = "select stat.orderStatus from bennek benneks.stat where orders_orderID = '$orderID'";
$checkQueryResult = $user->executeQuery($checkQuery);
$row = mysqli_fetch_row($checkQueryResult);

switch (action) {
    case "submit" :
        if ($row[0] === "عودت ترکیه-İade-Turkey") {
            $sback['result'] = "exsist";
            $sback['msg'] = "bu sipariş daha once mosteri tarafindan iptal olmuş, lotfan iade listerinde koyon";
            break;
        }
        if ($row[0] === "لغو-İptal") {
            $sback['result'] = "exsist";
            $sbcak['msg'] = "bu sipariş daha once mosteri tarafindan iptal olmuş, lotfan iade listerinde koyon";
            break;
        }
        if ($row[0] === NULL) {
            $sback['result'] = "exsist";
            $sbcak['msg'] = "bu sipariş daha once mosteri tarafindan iptal olmuş, lotfan iade listerinde koyon";
            break;
        }
        $sback['result'] = "exsist";
        $query = "UPDATE benneks.orders inner JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID SET shipment.officeArrivalDate = '$officeArrivalDate', stat.orderStatus = '$orderStatus', stat.orderStatusDescription = '$orderStatusDescription' WHERE orders.orderID = '$orderID'";
        if(!$user->executeQuery($query)) {
            $sback['msg'] = "خطایی در وارد کردن اطلاعات رخ داده است!";
        }
        $sback['msg'] = "sept";
        }


/* if (isset($_POST['submitButton'])) {
    $incomingPage = $_POST['incomingPage'];
    $officeArrivalDate = $_POST['officeArrivalDate'];
    $orderID = $_POST['rowID'];
    $orderStatus = "رسیده به دفتر-officde";
    $orderStatusDescription = "رسیده به دفتر-officde";
    $query = "UPDATE benneks.orders inner JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID SET shipment.officeArrivalDate = '$officeArrivalDate', stat.orderStatus = '$orderStatus', stat.orderStatusDescription = '$orderStatusDescription' WHERE orders.orderID = '$orderID'";
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
}*/
?>