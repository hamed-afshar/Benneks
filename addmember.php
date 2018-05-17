<?php

/*
 * php script to add customer and member details into the system
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

//set member information
$userID = $_SESSION['user'];
$action = $_GET['action'];
$customerName = $_GET['customerName'];
$customerTel = $_GET['customerTel'];
$customerSocialLink = $_GET['customerSocialLink'];
$customerSocialID = $_GET['customerSocialID'];
$customerCode = $userID . doubleval($customerTel);
//apply proper action based on action
//add = check if this customerCode is available in the DB
//confirm = confim member insertion in to the database
switch ($action) {
    //on order submition we need to insert customer information details in to the members table but first need to check if this user has already exist
    case "add" :
        $checkQuery = "SELECT customerCode, customerName, customerSocialID from benneks.members WHERE members.customerCode = '$customerCode'";
        $checkQueryResult = $user->executeQuery($checkQuery);
        $row = mysqli_fetch_row($checkQueryResult);
        if ($row[0] === $customerCode) {
            $sback['result'] = "exsist";
            $sback['msg'] = "مشتری با کد ذکر شده، نام " . $row[1] . " و آیدی " . $row[2] . " در سیستم ثبت شده و لذا این سفارش برای همین مشتری ثبت می گردد، درصورت تمایل به تغییر مشخصات مشتری صفحه را مجدادا بارگذاری فرمایید.";
        } else {
            // if this new customer does not exist in the db then it will be added to the database
            $memberQuery = "INSERT INTO benneks.members(customerCode, customerName, customerTel, customerSocialLink, customerSocialID) VALUES ('$customerCode', '$customerName', '$customerTel', '$customerSocialLink', '$customerSocialID')";
            if (!$user->executeQuery($memberQuery)) {
                $sback['msg'] = "خطا دروارد شدن اطلاعات، لطفا با مدیر سیستم تماس بگیرید.";
            }
            $sback['result'] = "success";
            $sback['msg'] = " مشتری جدید با کد  " . $customerCode . " به سیستم اضافه گردید.";
        }
        break;
}

echo json_encode($sback, JSON_PRETTY_PRINT);
?>
