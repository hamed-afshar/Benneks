<?php

/**
 * This script creates is an order list maker for admin
 */
/** Error reporting */
ob_start();
session_start();
require 'src/benneks.php';
// if iran admin session is not set this will get access denied msg
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
if (isset($_POST['submitButton'])) {
    $orderDate = $_POST['orderDate'];
    $orderTime = $_POST['orderTime'];
    $country = $_POST['country'];
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
        ->setCellValue('B1', 'مشتری')
        ->setCellValue('C1', 'تاریخ')
        ->setCellValue('D1', 'نوع لباس')
        ->setCellValue('E1', 'سایز')
        ->setCellValue('F1', 'قیمت')
        ->setCellValue('G1', 'کد')
        ->setCellValue('H1', 'وضعیت')
        ->setCellValue('I1', 'کارگو')
        ->setCellValue('J1', 'برند')
        ->setCellValue('K1', 'عکس')
        ->setCellValue('L1', 'لینک');
//query to extract requiered data from db and insert it to excel
$query1 = "SELECT users.userName, orders.orderDate, orders.clothesType, orders.productSize, orders.productPrice, orders.orderID, stat.orderStatus, shipment.cargoName, " .
        "orders.productBrand, orders.productPic, orders.productLink FROM benneks.orders INNER JOIN benneks.stat ON orders.orderID = stat.orders_orderID " .
        "INNER JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID INNER JOIN benneks.users ON orders.users_userID = users.userID " .
        "INNER JOIN benneks.dateandtime ON orders.orderID = dateandtime.orderID where dateandtime.country = '$country' AND dateandtime.dtime >= str_to_date(concat('$orderDate','','$orderTime'),'%Y-%m-%d %H:%i:%s') order by dateandtime.dtime; ";
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
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $row[5]);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $row[6]);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $row[7]);
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, $row[8]);
    //set row size 100 pixel for each row
    $objSheet->getRowDimension($i)->setRowHeight(130);
    //create new drawing object for images
    $objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
    $extension = strtolower(strrchr($row[9], '.'));
    $emptyPic = 'orderpics/no-pic.jpg';
    switch ($extension) {
        case '.jpg':
        case '.jpeg':
            $productImage = @imagecreatefromjpeg($row[9]);
            if (!$productImage) {
                $productImage = imagecreatefromjpeg($emptyPic);
            }
            break;
        case '.png':
            $productImage = @imagecreatefrompng($row[9]);
            if (!$productImage) {
               $productImage = imagecreatefromjpeg($emptyPic);
            }
            break;
    }
    $objDrawing->setImageResource($productImage);
    $objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
    $objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
    $objDrawing->setHeight(80);
    $objDrawing->setWidth(80);
    $objDrawing->setCoordinates('K' . $i);
    $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, $row[10]);
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
$objSheet->getColumnDimension('K')->setWidth(14);
$objSheet->getColumnDimension('L')->setWidth(150);
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// define sytle for table
$objSheet->getDefaultStyle()->applyFromArray($style);
$objSheet->getStyle("A1:L1")->getFont()->setBold(TRUE);
$objSheet->getStyle("A1:L1")->getFont()->setSize(14);
$objSheet->getStyle('A1:L1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="orderlist.xls"');

$objWriter->save('php://output');

