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
    $startDate = $_POST['startDate'];
    $finishDate = $_POST['finishDate'];
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
    ),
    'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      )
);

// Add header titles
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'ردیف')
        ->setCellValue('B1', 'تاریخ')
        ->setCellValue('C1', 'کد')
        ->setCellValue('D1', 'کد کارگو')
        ->setCellValue('E1', 'کد مشتری')
        ->setCellValue('F1', 'نحوه ارتباط')
        ->setCellValue('G1', 'آیدی مشتری')
        ->setCellValue('H1', 'نام مشتری')
        ->setCellValue('I1', 'قیمت تمام شده')
        ->setCellValue('J1', 'قیمت فروش به مشتری')
        ->setCellValue('K1', 'بیعانه')
        ->setCellValue('L1', 'الباقی')
        ->setCellValue('M1', 'سود')
        ->setCellValue('N1', 'وضعیت')
        ->setCellValue('O1', 'توضیحات');

//query to extract requiered data from db and insert it to excel
$userID = $_SESSION['user'];
$query1 = "SELECT orders.orderDate,orders.orderID, shipment.cargoName, members.customerCode, members.customerSocialLink, members.customerSocialID, members.customerName, "
        . "cost.benneksPrice, purchaseInfo.orderSalePrice, purchaseInfo.advancedPayment, stat.orderStatusDescription, purchaseInfo.paymentExtraDesc, orders.users_userID "
        . "FROM benneks.orders INNER JOIN benneks.members ON members.customerCode = orders.members_customerCode "
        . "INNER JOIN benneks.purchaseInfo ON purchaseInfo.purchaseID = orders.purchaseInfo_purchaseID "
        . "INNER JOIN benneks.shipment ON shipment.orders_orderID = orders.orderID "
        . "INNER JOIN benneks.cost ON cost.orders_orderID = orders.orderID "
        . "INNER JOIN benneks.users ON users.userID = orders.users_userID "
        . "INNER JOIN benneks.stat ON stat.orders_orderID = orders.orderID "
        . "WHERE users.userID = '$userID' AND orders.orderDate between '$startDate' AND '$finishDate'"; 
       
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
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, '=J' . $i . '-K' . $i);
    $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, '=J' . $i . '-I' . $i);
    $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, $row[10]);
    $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, $row[11]);
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
$objSheet->getColumnDimension('L')->setAutoSize(true);
$objSheet->getColumnDimension('M')->setAutoSize(true);
$objSheet->getColumnDimension('N')->setAutoSize(true);
$objSheet->getColumnDimension('O')->setAutoSize(true);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// define sytle for table
$objSheet->getDefaultStyle()->applyFromArray($style);
$objSheet->getStyle("A1:O1")->getFont()->setBold(TRUE);
$objSheet->getStyle("A1:O1")->getFont()->setSize(14);
$objSheet->getStyle('A1:O1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="customers-Excel.xls"');

$objWriter->save('php://output');
?>
