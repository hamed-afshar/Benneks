<?php

/*
 * This script will be used to add order shopping date into database
 * whn admin press add button in admin page
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
if (isset($_POST['submitButton'])) {
    $incomingPage = $_POST['incomingPage'];
    $status = "انجام شده-tamam";
    $statusDescription = "خرید با موفقیت-başarıyla satın aldı";
    $shoppingDate = $_POST['shoppingDate'];
    $orderID = $_POST['rowID'];
    $query = "UPDATE benneks.shipment inner JOIN benneks.stat ON shipment.orders_orderID = stat.orders_orderID SET shipment.benneksShoppingDate = '$shoppingDate', shipment.officeArrivalDate = NULL, stat.orderStatus = '$status', stat.orderStatusDescription = '$statusDescription' WHERE shipment.orders_orderID = '$orderID'";
    if (!$user->executeQuery($query)) {
        echo mysqli_error($user->conn);
    }

    //take order status and status description one stage back if reset button submited
} elseif (isset($_POST['resetButton'])) {
    $incomingPage = $_POST['incomingPage'];
    $orderID = $_POST['rowID'];
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
        case ($previousStatus === 'در راه ایران-iran yolunda' and $previousBenneksDeliverDate !== NULL and $previousShipmentKargoName !==NULL ) :
            $query = "UPDATE benneks.orders inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID inner join benneks.shipment ON shipment.orders_orderID = orders.orderID SET"
            . " stat.orderStatus = '$previousStatus', stat.orderStatusDescription = '$previousStatusDescription', shipment.benneksShoppingDate = '$previousShoppingDate', shipment.officeArrivalDate = '$previousArrivalDate' WHERE orders.orderID = '$orderID'";
            break;
        //if it has already returned in the Turkey
        case ($previousStatus === 'عودت ترکیه-İade-Turkey') :
            $query = "UPDATE benneks.orders inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID inner join benneks.shipment ON shipment.orders_orderID = orders.orderID SET"
            . " stat.orderStatus = 'رسیده به دفتر-officde', stat.orderStatusDescription = 'رسیده به دفتر-officde', shipment.benneksShoppingDate = '$previousShoppingDate', shipment.officeArrivalDate = '$previousArrivalDate' WHERE orders.orderID = '$orderID'";
            break;
        //if not bought and not office arrival
        case($previousShoppingDate === NULL and $previousArrivalDate === NULL) :
          $query = "UPDATE benneks.orders inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID inner join benneks.shipment ON shipment.orders_orderID = orders.orderID SET"
            . " stat.orderStatus = NULL, stat.orderStatusDescription = NULL, shipment.benneksShoppingDate = NULL, shipment.officeArrivalDate = NULL WHERE orders.orderID = '$orderID'";
            break;
        //if not bought and arrived to office
        case($previousShoppingDate === NULL and $previousArrivalDate !== NULL) :
          $query = "UPDATE benneks.orders inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID inner join benneks.shipment ON shipment.orders_orderID = orders.orderID SET"
            . " stat.orderStatus = NULL, stat.orderStatusDescription = NULL, shipment.benneksShoppingDate = NULL, shipment.officeArrivalDate = NULL WHERE orders.orderID = '$orderID'";
            break;
        //if bought and not arrived to office
        case($previousShoppingDate !== NULL and $previousArrivalDate === NULL) :
          $query = "UPDATE benneks.orders inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID inner join benneks.shipment ON shipment.orders_orderID = orders.orderID SET"
            . " stat.orderStatus = NULL, stat.orderStatusDescription = NULL, shipment.benneksShoppingDate = NULL, shipment.officeArrivalDate = NULL  WHERE orders.orderID = '$orderID'";
            break; 
        //if bought and arrived to office
        case($previousShoppingDate !== NULL and $previousArrivalDate !== NULL) :
          $query = "UPDATE benneks.orders inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID inner join benneks.shipment ON shipment.orders_orderID = orders.orderID SET"
            . " stat.orderStatus = 'انجام شده-tamam', stat.orderStatusDescription = 'خرید با موفقیت-başarıyla satın aldı', shipment.benneksShoppingDate = '$previousShoppingDate', shipment.officeArrivalDate = NULL WHERE orders.orderID = '$orderID'";
            break;  
    }
    if (!$user->executeQuery($query)) {
        echo mysqli_error($user->conn);
    }
}

mysqli_close($user->conn);

switch ($incomingPage) {
    case "turkish-Admin":
        header("Location: turkish-admin.php");
        break;
    case "farsi-Admin":
        header("Location: admin.php");
        break;
}
?>
