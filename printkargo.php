<?php

/* 
 * PHP Script to print kargo list based on user input code
 * 
 */
ob_start();
session_start();
require 'src/benneks.php';
// if turkish or iran admin session is not set this will get access denied msg
if ($_SESSION['userAccess'] !== '3' && $_SESSION['userAccess'] !== '2') {
    echo "اجازه دسترسی ندارید";
    exit();
}

/** Include PHPExcel */
require_once dirname(__FILE__) . '/Excel-Classes/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Benneks")
        ->setLastModifiedBy("Admin")
        ->setTitle("Kargo List")
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
        ->setCellValue('A1', 'No')
        ->setCellValue('B1', 'Kod')
        ->setCellValue('C1', 'Satin Almak Tarihi')
        ->setCellValue('D1', 'Fiyat');

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
$user = new user();
date_default_timezone_set("Asia/Tehran");
ini_set('memory_limit', '512M');

mysqli_autocommit($user->conn, false);
$flag = true;
if (isset($_POST['printButton'])) {
    $kargoCode = $_POST['kargoID'];
    $kargoLabel = $kargoCode;
    $kargoPrintQuery = "select orders.orderID, orders.orderDate, orders.productPrice from benneks.orders inner join benneks.shipment on orders.orderID = shipment.orders_orderID where shipment.cargoName = '$kargoCode' order by orders.orderDate;";
    $user->executeQuery($kargoPrintQuery);
}

$kargoPrintQueryResylt = $user->executeQuery($kargoPrintQuery);
// Quantity of shoes for each kargo
$shoesQtyQuery = "SELECT count(*) FROM benneks.orders INNER JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID "
        . "WHERE cargoName = '$kargoCode' AND clothesType LIKE '%کفش%'";
$user->executeQuery($shoesQtyQuery);
$shoesQtyResult = $user->executeQuery($shoesQtyQuery);
$shoesQtyRow = mysqli_fetch_row($shoesQtyResult);

//create excel sheet
$i = 2;
$objSheet = $objPHPExcel->getActiveSheet();
while ($row = mysqli_fetch_row($kargoPrintQueryResylt)) {
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $i - 1);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $row[0]);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $row[1]);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $row[2]);
    $i++;   
}
//insert shoes quantity in to the excel
$objSheet->getStyle('C'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
$objSheet->getStyle('D'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, "ayakkabi saysi");
$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $shoesQtyRow[0]);
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
header('Content-Disposition: attachment;filename="Kargo-' . $kargoLabel . '.xls"');
$objWriter->save('php://output');
