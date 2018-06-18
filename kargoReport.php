<?php

/*
 * script to make report for kargo profit and loss
 */
ob_start();
session_start();
require 'src/benneks.php';
// if turkish admin or iran admin session is not set this will get access denied msg
if ($_SESSION['userAccess'] !== '3' && $_SESSION['userAccess'] !== '2') {
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
        ->setCellValue('A1', 'ردیف')
        ->setCellValue('B1', 'شماره کارگو')
        ->setCellValue('C1', 'هزینه کارگو')
        ->setCellValue('D1', 'ضرر کدهای گم شده')
        ->setCellValue('E1', 'ضرر کدهای اشتباه')
        ->setCellValue('F1', 'متوسط لیر در خرید')
        ->setCellValue('G1', 'متوسط لیر در حواله')
        ->setCellValue('H1', 'مجموع ارزی کارگو')
        ->setCellValue('I1', 'سود یا زیان کارگو')
        ->setCellValue('J1', 'سود یا زیان خرید لیر')
        ->setCellValue('K1', 'سود یا زیان کل');

//query to extract requiered data from db and insert it to excel
$query1 = "SELECT * FROM benneks.kargo";
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
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $row[3]);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $row[4]);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $row[9]);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $row[8]);
    //benneksPriceSum - originalPriceTomanSum - iranDeliverCostSum - missingCost -wrongCost
    $columnI = intval($row[5]) - intval($row[7]) - intval($row[6]) - intval($row[2]) - intval($row[3]);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $columnI);
    // (exchangeAVG - buyingCurrencyAVG) * currencySUM
    $columnJ = (intval($row[9]) - intval($row[4])) * intval($row[8]);
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, $columnJ);
    // add two numbers
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, '=I' . $i . '+J' . $i);
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
$objSheet->getColumnDimension('J')->setAutoSize(true);
$objSheet->getColumnDimension('K')->setAutoSize(true);


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// define sytle for table
$objSheet->getDefaultStyle()->applyFromArray($style);
$objSheet->getStyle("A1:K1")->getFont()->setBold(TRUE);
$objSheet->getStyle("A1:K1")->getFont()->setSize(14);
$objSheet->getStyle('A1:K1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="kargo-profit-report.xls"');

$objWriter->save('php://output');
?>