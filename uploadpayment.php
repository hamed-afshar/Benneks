<?php

/*
 * script to upload payment pictures in to the system
 */
ob_start();
session_start();
require 'src/benneks.php';
// if users session is not set this will get access denied msg
if ($_SESSION['userAccess'] !== '1') {
    echo "اجازه دسترسی ندارید";
    exit();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$user = new user();
date_default_timezone_set("Asia/Tehran");

//define variables
if (isset($_POST['submitButton'])) {
    $customerCode = $_POST['customerCode'];
    $paymentPic = $_POST['paymentPic'];
// create folder for each customer based on customerCode and upload the product pic into database
    $userPaymentDir = $_SESSION['user'] . "-payment";
    $customerDir = $userPaymentDir . '/' . $customerCode;
    if (file_exists('paymentpics/' . $userPaymentDir . '/' . $customerCode)) {
        $targetDir = 'paymentpics/' . $userPaymentDir . '/' . $customerCode;
    } else {
        mkdir('paymentpics/' . $userPaymentDir . '/' . $customerCode);
        $targetDir = 'paymentpics/' . $userPaymentDir . '/' . $customerCode;
    }
    $fileName = time() . rand(11, 99) . basename($_FILES[$paymentPic]['name']);
    $targetPath1 = $targetDir . $fileName;
    move_uploaded_file($_FILES['paymentPic']["tmp_name"], $targetPath1);
}
?>