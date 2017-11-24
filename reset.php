<?php

/* 
 * Reset script is responsible for set product status and statu description one step back
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

$incomingPage = $_POST['incomingPage'];
$orderID = $_POST['rowID'];

$previousStageQuery = "select stat.orderStatus, stat.orderStatusDescription from benneks.stat where stat.orders_orderID = '$orderID'";
if (!$user->executeQuery($query)) {
    echo mysqli_error($user->conn);
}

$previousStageQueryResult = $user->executeQuery($previousStageQuery);
$row = mysqli_fetch_row($previousStageQuery);
$priviousStatus="$row[0]";
$previousStatusDescription = "$row[1]";



/* if (isset($_POST['resetButton'])) {
    
    
    $query = "UPDATE benneks.orders inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID inner join benneks.shipment ON shipment.orders_orderID = orders.orderID SET"
            . " stat.orderStatus = NULL, stat.orderStatusDescription = NULL, shipment.benneksShoppingDate = NULL WHERE orders.orderID = '$orderID'";
}*/
echo $priviousStatus;
echo $previousStatusDescription;