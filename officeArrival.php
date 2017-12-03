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
$orderStatus = "رسیده به دفتر-officde";
$orderStatusDescription = "رسیده به دفتر-officde";

//first check to see if this order ID has already cancealed by the user
$checkQuery = "select stat.orderStatus from benneks.stat where orders_orderID =  '$orderID'";
$checkQueryResult = $user->executeQuery($checkQuery);
$row = mysqli_fetch_row($checkQueryResult);
$previousOrderStatus = $row[0];

switch ($action) {
    case "submit" :
        //if it hase asigned a cargo code
        if($row[0] === "در راه ایران-iran yolunda") {
            $sback['result'] = "exsist";
            $findKargoQuery = "select shipment.cargoName from benneks.shipment where orders_orderID = '$orderID'";
            $findKargoQueryResult = $user->executeQuery($findKargoQuery);
            $res = mysqli_fetch_row($findKargoQueryResult);
            $kargoNo = $res[0];
            $sback['msg'] = "Bu Sipariş daha onçe kargodan irana gunderdilar-kargo $kargoNo" . " değiştirmek imkansız ";;
            break;
        }
        //if it has returned 
        if ($row[0] === "عودت ترکیه-İade-Turkey") {
            $sback['result'] = "exsist";
            $sback['msg'] = "bu sipariş daha once mosteri tarafindan iptal olmuş, lotfan iade listerinde koyon";
            break;
        }
        //if it has canceled 
        if ($row[0] === "لغو-İptal") {
            $sback['result'] = "exsist";
            $sback['msg'] = "bu sipariş daha oncesi satin alma zamani iptal olmuş ve officede olmamali lotfan bunu iade listde koyon";
            break;
        }
        // if without any status(pre order)
        if ($row[0] === NULL) {
            $sback['result'] = "exsist";
            $sback['msg'] = "bu siparis hala satin almamiş lik bunu satin alin";
            break;
        }
        $sback['result'] = "not-exist";
        $query = "UPDATE benneks.orders inner JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID SET shipment.officeArrivalDate = '$officeArrivalDate', stat.orderStatus = '$orderStatus', stat.orderStatusDescription = '$orderStatusDescription' WHERE orders.orderID = '$orderID'";
        if (!$user->executeQuery($query)) {
            $sback['msg'] = "خطایی در وارد کردن اطلاعات رخ داده است!";
        }
        $sback['msg'] = "sept";
        break;
}
mysqli_close($user->conn);
echo json_encode($sback, JSON_PRETTY_PRINT);

?>