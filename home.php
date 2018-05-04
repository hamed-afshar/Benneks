<?php
ob_start();
session_start();
require 'src/benneks.php';
// if session is not set this will redirect to login page
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$user = new user();
mysqli_autocommit($user->conn, false);
$flag = true;
date_default_timezone_set("Asia/Tehran");
if (isset($_POST['submitOrderButton'])) {
//Need to get the last customerID from Database if it is a first record then LastID will be 0;
    $query1 = "SELECT customerID FROM benneks.customers ORDER BY customerID DESC LIMIT 1";
    if (!$user->executeQuery($query1)) {
        $flag = false;
        echo "error1";
        echo mysqli_error($user->conn);
    } else {
        $lastCustomerIDResult = $user->executeQuery($query1);
        $row = mysqli_fetch_array($lastCustomerIDResult);
        $lastID = $row['customerID'];
    }
    if (is_null($lastID)) {
        $lastID = 0;
    }
    // create folder for each user based on userID and upload the product pic into database
    $userDir = $_SESSION['user'];
    if (file_exists('orderpics/' . $userDir)) {
        $targetDir = 'orderpics/' . $userDir . "/";
    } else {
        mkdir('orderpics/' . $userDir);
        $targetDir = 'orderpics/' . $userDir . "/";
    }
    $fileName = time() . rand(11, 99) . basename($_FILES['productPic']['name']);
    $targetPath1 = $targetDir . $fileName;
    move_uploaded_file($_FILES["productPic"]["tmp_name"], $targetPath1);

    // to save payment pictures, create a foolder for each user and save payment pictures to this folder
    $userPaymentDir = $_SESSION['user'] . "-payment";
    if (file_exists('paymentpics/' . $userPaymentDir)) {
        $targetDir = 'paymentpics/' . $userPaymentDir . "/";
    } else {
        mkdir('paymentpics/' . $userPaymentDir);
        $targetDir = 'paymentpics/' . $userPaymentDir . "/";
    }
    $fileName = time() . rand(11, 99) . basename($_FILES['paymentRefPic']['name']);
    $targetPath2 = $targetDir . $fileName;
    move_uploaded_file($_FILES["paymentRefPic"]["tmp_name"], $targetPath2);
    // Insert customer information into database
    $customerID = $lastID + 1;
    //customerID is actualy a counter
    $query2 = "INSERT INTO benneks.customers(customerID, customerName, customerTel) VALUES ('$customerID', 'نامشخص', 'نامشخص')";
    if (!$user->executeQuery($query2)) {
        $flag = false;
        echo "error2";
        echo mysqli_error($user->conn);
    }
    // order id which is a PK is made by combining userID and customerID
    $userID = $_SESSION['user'];
    $orderID = intval(strval($userID) . strval($customerID));
    //Insert order information into database orderID is a combination of customerID and userID
    $orderDate = date("Y-m-d");
    $orderTime = date("H:i:s");
    $clothesType = $_POST['clothesType'];
    $productGender = $_POST['productGender'];
    $productBrand = $_POST['productBrand'];
    $productSize = $_POST['productSize'];
    $productColor = $_POST['productColor'];
    $productLink = $_POST['productLink'];
    $productPrice = $_POST['productPrice'];
    $benneksPrice = intval($_POST['benneksPrice']);
    $country = $_POST['country'];
    $currency = $_POST['currency'];
    $rateTL = intval($_POST['currencyRate']);
    $originalTomanPrice = intval($productPrice) * $rateTL;
    $productWeight = $_POST['productWeight'];
    $benneksMargin = $_POST['benneksMargin'];
    $iranDeliverCost = $_POST['iranDeliverCost'];
// variable to hold transaction and customer information
    $customerTel = $_POST['customerTel'];
    $customerCode = $userID . doubleval($customerTel);
    //$customerCode = 19121324660;
    $orderSalePrice = $_POST['orderSalePrice'];
    $advancedPayment = $_POST['advancedPayment'];
    $paymentExtraDesc = $_POST['paymentExtraDesc'];
    $purchaseID = time() + rand(1, 100);
    // user directory needs to be added before pic name
    $productPic = $targetPath1;
    $paymentRefPic = $targetPath2;
    // insert purchase information to purchaseinfo table
    $purchaseQuery = "INSERT INTO benneks.purchaseInfo(purchaseID, orderSalePrice, advancedPayment, paymentRefPic, paymentExtraDesc) VALUES ('$purchaseID', '$orderSalePrice', '$advancedPayment', '$paymentRefPic', '$paymentExtraDesc')";
    if (!$user->executeQuery($purchaseQuery)) {
        $flag = false;
        echo "error purchase info";
        echo mysqli_error($user->conn);
    }
    // If mistakes happened and zero inserted into quantity field, it will change it to one. 
    $orderQuantity = 1;
    $query3 = "INSERT INTO benneks.orders(orderID, users_userID, customers_customerID, orderDate, orderTime, clothesType, productGender, productBrand, productSize, productColor, productLink,  productPrice, productPic, orderQuantity, country, productsWeight, purchaseInfo_purchaseID, members_customerCode) "
            . "values('$orderID' ,(SELECT userID FROM benneks.users where userID='$userID'), (SELECT customerID FROM benneks.customers where customerID='$customerID'), '$orderDate', '$orderTime', '$clothesType',"
            . "'$productGender' ,'$productBrand', '$productSize', '$productColor',  '$productLink', '$productPrice', '$productPic', '$orderQuantity', '$country', '$productWeight', '$purchaseID', '$customerCode')";
    if (!$user->executeQuery($query3)) {
        $flag = false;
        echo mysqli_error($user->conn);
    }
    // once an order submited, we nedd to create three records in shipment, status and cost table for this order
    $query4 = "INSERT INTO benneks.shipment(orders_orderID) VALUES ('$orderID')";
    $query5 = "INSERT INTO benneks.stat(orders_orderID) VALUES ('$orderID')";
    $query6 = "INSERT INTO benneks.cost(orders_orderID, rateTL, benneksPrice, originalTomanPrice, currency, benneksMargin, iranDeliverCost) VALUES ('$orderID', '$rateTL' ,'$benneksPrice', '$originalTomanPrice', '$currency', '$benneksMargin', '$iranDeliverCost')";
    if (($user->executeQuery($query4)) && ($user->executeQuery($query5)) && ($user->executeQuery($query6))) {
        $flag = true;
    } else {
        $flag = false;
        echo mysqli_error($user->conn);
    }

    // if all queries executed properly then comit the changes in to database otherwise roll back all changes
    if ($flag) {
        mysqli_commit($user->conn);
        $string = "سفارش شما با کد " . "$orderID" . " در سیستم ثبت گردید.";
        echo '<script type="text/javascript">' . 'alert("' . $string . '"); </script>';
    } else {
        mysqli_rollback($user->conn);
        echo "سیستم دچار اختلال در ورود اطلاعات گردیده است لطفا با مدیر  تماس برقرار نمایید";
    }
    mysqli_close($user->conn);
}
?>
<html>
    <head>
        <meta charset = "utf-8">
        <meta http-equiv = "X-UA-Compatible" content = "IE=edge">
        <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
        <meta name = "description" content = "benneks control panel">
        <meta name = "author" content = "hamed">

        <!--Bootstrap -->
        <link rel = "stylesheet" href = "http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />

        <!--JQuery -->
        <script src = "https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"/></script>
    <!-- MetisMenu CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.5.2/metisMenu.min.css"/>

    <!--Bootstrap Again -->
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"/></script>

