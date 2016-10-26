<?php
ob_start();
session_start();
require 'src/benneks.php';
// if session is not set this will redirect to login page
if (!isset($_SESSION['user'])) {
    header("Location: register.php");
    exit();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$user = new user();
date_default_timezone_set("Asia/Tehran");
if (isset($_POST['submitOrderButton'])) {
    $customerName = $_POST['customerName'];
    $customerTel = $_POST['customerTel'];
//Need to get the last customerID from Database if it is a first record then LastID will be 0;
    $query = "SELECT customerID FROM benneks.customers ORDER BY customerID DESC LIMIT 1";
    if (!$user->executeQuery($query)) {
        echo mysqli_error($user->conn);
    } else {
        $lastCustomerIDResult = $user->executeQuery($query);
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
        $targetDir = $targetDir = 'orderpics/' . $userDir . "/";
    }
    $targetFile = $targetDir . basename($_FILES["productPic"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($targetFile, PATHINFO_EXTENSION);
    move_uploaded_file($_FILES["productPic"]["tmp_name"], $targetFile);
    $image = basename($_FILES["productPic"]["name"], ".jpg");
// Insert customer information into database
    $customerID = $lastID + 1;
    $query = "INSERT INTO benneks.customers(customerID, customerName, customerTel) VALUES ('$customerID', '$customerName', '$customerTel')";
    if (!$user->executeQuery($query)) {
        echo mysqli_errno($user->conn);
    }
//Insert order information into database orderID is a combination of customerID and userID
    $orderDate = date("Y-m-d");
    $orderTime = date("H:i:s");
    $userID = $_SESSION['user'];
    $orderID = intval(strval($userID) . strval($customerID));
    $clothesType = $_POST['clothesType'];
    $productBrand = $_POST['productBrand'];
    $productSize = $_POST['productSize'];
    $productLink = $_POST['productLink'];
    $productPrice = $_POST['productPrice'];
    $productPic = $image;
    $orderQuantity = $_POST['orderQuantity'];
    $query = "INSERT INTO benneks.orders(orderID, users_userID, customers_customerID, orderDate, orderTime, clothesType, productBrand, productSize, productLink, productPrice, productPic, orderQuantity) "
            . "values('$orderID' ,(SELECT userID FROM benneks.users where userID='$userID'), (SELECT customerID FROM benneks.customers where customerID='$customerID'), '$orderDate', '$orderTime', '$clothesType',"
            . " '$productBrand', '$productSize', '$productLink', '$productPrice', '$productPic', '$orderQuantity' )";
    if (!$user->executeQuery($query)) {
        echo mysqli_error($user->conn);
    }
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
<link rel="stylesheet" href="http://ifont.ir/apicode/30">

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
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav in" id="side-menu">
                        <li>
                            <a href="home.php"> <i class="fa fa-dashboard fa-fw"></i> داشبورد </a>
                        </li>
                        <li>
                            <a href="calculator.php"> <i class="fa fa-calculator fa-fw"></i> ماشین حساب </a>
                        </li>
                        <li>
                            <a href="orderlist.php"> <i class="fa fa-th-list fa-fw"> </i> لیست سفارشات </a>
                        </li>
                        <li>
                            <a href="#"> <i class="fa fa-area-chart fa-fw"> </i> گزارش مفصل </a>
                        </li>
                        <li>
                            <a href="#"> <i class="fa fa-money fa-fw" > </i> گزارش مالی</a>
                        </li>
                        <li>
                            <a href="#"> <i class="fa fa-gear fa-fw" > </i> تنظیمات پروفایل</a>
                        </li>
                        <li>
                            <a href="logout.php"> <i class="fa fa-sign-out fa-fw" > </i> خروج</a>
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
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-comments fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge"> 23 </div>
                                        <div> آخرین خرید های انجام شده </div>
                                    </div>
                                </div>
                            </div>
                            <a href="#">
                                <div class="panel-footer">
                                    <span class="pull-left"> مشاهده جزئیات </span>
                                    <span class="pull-right"> <i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"> </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-green">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-tasks fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge"> 12 </div>
                                        <div> جدیدترین سفارشات رسیده به استانبول </div>
                                    </div>
                                </div>
                            </div>
                            <a href="#">
                                <div class="panel-footer">
                                    <span class="pull-left"> مشاهده جزئیات</span>
                                    <span class="pull-right"> <i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"> </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-yellow">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-shopping-cart fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge"> 4 </div>
                                        <div> آخرین ارسالی ها به تهران</div>
                                    </div>
                                </div>
                            </div>
                            <a href="#">
                                <div class="panel-footer">
                                    <span class="pull-left"> مشاهده جزئیات </span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-red">
                            <div class="panel-heading"> 
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-support fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge"> 13 </div>
                                        <div> سفارشات کنسل شده </div>

                                    </div>
                                </div>
                            </div>
                            <a href="#">
                                <div class="panel-footer">
                                    <span class="pull-left"> مشاهده جزئیات </span>
                                    <span class="pull-right"> <i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>

                        </div>
                    </div>
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
                                    <form role = "form" method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="clothesType"> نوع لباس:</label>
                                            <select dir = "rtl" class="form-control" id = "clothesType" name="clothesType">
                                                <option value = "کیف"> انواع کیف </option>
                                                <option value = "نواع کفش و بوت"> انواع کفش و بوت </option>

                                                <option value = "کیف پول"> کیف پول </option> 
                                                <option value = "کمربند"> کمربند </option>
                                                <option value = "عینک"> عینک </option>
                                                <option value = "عطر"> عطر </option>
                                                <option value = "ساعت"> ساعت </option>
                                                <option value = "اکسسوری"> اکسسوری </option>

                                                <option value = "شمیز و سرهمی"> شمیز و سرهمی </option>
                                                <option value = "شلوارک"> شلوارک </option>
                                                <option value = "بلوز"> بلوز </option>
                                                <option value = "sweater"> پلیور </option>
                                                <option value = "پلیور"> تاپ </option>
                                                <option value = "دامن"> دامن </option>
                                                <option value = "پیراهن زنانه"> پیراهن زنانه </option>
                                                <option value = "پیراهن مردانه"> پیراهن مردانه </option>
                                                <option value = "پیراهن بلند زنانه"> پیراهن بلند زنانه </option>
                                                <option value = "لباس زیر"> لباس زیر </option>
                                                <option value = "تی شرت"> تی شرت </option>
                                                <option value = "انواع روسری و شال"> انواع روسری و شال </option>
                                                <option value = "مایو"> مایو </option>
                                                <option value = "رو مایو"> رو مایو </option>
                                                <option value = "لباس خواب"> لباس خواب </option>
                                                <option value = "ساپورت"> ساپورت </option>
                                                <option value = "پانچو">  پانچو </option>
                                                <option value = "شلوار معمولی"> شلوار معمولی </option>

                                                <option value = "کاردیگان"> کاردیگان</option>
                                                <option value = "مانتو"> مانتو </option>
                                                <option value = "بارونی"> بارونی </option>
                                                <option value = "انواع کت های جین و تابستانی"> انواع کت های جین و تابستانی </option>
                                                <option value = "شلوار جین"> شلوار جین </option>
                                                <option value = "کت و دامن به همراه هم"> کت و دامن به همراه هم </option>

                                                <option value = "کت چرم"> کت چرم </option>
                                                <option value = "کاپشن"> کاپشن </option>
                                                <option value = "کت زمستانی"> کت زمستانی</option>
                                                <option value = "پالتو"> پالتو </option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="productBrand"> نام برند:</label>
                                            <select dir = "rtl" class="form-control" id = "productBrand" name = "productBrand">
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
                                                <option value="Others"> Others </option>
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
                                                <option value="xxs"> XX-Small </option>
                                                <option value="xs"> X-Small </option>
                                                <option value="s"> Small </option>
                                                <option value="m"> Medium </option>
                                                <option value="l"> Large </option>
                                                <option value="xl"> X-Large </option>
                                                <option value="xxl"> XX-Large </option>
                                                <option value="35"> 35-Shoes </option>
                                                <option value="36"> 36-Shoes </option>
                                                <option value="37"> 37-Shoes </option>
                                                <option value="38"> 38-Shoes </option>
                                                <option value="39"> 39-Shoes </option>
                                                <option value="40"> 40-Shoes </option>
                                                <option value="41"> 41-Shoes </option>
                                                <option value="42"> 42-Shoes </option>
                                                <option value="43"> 43-Shoes </option>
                                                <option value="44"> 44-Shoes </option>
                                                <option value="45"> 45-Shoes </option>
                                                <option value="45"> بدون سایز </option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="productPrice"> قیمت :</label>
                                            <input type="text" class="form-control eng-format" dir="ltr" maxlength="8" onkeyup="checkPrice(); activateOrderButton()" id="productPrice" name = "productPrice">
                                        </div>
                                        <div class="form-group">
                                            <span style="color:red" id="priceAlert">
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label for="quantity"> تعداد :</label>
                                            <input type="text" class="form-control eng-format" maxlength="2" id="orderQuantity" name="orderQuantity">
                                        </div>
                                        <div class="form-group">
                                            <label for="customerName"> نام مشتری :</label>
                                            <input type="text" class="form-control eng-format" dir="rtl" maxlength="30" id="customerName" name="customerName">
                                        </div>
                                        <div class="form-group">
                                            <label for=""customerTel"> تلفن مشتری :</label>
                                            <input type="tel" class="form-control eng-format" dir="ltr" maxlength="11" id="customerTel" name="customerTel" onkeyup="checkCustomerTel(); activateOrderButton();">
                                        </div>
                                        <div class="form-group">
                                            <span style="color:red" id="telAlert">
                                            </span> 
                                        </div>
                                        <button class="form-control btn btn-group btn-primary" id="submitOrderButton" name="submitOrderButton" disabled="disabled" onclick="alert('سفارش شما با موفقیت ثبت گردید')"> ثبت سفارش 
                                            <span>
                                                <i class="fa fa-plus"> </i>
                                            </span>

                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                        <!-- /.new-order-panel-body -->
                    </div>
                    <!--new-order-panel-->
                </div>
            </div>
            </body>
            </html>

