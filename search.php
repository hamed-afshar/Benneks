<?php

/*
 * Script to search values in db
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
if (isset($_POST['searchButton'])) {
    $searchOption = $_POST['searchOption'];
    $searchValue = $_POST['searchInput'];
    $searchquery = "";
    switch ($searchOption) {
        case "code":
            $searchQuery = "WHERE orders.orderID = " . $searchValue . "";  
            break;
        case "name":
            $searchQuery = "WHERE users.userName = " . "'" . $searchValue . "'" . "";
            break;
        case "done":
            $searchQuery = "WHERE stat.orderStatus = " . "'انجام شده'";
            break;
        case "cancel":
            $searchQuery = "WHERE stat.orderStatus = " . "'لغو'";
            break;
        case "unknown":
            $searchQuery = "WHERE stat.orderStatus IS NULL";
            break;
        default :
            $searchQuery = "";
            break;
    }
    $_SESSION['searchQuery'] = $searchQuery;
    header("Location: admin.php");
}
?>