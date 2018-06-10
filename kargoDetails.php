<?php

/* 
 *PHP scrıpt to insert kargo details in to the database
 */
ob_start();
session_start();
require 'src/benneks.php';
// if turkish admin session is not set this will get access denied msg
if ($_SESSION['userAccess'] !== '2') {
    echo "اجازه دسترسی ندارید";
    exit();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$user = new user();
date_default_timezone_set("Asia/Tehran");

//set kargo details
$kargoNO = $_POST['kargoNo'];
$exchangeAvrage = $_POST['exchangeAvrage'];
$kargoCost = $_POST['kargoCost'];
$wrongCost = $_POST['wrongCost'];
$missingCost = $_POST['missingCost'];
// query to get information from db and then insert all in to kargo table
$query1 = "SELECT SUM(cost.benneksPrice) AS benneksPrice, SUM(cost.iranDeliverCost) AS iranDeliverCost, SUM(cost.originalTomanPrice) AS originalTomanPrice, "
            . "SUM(CAST(orders.productPrice AS decimal(5,2))) AS currencySum, ROUND(AVG(cost.rateTL)) AS buyingCurrencyAVG, shipment.cargoName "
            . "from benneks.cost inner JOIN benneks.orders ON cost.orders_orderID = orders.orderID INNER JOIN benneks.shipment "
            . "ON shipment.orders_orderID = orders.orderID WHERE cargoName = '$kargoNO'";
    if (!$user->executeQuery($query1)) {
        echo mysqli_error($user->conn);
    }
    $queryResult1 = $user->executeQuery($query1);
    $row = mysqli_fetch_row($queryResult1);
    $benneksPrice = $row[0];
    $iranDeliverCost = $row[1];
    $originalTomanPrice = $row[2];
    $currencySum = $row[3];
    $buyingCurrencyAVG = $row[4];
$query2 = "INSERT INTO benneks.kargo(kargoNo, kargoCost, missingCost, wrongCost, exchangeAvg, benneksPriceSum, iranDeliverCostSum, originalTomanPriceSum, currencySum, buyingCurrencyAVG) "
        . "VALUES('$kargoNO','$kargoCost','$missingCost','$wrongCost','$exchangeAvrage','$benneksPrice','$iranDeliverCost','$originalTomanPrice','$currencySum','$buyingCurrencyAVG')";
if(!$user->executeQuery($query2)) {
    //echo 'اشتباه در وارد شدن اطلاعات به پایگاه داده';
    echo mysqli_error($user->conn);
}


?>
