<?php

/*
 * This script is to show comments for user in orderlistpage
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

$orderID = $_GET['orderID'];
$showCommentQuery = "select stat.comment from benneks.stat where orders_orderID = $orderID";
$commentQueryResult = $user->executeQuery($showCommentQuery);
$text = mysqli_fetch_row($commentQueryResult);
$sback['msg'] = $text[0];
echo json_encode($sback, JSON_PRETTY_PRINT);
?>
