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
$benneksDeliverDate = $_GET['benneksDeliverDate'];
$cargoName = $_GET['cargoName'];
$action = $_GET['action'];
/*apply relevant action based on the action got from deliver modal.
 * submit = to add cargo details into database.
 * change = means orderId has already assgined with a cargoname and should be changed to new one
 * reset: means all information about an orderID should be deleted and set to null
 */
switch ($action) {
    case "submit" :
        $checkQuery = "SELECT shipment.cargoName FROM benneks.orders INNER JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID WHERE orders.orderID = '$orderID'";
        $checkQueryResult = $user->executeQuery($checkQuery);
        $row = mysqli_fetch_row($checkQueryResult);
        if (isset($row[0])) {
            $sback['result'] = "exsist";
            $sback['msg'] = "برای این سفارش قبلا کد کارگو " . $row[0] . " وارد شده است" . " آیا مایل به تغییر می باشید؟";
        } else {
            $sback['result'] = "not-exsist";
            $query = "UPDATE benneks.orders inner JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID SET shipment.benneksDeliverDate = '$benneksDeliverDate', shipment.cargoName = '$cargoName' WHERE orders.orderID = '$orderID'";
            if (!$user->executeQuery($query)) {
                $sback['msg'] = "خطایی در ورود اطلاعات رخ داده است!";
            }
            $sback['msg'] = "کد کارگو " . $cargoName . " برای این سفارش ثبت گردید";
        }
        break;
    case "change" :
        $query = "UPDATE benneks.orders inner JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID SET shipment.benneksDeliverDate = '$benneksDeliverDate', shipment.cargoName = '$cargoName' WHERE orders.orderID = '$orderID'";
        if (!$user->executeQuery($query)) {
            $sback['msg'] = "خطایی در ورود اطلاعات رخ داده است!";
        }
        $sback['msg'] = "کد کارگو " . $cargoName . " برای این سفارش تغییر یافت";
        break;
    case "reset" :
        $query = "UPDATE benneks.orders inner JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID SET shipment.benneksDeliverDate = NULL, shipment.cargoName = NULL WHERE orders.orderID = '$orderID'";
        if (!$user->executeQuery($query)) {
            $sback['msg'] = "خطایی در ورود اطلاعات رخ داده است!";
        }
        $sback['msg'] = "اطلاعات کارگو و تاریخ ارسال برای این کد محصول حذف گردید.";
}


echo json_encode($sback, JSON_PRETTY_PRINT);
?>
