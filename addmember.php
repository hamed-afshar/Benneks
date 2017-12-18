<?php

/*
 * php script to add customer and member details into the system
 */

//member information
/*$customerName = $_POST['customerName'];
$customerTel = $_POST['customerTel'];
$customerTelegramID = $_POST['customerTelegramID'];
$customerCode = $userID + '-' + $customerTel;
//on order submition we need to insert customer information details in to the members table but first need to check if this user has already exist
    $memberQuery = "INSERT INTO benneks.members(customerCode, customerName, customerTel, customerTelegramID) VALUES ('$customerCode', '$customerName', '$customerTel', '$customerTelegramID')";*/

$sback['result'] = "exsist";
$sback['msg'] = "موفقیت";
echo json_encode($sback, JSON_PRETTY_PRINT);

?>
