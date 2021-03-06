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
    $searchReq = $_POST['searchReq'];
    $searchquery = "";
    switch ($searchOption) {
        case "code":
            $searchQuery = "orders.orderID = " . $searchValue . "";
            break;
        case "name":
            $searchQuery = "users.userName = " . "'" . $searchValue . "'" . "";
            break;
        case "done":
            $searchQuery = "stat.orderStatus = " . "'انجام شده-tamam'";
            break;
        case "turkey":
            $searchQuery = "orders.country = " . "'ترکیه'";
            break;
        case "fr":
            $searchQuery = "orders.country = " . "'فرانسه'";
            break;
        case "uk":
            $searchQuery = "orders.country = " . "'انگلیس'";
            break;
        case "cancel":
            $searchQuery = "stat.orderStatus = " . "'لغو-İptal'";
            break;
        case "unknown":
            $searchQuery = "stat.orderStatus IS NULL";
            break;
        case "cargo":
            $searchQuery = "shipment.cargoName = " . "'" . $searchValue . "'" . "";
            break;
        case "return":
            $searchQuery = "stat.orderStatus = " . "'عودت'";
            break;
        case "available": 
            $searchQuery = "orders.sellerSideAvailable = " . "'yes'";
            break;
        case "customerCode":
            $searchQuery = "members.customerCode = " . "'" . $searchValue . "'" . "";
            break;
        case "ref":
            $searchQuery = "stat.supplierRefCode = " . "'" . $searchValue . "'" . "";
            break;
        case "sellerName":
            $searchQuery = "users.userID = " . "'" . $searchValue . "'" . "";
            break;
        default :
            $searchQuery = "";
            break;
    }
    $_SESSION['searchQuery'] = $searchQuery;
    // Redirects user to target page based on the requesting page
    switch ($searchReq) {
        case "adminPage":
            header("Location: admin.php");
            break;
        case "admindetailsPage":
            header("Location: admindetails.php");
            break;
        case "orderlistPage":
            header("Location: orderlist.php");
            break;
        case "customersOrderList":
            header("Location: customersOrderList.php");
            break;
        case "turkish-admin":
            header("Location: turkish-admin.php");
            break;
        case "accountant":
            header("Location: accountant.php");
            break;
    }
}

if (isset($_POST['cancelSearchButton'])) {
    $searchReq = $_POST['searchReq'];
    unset($_SESSION['searchQuery']);
    switch ($searchReq) {
        case "adminPage":
            header("Location: admin.php");
            break;
        case "admindetailsPage":
            header("Location: admindetails.php");
            break;
        case "orderlistPage":
            header("Location: orderlist.php");
            break;
        case "turkish-admin":
            header("Location: turkish-admin.php");
            break;
        case "accountant":
            header("Location: accountant.php");
            break;
    }
}
?>