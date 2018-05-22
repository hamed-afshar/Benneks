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

$action = $_GET['action'];
$comment = $_GET['comment'];
$orderID = $_GET['orderID'];
switch ($action) {
    case "submit" : {
            $query1 = "UPDATE benneks.stat SET comment = '$comment' WHERE orders_orderID = '$orderID'";
            if (!$user->executeQuery($query1)) {
                echo mysqli_error($user->conn);
            }
            break;
        }

    case "show" :
        $query = "SELECT comment FROM benneks.stat WHERE orders_orderID = '$orderID'";
        $queryResult = $user->executeQuery($query);
        $text = mysqli_fetch_row($queryResult);
        $sback['msg'] = $text[0];
        echo json_encode($sback, JSON_PRETTY_PRINT);
        break;
}
?>