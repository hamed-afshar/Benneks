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

define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/Excel-Classes/PHPExcel.php';

// Create new PHPExcel object
echo date('H:i:s'), " Create new PHPExcel object", EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
echo date('H:i:s'), " Set document properties", EOL;
$objPHPExcel->getProperties()->setCreator("Benneks")
        ->setLastModifiedBy("Admin")
        ->setTitle("Admin Report")
        ->setSubject("In details report for admin")
        ->setDescription("This document dreated by admin")
        ->setKeywords("office PHPExcel php")
        ->setCategory("Test result file");


// Add header titles
echo date('H:i:s'), " Add some data", EOL;
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'ردیف')
        ->setCellValue('B1', 'کد')
        ->setCellValue('C1', 'کاربر')
        ->setCellValue('D1', 'قیمت اصلی')
        ->setCellValue('E1', 'قیمت محاسبه شده')
        ->setCellValue('F1', 'کارگو');
// Insert data to cells based on queries in session variables.
//Extract all recordes from query coming From admindetails.php also limitation must be removed from this query. 
$query1 = substr($_SESSION['query1'], 0, strpos($_SESSION['query1'], "LIMIT"));
$queryResult1 = $user->executeQuery($query1);
$i = 2;
while ($row = mysqli_fetch_row($queryResult1)){
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $i-1);
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $row[0]);
    $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $row[1]);
    $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $row[7]);
    $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $row[8]);
    $objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $row[9]);
    $i++;
}

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Save Excel 2007 file
echo date('H:i:s'), " Write to Excel2007 format", EOL;
$callStartTime = microtime(true);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
$callEndTime = microtime(true);
$callTime = $callEndTime - $callStartTime;

echo date('H:i:s'), " File written to ", str_replace('.php', '.xlsx', pathinfo(__FILE__, PATHINFO_BASENAME)), EOL;
echo 'Call time to write Workbook was ', sprintf('%.4f', $callTime), " seconds", EOL;
// Echo memory usage
echo date('H:i:s'), ' Current memory usage: ', (memory_get_usage(true) / 1024 / 1024), " MB", EOL;

