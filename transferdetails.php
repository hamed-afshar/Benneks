<?php

/*
 * script to insert transfer details into the database
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

//define variables
$transferDate = $_GET['transferDate'];
$exchangeRate = $_GET['exchangeRate'];
$amount = $_GET['amount'];
$action = $_GET['action'];
$amountToman = intval($amount) * intval($exchangeRate);
$transferCode = intval($_GET['transferCode']);

switch ($action) {
    case "submit" :
        $query1 = "INSERT INTO benneks.transfer(transDate, amount, exchangeRate, amountToman) VALUES('$transferDate', '$amount', '$exchangeRate', '$amountToman')";
        if (!$user->executeQuery($query1)) {
            echo mysqli_error($user->conn);
        }
        $sback['msg'] = "اطلاعات حواله با موفقیت ثبت گردید.";
        break;
    case "confirm" :
        $checkQuery = "SELECT transDate FROM benneks.transfer WHERE transRef = '$transferCode'";
        $checkQueryResult = $user->executeQuery($checkQuery);
        $row = mysqli_fetch_row($checkQueryResult);
        if ($row[0] === NULL) {
            $sback['msg'] = "کد حواله وارد شده اشتباه می باشد.";
            break;
        } else {
            $query2 = "UPDATE benneks.transfer SET transfer.transDate = '$transferDate', transfer.amount = '$amount', transfer.exchangeRate = '$exchangeRate' , transfer.amountToman = '$amountToman' "
                . " WHERE transfer.transRef = '$transferCode'";
            if(!$user->executeQuery($query2)) {
                $sback['msg'] = "خطایی در ورود اطلاعات رخ داده است.";
            }
            $sback['msg'] = "تغییرات مورد نظر با موفقیت اعمال گردید";
            break;
        }
}
echo json_encode($sback, JSON_PRETTY_PRINT);
?>