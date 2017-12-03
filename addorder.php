<?php

/*
 * This script will be used to add order shopping date into database
 * when admin press add button in admin page
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

$status = "انجام شده-tamam";
$statusDescription = "خرید با موفقیت-başarıyla satın aldı";
$shoppingDate = $_GET['shoppingDate'];
$orderID = $_GET['orderID'];
$action = $_GET['action'];
//first check to see the latest order status
$checkQuery = "select stat.orderStatus from benneks.stat where orders_orderID =  '$orderID'";
$checkQueryResult = $user->executeQuery($checkQuery);
$row = mysqli_fetch_row($checkQueryResult);


switch ($action) {
    case "submit" :
        if ($row[0] === "در راه ایران-iran yolunda" || $row[0] === "عودت ترکیه-İade-Turkey" || $row[0] === "رسیده به دفتر-officde") {
            $sback['result'] = "exsist";
            $sback['msg'] = "imkansiz";
            break;
        }
        if ($row[0] === "لغو-İptal" || $row[0] === NULL || $row[0] === "انجام شده-tamam") {
            $query = "UPDATE benneks.shipment inner JOIN benneks.stat ON shipment.orders_orderID = stat.orders_orderID SET shipment.benneksShoppingDate = '$shoppingDate', shipment.officeArrivalDate = NULL, stat.orderStatus = '$status', stat.orderStatusDescription = '$statusDescription' WHERE shipment.orders_orderID = '$orderID'";
            if (!$user->executeQuery($query)) {
                echo mysqli_error($user->conn);
            }
            $sback['result'] = "not-exsist";
            $sback['msg'] = "tamam";
            break;
        }
    case "reset" :
        //first check the latest query stage
        $previousStageQuery = "select stat.orderStatus, stat.orderStatusDescription, shipment.benneksShoppingDate, shipment.officeArrivalDate, shipment.benneksDeliverDate, shipment.cargoName from benneks.stat inner join benneks.shipment on"
                . " stat.orders_orderID = shipment.orders_orderID where stat.orders_orderID = '$orderID';";
        if (!$user->executeQuery($previousStageQuery)) {
            echo mysqli_error($user->conn);
        }
        $previousStageQueryResult = $user->executeQuery($previousStageQuery);
        $row = mysqli_fetch_row($previousStageQueryResult);
        $previousStatus = $row[0];
        $previousStatusDescription = $row[1];
        $previousShoppingDate = $row[2];
        $previousArrivalDate = $row[3];
        $previousBenneksDeliverDate = $row[4];
        $previousShipmentKargoName = $row[5];
        switch (TRUE) {
            //if it has already assigned a cargo name
            case ($previousStatus === 'در راه ایران-iran yolunda' and $previousBenneksDeliverDate !== NULL and $previousShipmentKargoName !== NULL ) :
                $query = "UPDATE benneks.orders inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID inner join benneks.shipment ON shipment.orders_orderID = orders.orderID SET"
                        . " stat.orderStatus = '$previousStatus', stat.orderStatusDescription = '$previousStatusDescription', shipment.benneksShoppingDate = '$previousShoppingDate', shipment.officeArrivalDate = '$previousArrivalDate' WHERE orders.orderID = '$orderID'";
                $sback['msg'] = "tamam";
                break;
            //if it has already returned in the Turkey
            case ($previousStatus === 'عودت ترکیه-İade-Turkey') :
                $query = "UPDATE benneks.orders inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID inner join benneks.shipment ON shipment.orders_orderID = orders.orderID SET"
                        . " stat.orderStatus = 'رسیده به دفتر-officde', stat.orderStatusDescription = 'رسیده به دفتر-officde', shipment.benneksShoppingDate = '$previousShoppingDate', shipment.officeArrivalDate = '$previousArrivalDate' WHERE orders.orderID = '$orderID'";
                $sback['msg'] = "tamam";
                break;
            //if not bought and not office arrival
            case($previousShoppingDate === NULL and $previousArrivalDate === NULL) :
                $query = "UPDATE benneks.orders inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID inner join benneks.shipment ON shipment.orders_orderID = orders.orderID SET"
                        . " stat.orderStatus = NULL, stat.orderStatusDescription = NULL, shipment.benneksShoppingDate = NULL, shipment.officeArrivalDate = NULL WHERE orders.orderID = '$orderID'";
                $sback['msg'] = "tamam";
                break;
            //if not bought and arrived to office
            case($previousShoppingDate === NULL and $previousArrivalDate !== NULL) :
                $query = "UPDATE benneks.orders inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID inner join benneks.shipment ON shipment.orders_orderID = orders.orderID SET"
                        . " stat.orderStatus = NULL, stat.orderStatusDescription = NULL, shipment.benneksShoppingDate = NULL, shipment.officeArrivalDate = NULL WHERE orders.orderID = '$orderID'";
                $sback['msg'] = "tamam";
                break;
            //if bought or canceled and not arrived to office
            case($previousShoppingDate !== NULL and $previousArrivalDate === NULL) :
                $query = "UPDATE benneks.orders inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID inner join benneks.shipment ON shipment.orders_orderID = orders.orderID SET"
                        . " stat.orderStatus = NULL, stat.orderStatusDescription = NULL, shipment.benneksShoppingDate = NULL, shipment.officeArrivalDate = NULL  WHERE orders.orderID = '$orderID'";
                $sback['msg'] = "tamam";
                break;
            //if bought and arrived to office
            case($previousShoppingDate !== NULL and $previousArrivalDate !== NULL) :
                $query = "UPDATE benneks.orders inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID inner join benneks.shipment ON shipment.orders_orderID = orders.orderID SET"
                        . " stat.orderStatus = 'انجام شده-tamam', stat.orderStatusDescription = 'خرید با موفقیت-başarıyla satın aldı', shipment.benneksShoppingDate = '$previousShoppingDate', shipment.officeArrivalDate = NULL WHERE orders.orderID = '$orderID'";
                $sback['msg'] = "tamam";
                break;
        }
        if (!$user->executeQuery($query)) {
            echo mysqli_error($user->conn);
        }
        break;
}

echo json_encode($sback, JSON_PRETTY_PRINT);
?>