<!--Javascript src -->
<script type="text/javascript" src="./Javascripts/script.js"></script>

<!--Farsi Font-->
<link rel="stylesheet" href="http://ifont.ir/apicode/33">

<!--CSS Style-->
<link rel="stylesheet" type="text/css" href="style.css" />

<!-- Custom Fonts -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">


<title>Benneks Order System</title>
</head>
<body>
    <div id ="wrapper">
        <!-- Navigation Bar -->
        <div class="navbar navbar-default navbar-static-top farsi" role = "navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="home.php">Benneks Control Panel</a>
            </div>

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse collapse">
                    <ul class="nav in" id="side-menu">
                        <li>
                            <a href="home.php#orderPanel"> <i class="fa fa-tags fa-fw"></i>سفارش گذاری </a>
                        </li>
                        <li>
                            <a href="calculator.php"> <i class="fa fa-calculator fa-fw"></i> ماشین حساب </a>
                        </li>
                        <li>
                            <a href="orderlist.php"> <i class="fa fa-th-list fa-fw"> </i> لیست سفارشات </a>
                        </li>
                        <li>
                            <a href="#printKargoModal" data-toggle='modal' data-target='#printKargoModal' class='open-printKargoModal'> <i class="fa fa-print fa-fw"> </i> پرینت کارگو </a>
                        </li>
                        <li>
                            <a href="#printCustomerExcelModal" data-toggle='modal' data-target='#printCustomerExcelModal' class='open-printCustomerExcelModal'> <i class="fa fa-file-excel-o fa-fw" > </i> اکسل فروش</a>
                        </li>
                        <li>
                            <a href="customersOrderList.php"> <i class="fa fa-list fa-fw" > </i> سفارشات مشتریان</a>
                        </li>
                        <li>
                            <a href="logout.php?logout"> <i class="fa fa-sign-out fa-fw" > </i> خروج</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div id ="page-wrapper" class="farsi">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header" dir="rtl">پنل کاربری</h1>
                </div>
                <!-- /.row -->
                <div class="row" dir="rtl">
                    <div class="col-lg-6"></div>
                    <div class="col-lg-6" dir="rtl" id="orderPanel">
                        <div class="panel panel-default" dir="rtl" >
                            <div class="panel-heading">
                                <i class="fa fa-shopping-bag fa-fw"></i> ثبت سفارش 
                            </div>
                            <!-- /.new-order-panel-heading -->

                            <div class="panel-body">
                                <div class="col-lg-6 col-lg-push-6" >
                                    <form role = "form" method="post" name="orderForm" id="orderForm" onsubmit="return validateForm();" enctype="multipart/form-data">
                                        <fieldset>
                                            <legend> اطلاعات مشتری</legend>
                                            <div class="form-group">
                                                <label for="customerName">نام مشتری:</label>
                                                <input type="text" dir="rtl" class="form-control eng-format" id="customerName" name="customerName">
                                            </div>
                                            <div class = "form-group">
                                                <label for="customerTel">تلفن مشتری:</label>
                                                <input type = "text" class = "form-control eng-format" dir="ltr" id = "customerTel" name = "customerTel" maxlength="11" onkeyup="checkTel()" placeholder = " موبایل مشتری به عنوان کد">
                                            </div>
                                            <div class="form-group">
                                                <span style="color:red" id="telAlert">
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label for="customerSocialLink">نوع ارتباط:</label>
                                                <select dir="rtl" class="form-control" id="customerSocialLink" name ="customerSocialLink">
                                                    <option value="تلگرام" selected> تلگرام </option>
                                                    <option value="اینستاگرام"> اینستاگرام</option>
                                                    <option value="واتس اپ"> واتس اپ</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="customerSocialID">آیدی:</label>
                                                <input type="text" dir="rtl" class="form-control eng-format" id="customerSocialID" name="customerSocialID">
                                            </div>
                                            <button type="button" class="form-control btn btn-group btn-success" id="memberSubmitButton" name="memberSubmitButton" onclick="addMemberFunc('add');" > ثبت مشتری 
                                                <span>
                                                    <i class="fa fa-plus"> </i>
                                                </span>
                                            </button>
                                            <div class="form-group">
                                                <span style="color:red; text-align: justify; font-size: 12" id="memberMsg">
                                                </span>
                                            </div>  
                                        </fieldset>
                                        <fieldset>
                                            <legend>اطلاعات سفارش</legend>
                                            <div class="form-group">
                                                <label for="country"> کشور </label>
                                                <select dir="rtl" class="form-control" id="country" name ="country">
                                                    <option value="ترکیه" selected> ترکیه </option>
                                                    <option value="انگلیس"> انگلیس </option>
                                                    <option value="فرانسه"> فرانسه </option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <input type="radio" name="productGender" id="productGender" value="female" checked> زنانه
                                                <input type="radio" name="productGender" id="productGender" value="male" > مردانه  
                                            </div>
                                            <div class="form-group">
                                                <label for="clothesType"> نوع لباس:</label>
                                                <select dir = "rtl" class="form-control" id = "clothesType" name="clothesType" onchange="showRealPrice()">
                                                    <option value="" disabled selected style="color: red">انواع لباس زنانه:</option>
                                                    \\Women bag and shoes
                                                    <option value = "کیف زنانه"> انواع کیف زنانه </option>
                                                    <option value = "کفش زنانه"> انواع کفش و بوت ودمپایی زنانه</option>
                                                    \\unisex products around 120 gr                         
                                                    <option value = "کیف پول"> کیف پول مردانه یا زنانه </option> 
                                                    <option value = "کمربند"> کمربند مردانه یا زنانه </option>
                                                    <option value = "عینک"> عینک  مردانه یا زنانه</option>
                                                    <option value = "عطر"> عطر مردانه یا زنانه </option>
                                                    <option value = "ساعت"> ساعت  مردانه یا زنانه</option>
                                                    <option value = "زیورآلات"> اکسسوری مردانه یا زنانه </option>
                                                    <option value = "جوراب"> جوراب زنانه یا مردانه </option>
                                                    \\Women products around 200 gr
                                                    <option value = "شلوارک زنانه"> شلوارک  زنانه</option> 
                                                    <option value = "تاپ زنانه"> تاپ زنانه </option> 
                                                    <option value = "لباس زیر زنانه"> لباس زیر  زنانه</option> 
                                                    <option value = "تی شرت زنانه"> تی شرت زنانه </option> 
                                                    <option value = "کلاه، شال، روسری، دستکش"> انواع شال، کلاه، روسری و دستکش </option> 
                                                    <option value = "بیکینی"> مایو زنانه </option> 
                                                    <option value = "رو مایو زنانه">  رو مایو زنانه </option> 
                                                    <option value = "لباس خواب زنانه"> لباس خواب  زنانه</option> 
                                                    <option value = "ساپورت زنانه"> ساپورت  زنانه</option> 
                                                    <option value = "بلوز زنانه"> بلوز زنانه </option>  
                                                    <option value = "دامن زنانه"> دامن زنانه </option>
                                                    \\Women products around 300 gr
                                                    <option value = "کاردیگان سبک زنانه"> کاردیگان سبک زنانه</option>
                                                    <option value = "پلیورهای نازک زنانه">  پلیورهای نازک زنانه</option>
                                                    <option value = "شلوار معمولی زنانه"> شلوار معمولی  زنانه</option> 
                                                    <option value = "سرهمی زنانه"> سرهمی  زنانه</option>                
                                                    <option value = "پانچو">  پانچو  زنانه</option>
                                                    <option value = "مانتو زنانه"> مانتو  زنانه</option>
                                                    <option value = "پیراهن زنانه"> پیراهن زنانه </option>
                                                    <option value = "سوئیت شرت زنانه"> سوئیت شرت زنانه </option>
                                                    \\Women products around 400 gr
                                                    <option value = "کاردیگان سنگین زنانه"> کاردیگان سنگین زنانه</option>
                                                    <option value = "جین زنانه"> شلوار جین زنانه </option>
                                                    <option value = "بارونی زنانه"> بارونی  زنانه</option>
                                                    <option value = "کت تابستانی زنانه"> انواع کت های تابستانی زنانه </option>
                                                    \\Women products around 600 gr
                                                    <option value = "کت زمستانی زنانه"> کت زمستانی زنانه</option>
                                                    <option value = "کاپشن سبک زنانه"> کاپشن سبک  زنانه</option>
                                                    <option value = "کت جین زنانه"> کت جین  زنانه</option>
                                                    <option value = "کت چرم زنانه"> کت چرم  زنانه</option>
                                                    <option value = "پلیور سنگین زنانه"> پلیورهای سنگین زنانه</option>
                                                    <option value = "پالتو زنانه سبک"> پالتو سبک  زنانه</option>
                                                    \\Women products 800 gr
                                                    <option value = "کت جیر زنانه"> کت جیر زنانه</option>
                                                    <option value = "پالتو زنانه سنگین"> پالتو سنگین زنانه</option>
                                                    <option value = "گرمکن زنانه"> ست گرمکن زنانه</option>
                                                    <option value = "کاپشن سنگین زنانه"> کاپشن سنگین  زنانه</option>
                                                    \\ Women products more than 1 kg
                                                    <option value = "سنگین زنانه"> کاپشن و کت و پالتو های سنگین و ضخیم  زنانه</option>
                                                    <option value = "سنگین زنانه">انواع کت دامن زنانه</option>
                                                    <option value = "سنگین زنانه"> انواع کت شلوار زنانه</option>
                                                    \\\\\Man Products
                                                    <option value="" disabled style="color:red">انواع لباس مردانه:</option>
                                                    \\ Man bag & shoes
                                                    <option value = "کفش مردانه"> انواع کفش و بوت ودمپایی مردانه </option>
                                                    <option value = "کیف مردانه"> کیف دستی مردانه </option>
                                                    \\ Man products around 200 gr
                                                    <option value = "پیراهن مردانه"> پیراهن مردانه </option>
                                                    <option value = "تی شرت مردانه"> تی شرت مردانه </option>
                                                    <option value = "لباس زیر مردانه"> لباس زیر مردانه </option>
                                                    <option value = "شلوارک مردانه"> شلوارک مردانه </option>
                                                    <option value = "مایو مردانه"> مایو مردانه</option>
                                                    \\ Man products around 450 gr
                                                    <option value = "شلوار معمولی مردانه"> شلوار معمولی مردانه </option>
                                                    <option value = "شلوار جین مردانه"> شلوار جین مردانه </option>     
                                                    <option value = "پلیور مردانه سبک"> پلیور مردانه سبک </option>
                                                    \\Man products around 600 gr
                                                    <option value = "کت معمولی مردانه"> کت معمولی مردانه</option>
                                                    \\Man products around 800 gr
                                                    <option value = "گرمکن مردانه"> ست گرمکن مردانه</option>
                                                    <option value = "کت جین مردانه"> کت جین مردانه</option>
                                                    <option value = "پلیور مردانه سنگین"> پلیور مردانه سنگین </option>
                                                    <option value = "کاپشن سبک مردانه"> کاپشن سبک مردانه </option>
                                                    \\Man product more than 1 kg
                                                    <option value = "کاپشن سنگین مردانه"> کاپشن سنگین مردانه</option>
                                                    <option value = "کت چرم مردانه"> کت چرم مردانه</option>
                                                    <option value = "پالتو مردانه"> پالتو مردانه</option>
                                                    <option value = "اورکت مردانه"> اورکت مردانه</option>
                                                    <option value = "کت شلوار مردانه"> کت شلوار مردانه</option>
                                                    \\KIDS Shoes and bags
                                                    <option value="" disabled style="color: red">انواع لباس بچه گانه:</option>
                                                    <option value = "کفش بچه گانه"> کفش و دمپایی و صندل بچه گانه</option>
                                                    <option value = "کیف بچه گانه"> انواع کیف بچه گانه</option>
                                                    \\KIDS Accessories 120 gr
                                                    <option value = "انواع کلاه بچه گانه"> انواع کلاه بچه گانه</option>
                                                    <option value = "جوراب و لباس زیر بچه گانه"> جوراب و لباس زیر بچه گانه</option>
                                                    <option value = "اسباب بازی بچه گانه"> اسباب بازی بچه گانه</option>
                                                    \\KIDS products equal to 200 gram
                                                    <option value = "بلوز شلوار ست بچه گانه"> بلوز شلوار ست بچه گانه</option>
                                                    <option value = "تی شرت بچه گانه"> تی شرت بچه گانه</option>
                                                    <option value = "سرهمی بچه گانه"> سرهمی بچه گانه</option>
                                                    <option value = "لباس خواب بچه گانه"> لباس خواب بچه گانه</option>
                                                    <option value = "شلوارک جین بچه گانه"> شلوارک جین بچه گانه</option>
                                                    <option value = "مایو بچه گانه"> مایو بچه گانه</option>
                                                    <option value = "پلیور بچه گانه"> پلیور بچه گانه</option>
                                                    <option value = "پیراهن بچه گانه"> پیراهن بچه گانه</option>
                                                    <option value = "دامن بچه گانه"> دامن بچه گانه</option>
                                                    \\KIDS products equal to 450 gram  
                                                    <option value = "کاپشن بچه گانه"> کاپشن بچه گانه</option>
                                                    <option value = "گرمکن بچه گانه"> گرمکن بچه گانه</option>
                                                    \\Baby clothes all 120 gram
                                                    <option value = "تمامی لباس های نوزادی"> تمامی لباس های نوزادی</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="productBrand"> نام برند:</label>
                                                <select dir = "rtl" class="form-control" id = "productBrand" name = "productBrand">
                                                    <option value="" disabled selected>نام برند را انتخاب کنید</option>
                                                    <option value="Trendyol"> Trendyol </option>
                                                    <option value="Stradivarius"> Stradivarius </option>
                                                    <option value="H & M "> H & M </option>
                                                    <option value="Vepa"> Vepa</option>
                                                    <option value="Morhipo"> Morhipo </option>
                                                    <option value="Patirti"> Patirti </option>
                                                    <option value="Aldo"> Aldo </option>
                                                    <option value="Hotic"> Hotic </option>
                                                    <option value="Bonprix"> Bonprix </option>
                                                    <option value="Brandroom"> Brandroom </option>
                                                    <option value="Defacto"> Defacto </option>
                                                    <option value="Delisiyim"> Delisiyim </option>
                                                    <option value="LCWALKIKI"> LCWALKIKI </option>
                                                    <option value="Inci"> Inci </option>
                                                    <option value="Koton"> Koton </option>
                                                    <option value="Oysho"> Oysho </option>
                                                    <option value="Saat ve Saat"> Saat ve Saat </option>
                                                    <option value="Zara"> Zara </option>
                                                    <option value="Mango"> Mango </option>
                                                    <option value="Breshka"> Breshka </option>
                                                    <option value="Pull&Bear"> Pull&Bear </option>
                                                    <option value="Micheal Kors"> Michael Kors </option>
                                                    <option value="Network"> Network </option>
                                                    <option value="Fabrika"> Fabrika </option>
                                                    <option value="Massimo Dutti"> Massimo Dutti </option>
                                                    <option value="Polo"> Polo </option>
                                                    <option value="Nike"> Nike </option>
                                                    <option value="Adidas"> Adidas </option>
                                                    <option value="Puma"> Puma </option>
                                                    <option value="Guess"> Guess </option>
                                                    <option value="Gucci"> Gucci </option>
                                                    <option value="Versace"> Versace </option>
                                                    <option value="Ralph Lauren"> Ralph Lauren </option>
                                                    <option value="Mavi"> Mavi </option>
                                                    <option value="Koton"> Koton </option>
                                                    <option value="Colins"> Colins </option>
                                                    <option value="Victoria Secret"> Victoria Secret </option>
                                                    <option value="Instagram"> Instagram </option>
                                                    <option value="Others"> Others </option>
                                                    <option value="UkSites"> UK Sites </option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="productLink"> لینک محصول :</label>
                                                <input type="text" dir="ltr" class="form-control eng-format" id="productLink" name="productLink">
                                            </div>
                                            <div class="form-group">
                                                <label for="productPic"> عکس:</label>
                                                <input type="file" class="eng-format" id="productPic" name = "productPic" accept="image/*">
                                            </div>
                                            <div class="form-group">
                                                <label for="productSize"> سایز:</label>
                                                <select dir = "ltr"  class="eng-format form-control" id = "productSize" name = "productSize">
                                                    <option value="" disabled selected style="color: red">  سایزهای لباس زنانه انتخاب کنید </option>
                                                    <option value="Women-XXSmall"> XX-Small </option>
                                                    <option value="Women-XSmall"> X-Small </option>
                                                    <option value="Women-Small"> Small </option>
                                                    <option value="Women-Medium"> Medium </option>
                                                    <option value="Women-Large"> Large</option>
                                                    <option value="Women-XLarge"> X-Large</option>
                                                    <option value="Women-XXLarge"> XX-Large</option>
                                                    <option value="Women-32"> 32</option>
                                                    <option value="Women-34"> 34</option>
                                                    <option value="Women-36"> 36</option>
                                                    <option value="Women-38"> 38</option>
                                                    <option value="Women-40"> 40</option>
                                                    <option value="Women-42"> 42</option>
                                                    <option value="Women-44"> 44</option>
                                                    <option value="Women-46"> 46</option>
                                                    <option value="" disabled style="color: red">  سایزهای انگلیس</option>
                                                    <option value="UK-3"> UK-3</option>
                                                    <option value="UK-3.5"> UK-3.5</option>
                                                    <option value="UK-4"> UK-4</option>
                                                    <option value="UK-4.5"> UK-4.5</option>
                                                    <option value="UK-5"> UK-5</option>
                                                    <option value="UK-5.5"> UK-5.5</option>
                                                    <option value="UK-6"> UK-6</option>
                                                    <option value="UK-6.5"> UK-6.5</option>
                                                    <option value="UK-7"> UK-7</option>
                                                    <option value="UK-7.5"> UK-7.5</option>
                                                    <option value="UK-8"> UK-8</option>
                                                    <option value="UK-8.5"> UK-8.5</option>
                                                    <option value="UK-9"> UK-9</option>
                                                    <option value="UK-9.5"> UK-9.5</option>
                                                    <option value="UK-10"> UK-10</option>
                                                    <option value="UK-10.5"> UK-10.5</option>
                                                    <option value="UK-11"> UK-11</option>
                                                    <option value="UK-11.5"> UK-11.5</option>
                                                    <option value="UK-12"> UK-12</option>
                                                    <option value="UK-12.5"> UK-12.5</option>
                                                    <option value="UK-14"> UK-14</option>
                                                    <option value="UK-14.5"> UK-14.5</option>
                                                    <option value="UK-16"> UK-16</option>
                                                    <option value="UK-16.5"> UK-16.5</option>
                                                    <option value="UK-18"> UK-18</option>
                                                    <option value="UK-18.5"> UK-18.5</option>
                                                    <option value="UK-20"> UK-20</option>
                                                    <option value="UK-20.5"> UK-20.5</option>
                                                    <option value="UK-22"> UK-22</option>
                                                    <option value="UK-22.5"> UK-22.5</option>
                                                    <option value="Women-Free-Size"> فری سایز زنانه </option>
                                                    <option value="" disabled style="color: red">  سایزهای کفش زنانه</option>
                                                    <option value="Shoes-35"> Shoes(EU=35) </option>
                                                    <option value="Shoes-35.5"> Shoes(EU=35.5) </option>
                                                    <option value="Shoes-36"> Shoes(EU=36) </option>
                                                    <option value="Shoes-36.5"> Shoes(EU=36.5) </option>
                                                    <option value="Shoes-37"> Shoes(EU=37) </option>
                                                    <option value="Shoes-37.5"> Shoes(EU=37.5) </option>
                                                    <option value="Shoes-38"> Shoes(EU=38) </option>
                                                    <option value="Shoes-37"> Shoes(EU=38.5) </option>
                                                    <option value="Shoes-39"> Shoes(EU=39) </option>
                                                    <option value="Shoes-39"> Shoes(EU=39.5) </option>
                                                    <option value="Shoes-40"> Shoes(EU=40) </option>
                                                    <option value="Shoes-40.5"> Shoes(EU=40.5) </option>
                                                    <option value="Shoes-41"> Shoes(EU=41)</option>
                                                    <option value="Shoes-41.5"> Shoes(EU=41.5)</option>
                                                    <option value="Shoes-42"> Shoes(EU=42)</option>
                                                    <option value="Shoes-42.5"> Shoes(EU=42.5)</option>
                                                    <option value="" disabled style="color: red">سایزهای مردانه</option>
                                                    <option value="Men-XXSmall"> XX-Small</option>
                                                    <option value="Men-XSmall"> X-Small</option>
                                                    <option value="Men-Small"> Small</option>
                                                    <option value="Men-Medium"> Medium</option>
                                                    <option value="Men-Large"> Large</option>
                                                    <option value="Men-XLarge"> X-Large</option>
                                                    <option value="Men-XXLarge"> XX-Large</option>
                                                    <option value="Men-XXXLarge"> XXX-Large</option>
                                                    <option value="Men-36"> 36</option>
                                                    <option value="Men-38"> 38</option>
                                                    <option value="Men-40"> 40</option>
                                                    <option value="Men-42"> 42</option>
                                                    <option value="Men-44"> 44</option>
                                                    <option value="Men-46"> 46</option>
                                                    <option value="Men-48"> 48</option>
                                                    <option value="Men-50"> 50</option>
                                                    <option value="Men-52"> 52</option>
                                                    <option value="Men-54"> 54</option>
                                                    <option value="Men-56"> 56</option>
                                                    <option value="" disabled style="color: red">  سایزهای شلوار مردانه</option>
                                                    <option value="Men-Trouser-W30"> 30</option>
                                                    <option value="Men-Trouser-W31"> 31</option>
                                                    <option value="Men-Trouser-W32"> 32</option>
                                                    <option value="Men-Trouser-W33"> 33</option>
                                                    <option value="Men-Trouser-W34"> 34</option>
                                                    <option value="Men-Trouser-W35"> 35</option>
                                                    <option value="Men-Trouser-W26:L30"> W:26 L:30</option>
                                                    <option value="Men-Trouser-W26:L32"> W:26 L:32</option>
                                                    <option value="Men-Trouser-W26:L34"> W:26 L:34</option>
                                                    <option value="Men-Trouser-W28:L30"> W:28 L:30</option>
                                                    <option value="Men-Trouser-W28:L32"> W:28 L:32</option>
                                                    <option value="Men-Trouser-W28:L34"> W:28 L:34</option>
                                                    <option value="Men-Trouser-W30:L30"> W:30 L:30</option>
                                                    <option value="Men-Trouser-W30:L32"> W:30 L:32</option>
                                                    <option value="Men-Trouser-W30:L34"> W:30 L:34</option>
                                                    <option value="Men-Trouser-W31:L30"> W:31 L:30</option>
                                                    <option value="Men-Trouser-W31:L32"> W:31 L:32</option>
                                                    <option value="Men-Trouser-W31:L34"> W:31 L:34</option>
                                                    <option value="Men-Trouser-W32:L30"> W:32 L:30</option>
                                                    <option value="Men-Trouser-W32:L32"> W:32 L:32</option>
                                                    <option value="Men-Trouser-W32:L34"> W:32 L:34</option>
                                                    <option value="Men-Trouser-W34:L30"> W:34 L:30</option>
                                                    <option value="Men-Trouser-W34:L32"> W:34 L:32</option>
                                                    <option value="Men-Trouser-W34:L34"> W:34 L:34</option>
                                                    <option value="Men-Trouser-W36:L30"> W:36 L:30</option>
                                                    <option value="Men-Trouser-W36:L32"> W:36 L:32</option>
                                                    <option value="Men-Trouser-W36:L34"> W:36 L:34</option>
                                                    <option value="Men-Trouser-W38:L30"> W:38 L:30</option>
                                                    <option value="Men-Trouser-W38:L32"> W:38 L:32</option>
                                                    <option value="Men-Trouser-W38:L34"> W:38 L:34</option>
                                                    <option value="Men-Free-Size"> فری سایز مردانه </option>
                                                    <option value="" disabled style="color: red">  سایزهای کفش مردانه</option>
                                                    <option value="Shoes-38"> Shoes(EU=38) </option>
                                                    <option value="Shoes-39"> Shoes(EU=39) </option>
                                                    <option value="Shoes-40"> Shoes(EU=40) </option>
                                                    <option value="Shoes-40.5"> Shoes(EU=40.5) </option>
                                                    <option value="Shoes-41"> Shoes(EU=41) </option>
                                                    <option value="Shoes-41.5"> Shoes(EU=41.5) </option>
                                                    <option value="Shoes-42"> Shoes(EU=42)</option>
                                                    <option value="Shoes-42.5"> Shoes(EU=42.5)</option>
                                                    <option value="Shoes-43"> Shoes(EU=43)</option>
                                                    <option value="Shoes-43.5"> Shoes(EU=43.5)</option>
                                                    <option value="Shoes-44"> Shoes(EU=44)</option>
                                                    <option value="Shoes-44.5"> Shoes(EU=44.5)</option>
                                                    <option value="Shoes-45"> Shoes(EU=45)</option>
                                                    <option value="Shoes-46"> Shoes(EU=46)</option>
                                                    <option value="" disabled style="color: red">  سایزاهای بچه گانه</option>
                                                    <option value="just-born"> نوزادی</option>
                                                    <option value="0-3 months"> 0-3 Months </option>
                                                    <option value="3-6 months"> 3-6 months </option>
                                                    <option value="6-7 months"> 6-7 months </option>
                                                    <option value="8 months"> 8 months </option>
                                                    <option value="9 months"> 9 months </option>
                                                    <option value="10-11-12 months"> 10-11-12 months </option>
                                                    <option value="1-2 years"> 1-2 years </option>
                                                    <option value="2-4 years"> 2-4 years </option>
                                                    <option value="4-6 years"> 4-6 years </option>
                                                    <option value="6-8 years"> 6-8 years </option>
                                                    <option value="8-10 years"> 8-10 years </option>
                                                    <option value="10-12 years"> 10-12 years </option>
                                                    <option value="12-14 years"> 12-14 years </option>
                                                    <option value="14-16 years"> 14-16 years </option>
                                                    <option value="Kids-xxs"> Kids-xxs </option>
                                                    <option value="Kids-xs"> Kids-xs </option>
                                                    <option value="Kids-s"> Kids-s </option>
                                                    <option value="Kids-m"> Kids-m </option>
                                                    <option value="Kids-l"> Kids-l </option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="productColor">  رنگ:</label>
                                                <select dir = "rtl"  class="form-control" id = "productColor" name = "productColor">
                                                    <option value="" disabled selected>  انتخاب رنگ</option>
                                                    <option value="سفید(beyaz)">  سفید</option>
                                                    <option value="مشکی(siyah)">  مشکی</option>
                                                    <option value="سبز(yeşil)">  سبز</option>
                                                    <option value="آبی(mavi)">  آبی</option>
                                                    <option value="قرمز(kırmızı)">  قرمز</option>
                                                    <option value="زرد(Sarı)">  زرد</option>
                                                    <option value="قهوه ای(kahverengi)">  قهوه ای</option>
                                                    <option value="طوسی(gri)">  طوسی</option>
                                                    <option value="سرمه ای(lacivert)">  سرمه ای</option>
                                                    <option value="بنفش(mor)">  بنفش</option>
                                                    <option value="نقره ای(gümüş)">  نقره ای</option>
                                                    <option value="زرشکی(kıpkırmızı)">  زرشکی</option>
                                                    <option value="کرم(bej)">  کرم</option>
                                                    <option value="صورتی(pembe)">  صورتی</option>
                                                    <option value="طلایی(altın)">  طلایی</option>
                                                    <option value="نارنجی(Portakal)">  نارنجی</option>
                                                    <option value="بژ(bej)">  بژ</option>
                                                    <option value="سرخ آبی(karanfıl)">  سرخ آبی</option>
                                                    <option value="یشمی(Viridian)">  یشمی</option>
                                                    <option value="لیمویی(limon)">  لیمویی</option>
                                                    <option value="چند رنگ(renkli">  چند رنگ</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="productPrice"> قیمت اصلی :</label>
                                                <input type="text" class="form-control eng-format" dir="ltr" maxlength="8" onkeyup="checkPrice(); showRealPrice();" id="productPrice" name = "productPrice">
                                            </div>                 
                                            <div class="form-group">
                                                <span style="color:red" id="priceAlert">
                                                </span>
                                            </div>
                                            <div class = "form-group">
                                                <label for = "benneksPrice"> قیمت فروش سیستم :</label>
                                                <input type = "text" class = "form-control eng-format" dir="ltr" id = "benneksPrice" name = "benneksPrice" readonly="readonly" placeholder = "قیمت فروش سیستم ">
                                            </div>
                                        </fieldset>
                                        <fieldset>
                                            <legend> اطلاعات فروش</legend>
                                            <div class="form-group">
                                                <label for="orderSalePrice">قیمت فروش به مشتری:</label>
                                                <input type="text" dir="rtl" class="form-control eng-format" id="orderSalePrice" name="orderSalePrice">
                                            </div>
                                            <div class="form-group">
                                                <label for="advancedPayment">مبلغ بیعانه از مشتری:</label>
                                                <input type="text" dir="rtl" class="form-control eng-format" id="advancedPayment" name="advancedPayment">
                                            </div>
                                            <div class="form-group">
                                                <label for="paymentRefPic"> عکس:</label>
                                                <input type="file" class="eng-format" id="paymentRefPic" name = "paymentRefPic" accept="image/*">
                                                </di
                                                <div class="form-group">
                                                    <label for="paymentExtraDesc">توضیحات:</label>
                                                    <textarea rows="4" cols="50" dir="rtl" class="form-control eng-format" id="paymentExtraDesc" name="paymentExtraDesc"> </textarea>
                                                </div>
                                        </fieldset>
                                        <div class="form-group">
                                            <input type="hidden" id="currency" name="currency">
                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" id="currencyRate" name="currencyRate" >
                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" id="productWeight" name="productWeight" >
                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" id="benneksMargin" name="benneksMargin" >
                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" id="iranDeliverCost" name="iranDeliverCost" >
                                        </div>
                                        <!-- to get userID for different prices for different users based on js file -->
                                        <div class="form-group">
                                            <input type="hidden" id="userID" name="userID" value="<?php echo $_SESSION['user']; ?>" >
                                        </div>
                                        <!-- javascript to pass variables to calculator() in script.js file -->
                                        <script>
                                            function showRealPrice() {
                                                var userID = document.getElementById("userID").value;
                                                var country = document.getElementById("country").value;
                                                var clothesType = document.getElementById("clothesType").value;
                                                var productPrice = document.getElementById("productPrice").value;
                                                var orderDetailsVar = calculator(userID, country, clothesType, productPrice);
                                                document.getElementById("currency").value = orderDetailsVar.currency;
                                                document.getElementById("currencyRate").value = orderDetailsVar.currencyRate;
                                                document.getElementById("benneksPrice").value = orderDetailsVar.totalCost;
                                                document.getElementById("productWeight").value = orderDetailsVar.productWeight;
                                                document.getElementById("benneksMargin").value = orderDetailsVar.benneksMargin;
                                                document.getElementById("iranDeliverCost").value = orderDetailsVar.iranDeliverCost;
                                            }
                                        </script>

                                        <button class="form-control btn btn-group btn-primary" id="submitOrderButton" name="submitOrderButton" disabled="true"> ثبت سفارش 
                                            <span>
                                                <i class="fa fa-plus"> </i>
                                            </span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.new-order-panel-body -->
                </div>
                <!--new-order-panel-->
                <!--print kargo modal -->
                <div class = "modal fade" id = "printKargoModal" role="dialog">
                    <div class="modal-dialog">
                        <!--modal content -->
                        <div class="modal-content">
                            <div class="modal-header" style="padding: 35px 50px;">
                                <button type="button" class="close" data-dismiss = "modal">&times; </button>
                                <h4><span class = "glyphicon glyphicon-print"> </span> پرینت کارگو</h4>
                            </div>
                            <div class="modal-body" style="padding:40px 50px;">
                                <form role="form" action="printUsersKargo.php" method="post" dir="rtl">
                                    <div class="form-group">
                                        <label for="kargoID"> <span class="glyphicon glyphicon-asterisk"></span> کد کارگو</label>
                                        <input type="text" class="form-control" name="kargoID" id="kargoID">
                                    </div>
                                    <button type="submit" class="btn btn-success btn-block" name="submitButton" id="submitButton"> پرینت </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- customer excel modal -->
                <div class = "modal fade" id = "printCustomerExcelModal" role="dialog">
                    <div class="modal-dialog">
                        <!--modal content -->
                        <div class="modal-content">
                            <div class="modal-header" style="padding: 35px 50px;">
                                <button type="button" class="close" data-dismiss = "modal">&times; </button>
                                <h4><span class = "glyphicon glyphicon-print"> </span> گزارش اکسل فروش مشتریان</h4>
                            </div>
                            <div class="modal-body" style="padding:40px 50px;">
                                <form role="form" action="customerExcelFile.php" method="post" dir="rtl">
                                    <div class="form-group">
                                        <label for="startDate"> تاریخ شروع: </label>
                                        <input type="date" value="2018-05-04" class="form-control" name="startDate" id="startDate">
                                    </div>
                                    <div class="form-group">
                                        <label for="finishDate"> تاریخ پایان: </label>
                                        <input type="date" class="form-control" name="finishDate" id="finishDate">
                                    </div>
                                    <button type="submit" class="btn btn-success btn-block" name="submitButton" id="submitButton"> دریافت فایل اکسل </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
</body>
</html>

