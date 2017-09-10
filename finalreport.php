<?php

/*
  PHP script to generate comperhensive report named finalreport
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
if (isset($_POST['submitButton'])) {
    $startDate = $_POST['startDate'];
    $finishDate = $_POST['finishDate'];
    $country = $_POST['countryReport'];
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
        ->setCellValue('C1', 'تاریخ')
        ->setCellValue('D1', 'فروشنده')
        ->setCellValue('E1', 'کشور')
        ->setCellValue('F1', 'نوع لباس')
        ->setCellValue('G1', 'وزن')
        ->setCellValue('H1', 'وضعیت')
        ->setCellValue('I1', 'کارگو')
        ->setCellValue('J1', 'قیمت اصلی')
        ->setCellValue('K1', 'نرخ ارز')
        ->setCellValue('L1', 'قیمت اصلی تومان')
        ->setCellValue('M1', 'درصد سود بنکس')
        ->setCellValue('N1', 'هزینه حمل تا ایران')
        ->setCellValue('O1', 'قیمت محاسبه شده')
        ->setCellValue('P1', 'هزینه اصل جنس و حمل')
        ->setCellValue('Q1', 'سود');

//query to extract requiered data from db and insert it to excel
$query1 = "select orders.orderID, orders.orderDate, users.userName, orders.country, orders.clothesType, orders.productsWeight, stat.orderStatus, shipment.cargoName, orders.productPrice, " .
        "cost.rateTL, cost.benneksMargin, cost.iranDeliverCost, cost.benneksPrice " .
        "FROM benneks.orders inner join benneks.users on orders.users_userID = users.userID inner join benneks.stat on orders.orderID = stat.orders_orderID inner join " .
        "benneks.shipment on orders.orderID = shipment.orders_orderID inner join benneks.cost on cost.orders_orderID = orders.orderID WHERE orders.country = '$country' AND " .
        "orders.orderDate between '$startDate' AND '$finishDate'";
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
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, $row[9]);
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, '=J' . $i . '*K' . $i);
    $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, $row[10]);
    $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, $row[11]);
    $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, $row[12]);
    $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, '=L' . $i . '+N' . $i);
    $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, '=O' . $i . '-(J' . $i . '*K' . $i . ')-N' . $i);
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
$objSheet->getColumnDimension('M')->setAutoSize(true);
$objSheet->getColumnDimension('N')->setAutoSize(true);
$objSheet->getColumnDimension('O')->setAutoSize(true);
$objSheet->getColumnDimension('P')->setAutoSize(true);
$objSheet->getColumnDimension('Q')->setAutoSize(true);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// define sytle for table
$objSheet->getDefaultStyle()->applyFromArray($style);
$objSheet->getStyle("A1:Q1")->getFont()->setBold(TRUE);
$objSheet->getStyle("A1:Q1")->getFont()->setSize(14);
$objSheet->getStyle('A1:Q1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="orderlist.xls"');

$objWriter->save('php://output');
?>
