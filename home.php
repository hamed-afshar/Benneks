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
    // For phase 1 we dont get these information from users
    $customerName = "نامشخص";
    $customerTel = "نامشخص";
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
    $targetFile = $targetDir . basename($_FILES["productPic"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($targetFile);
    move_uploaded_file($_FILES["productPic"]["tmp_name"], $targetFile);
    $image = basename($_FILES["productPic"]["name"]);
// Insert customer information into database
    $customerID = $lastID + 1;
    $query2 = "INSERT INTO benneks.customers(customerID, customerName, customerTel) VALUES ('$customerID', '$customerName', '$customerTel')";
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
    $productBrand = $_POST['productBrand'];
    $productSize = $_POST['productSize'];
    $productLink = $_POST['productLink'];
    $productPrice = $_POST['productPrice'];
    $benneksPrice = intval($_POST['benneksPrice']);
    // user directory needs to be added before pic name
    $productPic = $targetDir . $image;
    // If mistakes happened and zero inserted into quantity field, it will change it to one. 
    $orderQuantity = 1;
    $query3 = "INSERT INTO benneks.orders(orderID, users_userID, customers_customerID, orderDate, orderTime, clothesType, productBrand, productSize, productLink, productPrice, productPic, orderQuantity) "
            . "values('$orderID' ,(SELECT userID FROM benneks.users where userID='$userID'), (SELECT customerID FROM benneks.customers where customerID='$customerID'), '$orderDate', '$orderTime', '$clothesType',"
            . " '$productBrand', '$productSize', '$productLink', '$productPrice', '$productPic', '$orderQuantity' )";
    if (!$user->executeQuery($query3)) {
        $flag = false;
        echo mysqli_error($user->conn);
    }
    // once an order submited, we nedd to create three records in shipment, status and cost table for this order
    $query4 = "INSERT INTO benneks.shipment(orders_orderID) VALUES ('$orderID')";
    $query5 = "INSERT INTO benneks.stat(orders_orderID) VALUES ('$orderID')";
    $query6 = "INSERT INTO benneks.cost(orders_orderID, benneksPrice) VALUES ('$orderID', '$benneksPrice')";
    if (($user->executeQuery($query4)) && ($user->executeQuery($query5)) && ($user->executeQuery($query6))) {
        $flag = true;
    } else {
        $flag = false;
        echo mysqli_error($user->conn);
    }
// if all queries executed properly then comit the changes in to database otherwise roll back all changes
    if ($flag) {
        mysqli_commit($user->conn);
        echo '<script language="javascript">';
        echo 'alert("سفارش شما با موفقیت در سیستم ثبت گردید")';
        echo '</script>';
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
                            <a href="#"> <i class="fa fa-area-chart fa-fw"> </i> گزارش مفصل </a>
                        </li>
                        <li>
                            <a href="#"> <i class="fa fa-money fa-fw" > </i> گزارش مالی</a>
                        </li>
                        <li>
                            <a href="#"> <i class="fa fa-gear fa-fw" > </i> تنظیمات پروفایل</a>
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
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-comments fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge"> # </div>
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
                                        <div class="huge"> # </div>
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
                                        <div class="huge"> # </div>
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
                                        <div class="huge"> # </div>
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
                                            <select dir = "rtl" class="form-control" id = "clothesType" name="clothesType" onchange="showRealPrice()">
                                                <option value="" disabled selected>نوع لباس را مشخص نمایید</option>
                                                <option value = "bag"> انواع کیف </option>
                                                <option value = "shoes"> انواع کفش و بوت </option>
                                                \\Products around 120 gr                         
                                                <option value = "wallet"> کیف پول </option> 
                                                <option value = "belt"> کمربند </option>
                                                <option value = "sunglass"> عینک </option>
                                                <option value = "perfium"> عطر </option>
                                                <option value = "watch"> ساعت </option>
                                                <option value = "accessory"> اکسسوری </option>
                                                \\Products around 200 gr
                                                <option value = "short"> شلوارک </option> 
                                                <option value = "blouse"> بلوز </option> 
                                                <option value = "top"> تاپ </option> 
                                                <option value = "skirt"> دامن </option> 
                                                <option value = "womenshirt"> پیراهن زنانه </option> 
                                                <option value = "manshirt"> پیراهن مردانه </option> 
                                                <option value = "dress"> پیراهن بلند زنانه </option> 
                                                <option value = "lingerie"> لباس زیر </option> 
                                                <option value = "tshirt"> تی شرت </option> 
                                                <option value = "scarf"> انواع روسری و شال </option> 
                                                <option value = "bikini"> مایو </option> 
                                                <option value = "swimsuit"> رو مایو </option> 
                                                <option value = "sleepwear"> لباس خواب </option> 
                                                <option value = "support"> ساپورت </option> 
                                                \\Products around 450 gr
                                                <option value = "cardigan"> کاردیگان</option>
                                                <option value = "manto"> مانتو </option>
                                                <option value = "rainingcoat"> بارونی </option> 
                                                <option value = "summerjacket"> انواع کت های تابستانی </option>
                                                <option value = "jean"> شلوار جین </option>
                                                <option value = "coat&skirt"> کت و دامن به همراه هم </option>
                                                <option value = "shomiz"> شمیز و سرهمی </option> 
                                                <option value = "sweater"> پلیورهای نازک</option> 
                                                <option value = "pancho">  پانچو </option> 
                                                <option value = "pant"> شلوار معمولی </option> 
                                                \\Products around 600 gr
                                                <option value = "wintercoat"> کت زمستانی</option>
                                                <option value = "palto"> پالتو سبک </option>
                                                <option value = "jacket"> کاپشن سبک </option>
                                                \\ Products around 800 gr
                                                <option value = "jeancoat"> کت جین </option>
                                                <option value = "leathercoat"> کت چرم </option>
                                                <option value = "winterjacket"> کاپشن سنگین </option>
                                                <option value = "heavysweater"> پلیورهای سنگین</option>
                                                \\ Products more than 1 kg
                                                <option value = "heavy"> کاپشن و کت و پالتو های سنگین و ضخیم </option>
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
                                            <label for="productSize"> سایز زنانه:</label>
                                            <select dir = "ltr"  class="eng-format form-control" id = "productSize" name = "productSize">
                                                <option value="XXSmall-UK4-EU32-US1-AUS4"> XX-Small(UK=4, EU=32, US=1) </option>
                                                <option value="XSmall-UK6-EU34-US2-AUS6"> X-Small(UK=6, EU=34, US=2) </option>
                                                <option value="Small-UK8-EU36-US4-AUS8"> Small(UK=8, EU=36, US=4) </option>
                                                <option value="Small-UK10-EU38-US6-AUS10"> Small(UK=10, EU=38, US=6) </option>
                                                <option value="Medium-UK12-EU40-US8-AUS12"> Medium(UK=12, EU=40, US=8) </option>
                                                <option value="Medium-UK14-EU42-US10-AUS14"> Medium(UK=14, EU=42, US=10) </option>
                                                <option value="Large-UK16-EU44-US12-AUS16"> Large(UK=16, EU=44, US=12) </option>
                                                <option value="Large-UK18-EU46-US14-AUS18"> Large(UK=18, EU=46, US=14) </option>
                                                <option value="XLarge-UK20-EU48-US16-AUS20"> X-Large(UK=20, EU=48, US=16) </option>
                                                <option value="Shoes-35"> Shoes(EU=35, UK=2, US=4) </option>
                                                <option value="Shoes-36"> Shoes(EU=36, UK=3, US=5) </option>
                                                <option value="Shoes-37"> Shoes(EU=37, UK=4, US=6) </option>
                                                <option value="Shoes-38"> Shoes(EU=38, UK=5, US=7) </option>
                                                <option value="Shoes-39"> Shoes(EU=39, UK=6, US=8) </option>
                                                <option value="Shoes-40"> Shoes(EU=40, UK=7, US=9) </option>
                                                <option value="Shoes-41"> Shoes(EU=41, UK=8, US=10)</option>
                                                <option value="Shoes-42"> بدون سایز </option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="productPrice"> قیمت :</label>
                                            <input type="text" class="form-control eng-format" dir="ltr" maxlength="8" onkeyup="checkPrice(); activateOrderButton(); showRealPrice();" id="productPrice" name = "productPrice">
                                        </div>
                                        <div class="form-group">
                                            <span style="color:red" id="priceAlert">
                                            </span>
                                        </div>
                                        <div class = "form-group">
                                            <label for = "benneksPrice"> قیمت فروش سیستم :</label>
                                            <input type = "text" class = "form-control eng-format" dir="ltr" id = "benneksPrice" name = "benneksPrice" readonly="readonly" placeholder = "قیمت فروش سیستم ">
                                        </div>

                                        <!-- javascript to pass variables to calculator() in script.js file -->
                                        <script>
                                            function showRealPrice() {
                                                var clothesType = document.getElementById("clothesType").value;
                                                var productPrice = document.getElementById("productPrice").value;
                                                document.getElementById("benneksPrice").value = calculator(clothesType, productPrice);
                                            }
                                        </script>

                                        <button class="form-control btn btn-group btn-primary" id="submitOrderButton" name="submitOrderButton" disabled="disabled"> ثبت سفارش 
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
            </div>
        </div>
</body>
</html>

