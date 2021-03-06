<?php

/* 
 * script to print kargo for user based on its userID
 */
ob_start();
session_start();
require 'src/benneks.php';
// if user session is not set then
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
$user = new user();
date_default_timezone_set("Asia/Tehran");
ini_set('memory_limit', '512M');

if (isset($_POST['submitButton'])) {
    $cargoName = $_POST['kargoID'];
}
/** Include PHPExcel */
require_once dirname(__FILE__) . '/Excel-Classes/PHPExcel.php';
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Benneks")
        ->setLastModifiedBy("Admin")
        ->setTitle("Admin Report")
        ->setSubject("In details report for admin")
        ->setDescription("This document created by admin")
        ->setKeywords("office PHPExcel php")
        ->setCategory("Test result file");
$style = array(
    'alignment' => array(
        'vertical' => PHPExcel_Style_Alignment:: VERTICAL_CENTER,
        'horizontal' => PHPExcel_Style_Alignment:: HORIZONTAL_LEFT,
    )
);

// Add header titles
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'ردیف')
        ->setCellValue('B1', 'کد')
        ->setCellValue('C1', 'وضعیت')
        ->setCellValue('D1', 'جزئیات')
        ->setCellValue('E1', 'کد کارگو')
        ->setCellValue('F1', 'نام مشتری')
        ->setCellValue('G1', 'نحوه ارتباط')
        ->setCellValue('H1', 'آیدی')
        ->setCellValue('I1', 'تلفن');

//query to extract requiered data from db and insert it to excel
$userID = $_SESSION['user'];
$query = "select orders.orderID, stat.orderStatus, stat.orderStatusDescription, shipment.cargoName, members.customerName,"
        . "members.customerSocialLink, members.customerSocialID, members.customerTel from benneks.users inner join benneks.orders on orders.users_userID = users.userID "
        . "inner join benneks.stat on orders.orderID = stat.orders_orderID inner join benneks.members on members.customerCode = orders.members_customerCode "
        . "inner join benneks.shipment on orders.orderID = shipment.orders_orderID "
        . "where users.userID = '$userID' and shipment.cargoName = '$cargoName' and (stat.orderStatus = 'رسیده به ایران-İrana galmiş' or stat.orderStatus = 'رسیده به ایران با مشکل-İrana galmiş');";
if (!$user->executeQuery($query)) {
    echo mysqli_error($user->conn);
}
$queryResult1 = $user->executeQuery($query);
$objSheet = $objPHPExcel->getActiveSheet();
$i = 2;

while ($row = mysqli_fetch_row($queryResult1)) {
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $i - 1);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $row[0]);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $row[1]);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $row[2]);
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $row[3]);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $row[4]);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $row[5]);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $row[6]);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $row[7]);
    $i++;
}

// autosize the columns
$objSheet->getColumnDimension('A')->setAutoSize(true);
$objSheet->getColumnDimension('B')->setAutoSize(true);
$objSheet->getColumnDimension('C')->setAutoSize(true);
$objSheet->getColumnDimension('D')->setAutoSize(true);
$objSheet->getColumnDimension('E')->setAutoSize(true);
$objSheet->getColumnDimension('F')->setAutoSize(true);
$objSheet->getColumnDimension('G')->setAutoSize(true);
$objSheet->getColumnDimension('H')->setAutoSize(true);
$objSheet->getColumnDimension('I')->setAutoSize(true);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// define sytle for table
$objSheet->getDefaultStyle()->applyFromArray($style);
$objSheet->getStyle("A1:I1")->getFont()->setBold(TRUE);
$objSheet->getStyle("A1:I1")->getFont()->setSize(14);
$objSheet->getStyle('A1:I1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Kargo-home-' . $cargoName . '.xls"');

$objWriter->save('php://output');
