<?php

/**
 * This script creates an excel file for different admin or user's reports
 */
/** Error reporting */
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

/** Include PHPExcel */
require_once dirname(__FILE__) . '/Excel-Classes/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Benneks")
        ->setLastModifiedBy("Admin")
        ->setTitle("Admin Report")
        ->setSubject("In details report for admin")
        ->setDescription("This document dreated by admin")
        ->setKeywords("office PHPExcel php")
        ->setCategory("Test result file");


// Add header titles
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'ردیف')
        ->setCellValue('B1', 'کد')
        ->setCellValue('C1', 'کاربر')
        ->setCellValue('D1', 'کشور')
        ->setCellValue('E1', 'قیمت ارزی')
        ->setCellValue('F1', 'قیمت اصلی')
        ->setCellValue('G1', 'قیمت محاسبه شده')
        ->setCellValue('H1', 'کارگو');
// Insert data to cells based on queries in session variables.
//Extract all recordes from query coming From admindetails.php also limitation must be removed from this query. 
$query1 = substr($_SESSION['query1'], 0, strpos($_SESSION['query1'], "LIMIT"));
$queryResult1 = $user->executeQuery($query1);
$i = 2;
while ($row = mysqli_fetch_row($queryResult1)){
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $i-1);
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $row[0]);
    $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $row[1]);
    $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $row[5]);
    $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $row[6]);
    $objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $row[8]);
    $objPHPExcel->getActiveSheet()->setCellValue('G'.$i, $row[9]);
    $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $row[10]);
    $i++;
}
// autosize the columns
$objSheet = $objPHPExcel->getActiveSheet();
$objSheet->getColumnDimension('A')->setAutoSize(true);
$objSheet->getColumnDimension('B')->setAutoSize(true);
$objSheet->getColumnDimension('C')->setAutoSize(true);
$objSheet->getColumnDimension('D')->setAutoSize(true);
$objSheet->getColumnDimension('E')->setAutoSize(true);
$objSheet->getColumnDimension('F')->setAutoSize(true);
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Save Excel 2007 file
/*$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="report.xlsx"');
header('Cache-Control: max-age=0');

$objWriter->save('php://output');*/

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="orderlist.xls"');

$objWriter->save('php://output');


