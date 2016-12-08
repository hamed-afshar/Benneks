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
    $productGender = $_POST['productGender'];
    $productBrand = $_POST['productBrand'];
    $productSize = $_POST['productSize'];
    $productColor = $_POST['productColor'];
    $productLink = $_POST['productLink'];
    $productPrice = $_POST['productPrice'];
    $benneksPrice = intval($_POST['benneksPrice']);
    $rateTL = intval($_POST['rateTL']);
    $originalTomanPrice = intval($productPrice) * $rateTL;
    // user directory needs to be added before pic name
    $productPic = $targetDir . $image;
    // If mistakes happened and zero inserted into quantity field, it will change it to one. 
    $orderQuantity = 1;
    $query3 = "INSERT INTO benneks.orders(orderID, users_userID, customers_customerID, orderDate, orderTime, clothesType, productGender, productBrand, productSize, productColor, productLink,  productPrice, productPic, orderQuantity) "
            . "values('$orderID' ,(SELECT userID FROM benneks.users where userID='$userID'), (SELECT customerID FROM benneks.customers where customerID='$customerID'), '$orderDate', '$orderTime', '$clothesType',"
            . "'$productGender' ,'$productBrand', '$productSize', '$productColor',  '$productLink', '$productPrice', '$productPic', '$orderQuantity' )";
    if (!$user->executeQuery($query3)) {
        $flag = false;
        echo mysqli_error($user->conn);
    }
    // once an order submited, we nedd to create three records in shipment, status and cost table for this order
    $query4 = "INSERT INTO benneks.shipment(orders_orderID) VALUES ('$orderID')";
    $query5 = "INSERT INTO benneks.stat(orders_orderID) VALUES ('$orderID')";
    $query6 = "INSERT INTO benneks.cost(orders_orderID, rateTL, benneksPrice, originalTomanPrice) VALUES ('$orderID', '$rateTL' ,'$benneksPrice', '$originalTomanPrice')";
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
                    <div class="col-lg-3 col-md-4">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-comments fa-3x"></i>
                                    </div>
                                    <div class="col-xs-9  text-right">
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
                    <div class="col-lg-3 col-md-4">
                        <div class="panel panel-green">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-tasks fa-3x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge"> # </div>
                                        <div> سفارشات رسیده به استانبول </div>
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
                    <div class="col-lg-3 col-md-4">
                        <div class="panel panel-yellow">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-shopping-cart fa-3x"></i>
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
                    <div class="col-lg-3 col-md-4">
                        <div class="panel panel-red">
                            <div class="panel-heading"> 
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-support fa-3x"></i>
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
                                    <form role = "form" method="post" name="orderForm" id="orderForm" onsubmit="return validateForm();" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <input type="radio" name="productGender" id="productGender" value="female" checked> زنانه
                                            <input type="radio" name="productGender" id="productGender" value="male" > مردانه  
                                        </div>
                                        <div class="form-group">
                                            <label for="clothesType"> نوع لباس:</label>
                                            <select dir = "rtl" class="form-control" id = "clothesType" name="clothesType" onchange="showRealPrice()">
                                                <option value="" disabled selected style="color: red">انواع لباس زنانه:</option>
                                                \\Women bag and shoes
                                                <option value = "women-bag"> انواع کیف زنانه </option>
                                                <option value = "women-shoes"> انواع کفش و بوت ودمپایی زنانه</option>
                                                \\unisex products around 120 gr                         
                                                <option value = "wallet"> کیف پول مردانه یا زنانه </option> 
                                                <option value = "belt"> کمربند مردانه یا زنانه </option>
                                                <option value = "sunglass"> عینک  مردانه یا زنانه</option>
                                                <option value = "perfium"> عطر مردانه یا زنانه </option>
                                                <option value = "watch"> ساعت  مردانه یا زنانه</option>
                                                <option value = "accessory"> اکسسوری مردانه یا زنانه </option>
                                                <option value = "sucks"> جوراب زنانه یا مردانه </option>
                                                \\Women products around 200 gr
                                                <option value = "women-short"> شلوارک  زنانه</option> 
                                                <option value = "women-top"> تاپ زنانه </option> 
                                                <option value = "women-shirt"> پیراهن زنانه </option> 
                                                <option value = "women-lingerie"> لباس زیر  زنانه</option> 
                                                <option value = "women-tshirt"> تی شرت زنانه </option> 
                                                <option value = "women-scarf"> انواع شال، کلاه، روسری و دستکش </option> 
                                                <option value = "women-bikini"> مایو زنانه </option> 
                                                <option value = "women-swimsuit">  رو مایو زنانه </option> 
                                                <option value = "women-sleepwear"> لباس خواب  زنانه</option> 
                                                <option value = "women-support"> ساپورت  زنانه</option> 
                                                \\Women products around 450 gr
                                                <option value = "women-cardigan"> کاردیگان زنانه</option>
                                                <option value = "women-jean"> شلوار جین زنانه </option>
                                                <option value = "women-dress"> پیراهن بلند زنانه </option> 
                                                <option value = "women-shomiz"> شمیز و سرهمی  زنانه</option> 
                                                <option value = "women-sweater">  پلیورهای نازک زنانه</option> 
                                                <option value = "women-pancho">  پانچو  زنانه</option> 
                                                <option value = "women-pant"> شلوار معمولی  زنانه</option> 
                                                <option value = "women-skirt"> دامن زنانه </option>
                                                <option value = "women-blouse"> بلوز زنانه </option> 
                                                \\Women products around 600 gr
                                                <option value = "women-manto"> مانتو  زنانه</option>
                                                <option value = "women-rainingcoat"> بارونی  زنانه</option>
                                                <option value = "women-summerjacket"> انواع کت های تابستانی زنانه </option>
                                                <option value = "women-wintercoat"> کت زمستانی زنانه</option>
                                                <option value = "women-jacket"> کاپشن سبک  زنانه</option>
                                                \\Women products 800 gr
                                                <option value = "women-jircoat"> کت جیر زنانه</option>
                                                <option value = "women-palto"> پالتو سبک  زنانه</option>
                                                <option value = "women-sportwear"> گرمکن زنانه</option>
                                                <option value = "women-jeancoat"> کت جین  زنانه</option>
                                                <option value = "women-leathercoat"> کت چرم  زنانه</option>
                                                <option value = "women-winterjacket"> کاپشن سنگین  زنانه</option>
                                                <option value = "women-heavysweater"> پلیورهای سنگین زنانه</option>
                                                \\ Women products more than 1 kg
                                                <option value = "women-heavy"> کاپشن و کت و پالتو های سنگین و ضخیم  زنانه</option>
                                                <option value = "women-heavy">انواع کت دامن زنانه</option>
                                                <option value = "women-heavy"> انواع کت شلوار زنانه</option>
                                                \\\\\Man Products
                                                <option value="" disabled style="color:red">انواع لباس مردانه:</option>
                                                \\ Man bag & shoes
                                                <option value = "man-shoes"> انواع کفش و بوت ودمپایی مردانه </option>
                                                <option value = "man-bag"> کیف دستی مردانه </option>
                                                \\ Man products around 200 gr
                                                <option value = "man-shirt"> پیراهن مردانه </option>
                                                <option value = "man-tshirt"> تی شرت مردانه </option>
                                                <option value = "man-underwear"> لباس زیر مردانه </option>
                                                <option value = "man-short"> شلوارک مردانه </option>
                                                <option value = "man-overcoat"> مایو مردانه</option>
                                                \\ Man products around 450 gr
                                                <option value = "man-pant"> شلوار معمولی مردانه </option>
                                                <option value = "man-jean"> شلوار جین مردانه </option>     
                                                <option value = "man-sweater"> پلیور مردانه سبک </option>
                                                \\Man products around 600 gr
                                                <option value = "man-coat"> کت معمولی مردانه</option>
                                                \\Man products around 800 gr
                                                <option value = "man-sportwear"> گرمکن مردانه</option>
                                                <option value = "man-jean-coat"> کت جین مردانه</option>
                                                <option value = "man-heavy-sweater"> پلیور مردانه سنگین </option>
                                                <option value = "man-jacket"> کاپشن سبک مردانه </option>
                                                \\Man product more than 1 kg
                                                <option value = "man-heavy-jacket"> کاپشن سنگین مردانه</option>
                                                <option value = "man-leather-coat"> کت چرم مردانه</option>
                                                <option value = "man-palto"> پالتو مردانه</option>
                                                <option value = "man-overcoat"> اورکت مردانه</option>
                                                <option value = "man-suit"> کت شلوار مردانه</option>
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
                                                <option value="Women-Free-Size"> فری سایز زنانه </option>
                                                <option value="" disabled style="color: red">  سایزهای کفش زنانه</option>
                                                <option value="Shoes-35"> Shoes(EU=35, UK=2.5) </option>
                                                <option value="Shoes-35.5"> Shoes(EU=35.5, UK=3) </option>
                                                <option value="Shoes-36"> Shoes(EU=36, UK=3.5) </option>
                                                <option value="Shoes-36.5"> Shoes(EU=36.5, UK=4) </option>
                                                <option value="Shoes-37"> Shoes(EU=37, UK=4.5) </option>
                                                <option value="Shoes-37.5"> Shoes(EU=37.5, UK=5) </option>
                                                <option value="Shoes-38"> Shoes(EU=38, UK=5.5) </option>
                                                <option value="Shoes-37"> Shoes(EU=38.5, UK=6) </option>
                                                <option value="Shoes-39"> Shoes(EU=39, UK=6.5) </option>
                                                <option value="Shoes-39"> Shoes(EU=39.5, UK=7) </option>
                                                <option value="Shoes-40"> Shoes(EU=40, UK=7.5) </option>
                                                <option value="Shoes-40.5"> Shoes(EU=40.5, UK=8) </option>
                                                <option value="Shoes-41"> Shoes(EU=41, UK=8.5)</option>
                                                <option value="Shoes-41.5"> Shoes(EU=41.5, UK=9)</option>
                                                <option value="Shoes-42"> Shoes(EU=42, UK=9.5)</option>
                                                <option value="Shoes-42.5"> Shoes(EU=42.5, UK=10)</option>
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
                                                <option value="Men-Free-Size"> فری سایز مردانه </option>
                                                <option value="" disabled style="color: red">  سایزهای کفش مردانه</option>
                                                <option value="Shoes-38"> Shoes(EU=38, UK=5) </option>
                                                <option value="Shoes-39"> Shoes(EU=39, UK=6) </option>
                                                <option value="Shoes-40"> Shoes(EU=40, UK=6.5) </option>
                                                <option value="Shoes-40.5"> Shoes(EU=40.5, UK=7) </option>
                                                <option value="Shoes-41"> Shoes(EU=41, UK=7.5,) </option>
                                                <option value="Shoes-41.5"> Shoes(EU=41.5, UK=8) </option>
                                                <option value="Shoes-42"> Shoes(EU=42, UK=8.5)</option>
                                                <option value="Shoes-42.5"> Shoes(EU=42.5, UK=9)</option>
                                                <option value="Shoes-43"> Shoes(EU=43, UK=9.5)</option>
                                                <option value="Shoes-43.5"> Shoes(EU=43.5, UK=10)</option>
                                                <option value="Shoes-44"> Shoes(EU=44, UK=10.5)</option>
                                                <option value="Shoes-44.5"> Shoes(EU=44.5, UK=11)</option>
                                                <option value="Shoes-45"> Shoes(EU=45, UK=11.5)</option>
                                                <option value="Shoes-46"> Shoes(EU=46, UK=12.5)</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="productColor">  رنگ:</label>
                                            <select dir = "rtl"  class="form-control" id = "productColor" name = "productColor">
                                                <option value="" disabled selected>  انتخاب رنگ</option>
                                                <option value="White">  سفید</option>
                                                <option value="Black">  مشکی</option>
                                                <option value="Green">  سبز</option>
                                                <option value="Blue">  آبی</option>
                                                <option value="Red">  قرمز</option>
                                                <option value="Yellow">  زرد</option>
                                                <option value="Brown">  قهوای</option>
                                                <option value="Grey">  طوسی</option>
                                                <option value="Dark Blue">  سرمه ای</option>
                                                <option value="Purple">  بنفش</option>
                                                <option value="Light-Grey">  نقره ای</option>
                                                <option value="Crimson">  زرشکی</option>
                                                <option value="Bisque">  کرم</option>
                                                <option value="Pink">  صورتی</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="productPrice"> قیمت :</label>
                                            <input type="text" class="form-control eng-format" dir="ltr" maxlength="8" onkeyup="checkPrice();showRealPrice();" id="productPrice" name = "productPrice">
                                        </div>
                                        <div class="form-group">
                                            <span style="color:red" id="priceAlert">
                                            </span>
                                        </div>
                                        <div class = "form-group">
                                            <label for = "benneksPrice"> قیمت فروش سیستم :</label>
                                            <input type = "text" class = "form-control eng-format" dir="ltr" id = "benneksPrice" name = "benneksPrice" readonly="readonly" placeholder = "قیمت فروش سیستم ">
                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" id="rateTL" name="rateTL" >
                                        </div>

                                        <!-- javascript to pass variables to calculator() in script.js file -->
                                        <script>
                                            function showRealPrice() {
                                                var clothesType = document.getElementById("clothesType").value;
                                                var productPrice = document.getElementById("productPrice").value;
                                                 document.getElementById("benneksPrice").value = calculator(clothesType, productPrice);
                                                 document.getElementById("rateTL").value = currencyRate();
                                            }
                                        </script>

                                        <button class="form-control btn btn-group btn-primary" id="submitOrderButton" name="submitOrderButton" > ثبت سفارش 
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

