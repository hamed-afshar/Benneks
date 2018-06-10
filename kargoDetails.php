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
$query = "INSERT INTO benneks.kargo(kargoNo, kargoCost, missingCost, wrongCost, exchangeAvg) VALUES('$kargoNO','$kargoCost','$missingCost','$wrongCost','$exchangeAvrage')";
if(!$user->executeQuery($query)) {
    //echo 'اشتباه در وارد شدن اطلاعات به پایگاه داده';
    echo mysqli_error($user->conn);
}

?>
