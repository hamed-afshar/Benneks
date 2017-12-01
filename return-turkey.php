<?php

/*
 * add information about return items in this script
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
$orderStatus = "عودت ترکیه-İade-Turkey";
$orderStatusDescription = $_GET['returnReason'];

//first check to see if this order ID has already arrived to the office
$checkQuery = "select stat.orderStatus from benneks.stat where orders_orderID =  '$orderID'";
$checkQueryResult = $user->executeQuery($checkQuery);
$row = mysqli_fetch_row($checkQueryResult);
$previousOrderStatus = $row[0];

switch ($action) {
    case "submit" :
        if($row[0] === "در راه ایران-iran yolunda") {
            $sback['result'] = "exsist";
            $findKargoQuery = "select shipment.cargoName from benneks.shipment where orders_orderID = '$orderID'";
            $findKargoQueryResult = $user->executeQuery($findKargoQuery);
            $res = mysqli_fetch_row($findKargoQueryResult);
            $kargoNo = $res[0];
            $sback['msg'] = "Bu Sipariş daha onçe kargodan irana gunderdilar-kargo $kargoNo" . " iade imkansız ";
            break;
        }
        if ($row[0] === "عودت ترکیه-İade-Turkey") {
            $sback['result'] = "exsist";
            $sback['msg'] = "قبلا به لیست عودت اضافه شده و احتیاج به ثبت مجدد نمی باشد.";
            break;
        }
        if ($row[0] !== "رسیده به دفتر-officde") {
            $sback['result'] = "exsist";
            $sback['msg'] = "برای اعاده سفارش در ابتدا باید آن سفارش به دفتر رسیده باشد و هنوز رسیدن به دفتر وارد سیستم نشده";
            break;
        }
        $sback['result'] = "not-exist";
        $query = "UPDATE benneks.orders inner JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID SET stat.orderStatus = '$orderStatus', stat.orderStatusDescription = '$orderStatusDescription' WHERE orders.orderID = '$orderID'";
        if (!$user->executeQuery($query)) {
            $sback['msg'] = "خطایی در وارد کردن اطلاعات رخ داده است!";
        }
        $sback['msg'] = "باموفقیت به لیست اعاده ها اضافه شد.";
        break;
}
mysqli_close($user->conn);
echo json_encode($sback, JSON_PRETTY_PRINT);

?>

