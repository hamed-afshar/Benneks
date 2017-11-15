<?php

/*
 * This script is responsible for making a kargo list for sending items to Iran.
 */
ob_start();
session_start();
require 'src/benneks.php';
// if Admin session is not set this will get access denied msg
if ($_SESSION['userAccess'] !== '2') {
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
        ->setCellValue('C1', 'Satin Almak Tarihi');

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
$user = new user();
date_default_timezone_set("Asia/Tehran");
ini_set('memory_limit', '512M');

if (isset($_POST['printButton'])) {
    $kargoCode = $_POST['kargoID'];
    $kargoLabel = $kargoCode;
    $kargoMakerQuery = "select orders.orderID, orders.orderDate from benneks.orders inner join benneks.shipment on orders.orderID = shipment.orders_orderID where shipment.cargoName = '$kargoCode' order by orders.orderDate;";
} else {
    // Query to extract the last kargo code from database
    $lastKargoQuery = "select max(cargoName) from benneks.shipment where cargoName REGEXP '^[0-9]{3}+$';";
    if (!$user->executeQuery($lastKargoQuery)) {
        echo mysqli_error($user->conn);
    }
    $lastKargoQueryResult = $user->executeQuery($lastKargoQuery);

    $row = mysqli_fetch_array($lastKargoQueryResult);
    $nextKargo = $row[0] + 1;
    $kargoLabel = $nextKargo;
//query to prepare a kargo list based on the office arrival date
    $kargoMakerQuery = "select orders.orderID, orders.orderDate from benneks.orders inner join benneks.shipment on orders.orderID = shipment.orders_orderID where shipment.officeArrivalDate is not null order by orders.orderDate asc limit 100;";
}



if (!$user->executeQuery($kargoMakerQuery)) {
    echo mysqli_error($user->conn);
}
$kargoMakerQueryResult = $user->executeQuery($kargoMakerQuery);
$i = 2;
$objSheet = $objPHPExcel->getActiveSheet();
while ($row = mysqli_fetch_row($kargoMakerQueryResult)) {
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $i - 1);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $row[0]);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $row[1]);
    $i++;
}

// autosize the columns
$objSheet->getColumnDimension('A')->setAutoSize(true);
$objSheet->getColumnDimension('B')->setAutoSize(true);
$objSheet->getColumnDimension('C')->setAutoSize(true);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// define sytle for table
$objSheet->getDefaultStyle()->applyFromArray($style);
$objSheet->getStyle("A1:C1")->getFont()->setBold(TRUE);
$objSheet->getStyle("A1:C1")->getFont()->setSize(14);
$objSheet->getStyle('A1:C1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Kargo-' . $kargoLabel . '.xls"');

$objWriter->save('php://output');
?>


