<?php

/*
 * PHP script to create a excel file incuding orders that has bought but not arrived to office yet
 */
ob_start();
session_start();
require 'src/benneks.php';
// if Admin session is not set this will get access denied msg
if ($_SESSION['userAccess'] !== '2') {
    echo "اجازه دسترسی ندارید";
    exit();
}
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
$user = new user();
date_default_timezone_set("Asia/Tehran");
ini_set('memory_limit', '512M');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/Excel-Classes/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Benneks")
        ->setLastModifiedBy("Admin")
        ->setTitle("rapor")
        ->setSubject("10 days report")
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
        ->setCellValue('A1', 'no')
        ->setCellValue('B1', 'kod')
        ->setCellValue('C1', 'fiyat')
        ->setCellValue('D1', 'satin alma tarihi');
//query to extract orders purchased ten days ago but not arrived to the office yet from the db and insert them into the excel report file
$query1 = "select orders.orderID, orders.productPrice, shipment.benneksShoppingDate, shipment.officeArrivalDate from benneks.orders inner join benneks.shipment on orders.orderID = shipment.orders_orderID where "
        . "benneksShoppingDate < DATE_SUB(NOW(), INTERVAL 10 DAY) and benneksShoppingDate > '2017-12-01' and  officeArrivalDate is null order by benneksShoppingDate desc;";
if (!$user->executeQuery($query1)) {
    echo mysqli_error($user->conn);
}
$queryResult1 = $user->executeQuery($query1);
$objSheet = $objPHPExcel->getActiveSheet();
$i = 2;
while ($row = mysqli_fetch_row($queryResult1)) {
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $i - 1);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $row[0]);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $row[1]);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $row[2]);
    $i++;
}

// autosize the columns
$objSheet->getColumnDimension('A')->setAutoSize(true);
$objSheet->getColumnDimension('B')->setAutoSize(true);
$objSheet->getColumnDimension('C')->setAutoSize(true);
$objSheet->getColumnDimension('D')->setAutoSize(true);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// define sytle for table
$objSheet->getDefaultStyle()->applyFromArray($style);
$objSheet->getStyle("A1:D1")->getFont()->setBold(TRUE);
$objSheet->getStyle("A1:D1")->getFont()->setSize(14);
$objSheet->getStyle('A1:D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="10 gun Rapor.xls"');

$objWriter->save('php://output');