<?php

/* 
script to add turkish admin comment in to the system
 */
ob_start();
session_start();
require 'src/benneks.php';
// if turkish admin session is not set this will get access denied msg
if ($_SESSION['userAccess'] !== '3') {
    echo "اجازه دسترسی ندارید";
    exit();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$user = new user();
date_default_timezone_set("Asia/Tehran");

$comment = $_POST['comment'];
$orderID = $_POST['rowID'];
$query  = "UPDATE benneks.stat SET comment = '$comment' WHERE orders_orderID = '$orderID'";
$user->executeQuery($query);
?>