<?php

/* 
 * Script to print requiered information for accountant
 */
ob_start();
session_start();
require 'src/benneks.php';
// if Admin session is not set this will get access denied msg
if ($_SESSION['userAccess'] !== '4') {
    echo "اجازه دسترسی ندارید";
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
        ->setCellValue('C1', 'کارگو')
        ->setCellValue('D1', 'کد مشتری')
        ->setCellValue('E1', 'قیمت خرید')
        ->setCellValue('F1', 'قیمت فروش');

//query to extract requiered data from db and insert it to excel
$query = "SELECT orders.orderID, shipment.cargoName, members.customerCode, cost.benneksPrice, purchaseInfo.orderSalePrice from benneks.orders inner join benneks.shipment "
        . "on orders.orderID = shipment.orders_orderID INNER JOIN benneks.members ON members.customerCode = orders.members_customerCode "
        . "INNER JOIN benneks.purchaseInfo ON orders.purchaseInfo_purchaseID = purchaseInfo.purchaseID "
        . "INNER JOIN benneks.cost ON orders.orderID = cost.orders_orderID "
        . "WHERE shipment.cargoName = '$cargoName' and shipment.iranArrivalDate is not null;";
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
    $i++;
}

// autosize the columns
$objSheet->getColumnDimension('A')->setAutoSize(true);
$objSheet->getColumnDimension('B')->setAutoSize(true);
$objSheet->getColumnDimension('C')->setAutoSize(true);
$objSheet->getColumnDimension('D')->setAutoSize(true);
$objSheet->getColumnDimension('E')->setAutoSize(true);
$objSheet->getColumnDimension('F')->setAutoSize(true);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// define sytle for table
$objSheet->getDefaultStyle()->applyFromArray($style);
$objSheet->getStyle("A1:F1")->getFont()->setBold(TRUE);
$objSheet->getStyle("A1:F1")->getFont()->setSize(14);
$objSheet->getStyle('A1:F1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="accountant-kargo-' . $cargoName . '.xls"');

$objWriter->save('php://output');