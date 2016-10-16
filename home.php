<html>
<?php
/* ob_start();
  session_start();
  require 'src/benneks.php';
  // if session is not set this will redirect to login page
  if (!isset($_SESSION['user'])) {
  header("Location: register.php");
  exit();
  } */
?>



    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="benneks control panel">
        <meta name="author" content="hamed">

        <!-- Bootstrap -->
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />

        <!-- JQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"/></script>
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
    //for test
    <?php ?>
    ///////
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
            <ul class="nav navbar-top-links navbar-right">
                <!-- dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-envelope fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <li>
                            <a href="#">
                                <div>
                                    <strong> New Order </strong>
                                    <span class="pull-right text-muted">
                                        <em> Today </em>
                                    </span>
                                </div>
                                <div> New order has been submitted </div>
                            </a>
                        </li>
                        <li class="divider"> </li>
                        <li>
                            <a class="text-center" href="#">
                                <strong> Read All Messages </strong>
                                <i class="fa fa-angle-right"> </i>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- Dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-calculator"></i>
                    </a>
                </li>
                <!-- dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"> </i> <i class="fa fa-caret-down"> </i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li> <a href="#"><i class="fa fa-user fa-fw"> </i> پروفایل کاربر </a> </li>
                        <li> <a href="#"><i class="fa fa-gear fa-fw"> </i> تنظیمات </a> </li>
                        <li class="divider"> </li>
                        <li> <a href="logout.php"> <i class="fa fa-sign-out fa-fw"> </i> خروج </a> </li>
                    </ul>
                </li>
            </ul>

            <!-- navigation bar on right -->
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav in" id="side-menu">
                        <li>
                            <a href="home.php"> <i class="fa fa-dashboard fa-fw"></i> داشبورد </a>
                        </li>
                        <li>
                            <a href="#"> <i class="fa fa-th-list fa-fw"> </i> لیست سفارشات </a>
                        </li>
                        <li>
                            <a href="#"> <i class="fa fa-cart-plus fa-fw"> </i> سفارش جدید </a>
                        </li>
                        <li>
                            <a href="#"> <i class="fa fa-money fa-fw" > </i> گزارش مالی</a>
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
                    <div class="col-lg-12">
                        <div class="panel panel-default" >
                            <div class="panel-heading">
                                <i class="fa fa-shopping-bag fa-fw"></i> ثبت سفارش 
                            </div>
                            <!-- /.new-order-panel-heading -->
                            <div class="panel-body">
                                <div id="newOrderArea">
                                    <div class="col-lg-6"> 
                                    </div>
                                    <div class="col-lg-6" >
                                        <form role = "form" method="post">
                                            <div class="form-group">
                                                <label for="clothesType"> نوع لباس:</label>
                                                <select dir = "rtl" class="form-control" id = "clothesType">
                                                    <option value = "bag"> انواع کیف </option>
                                                    <option value = "shoes"> انواع کفش و بوت </option>

                                                    <option value = "wallet"> کیف پول </option> 
                                                    <option value = "belt"> کمربند </option>
                                                    <option value = "sunglass"> عینک </option>
                                                    <option value = "perfium"> عطر </option>
                                                    <option value = "watch"> ساعت </option>
                                                    <option value = "accessory"> اکسسوری </option>

                                                    <option value = "shomiz"> شمیز و سرهمی </option>
                                                    <option value = "short"> شلوارک </option>
                                                    <option value = "blouse"> بلوز </option>
                                                    <option value = "sweater"> پلیور </option>
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
                                                    <option value = "pancho">  پانچو </option>
                                                    <option value = "pant"> شلوار معمولی </option>

                                                    <option value = "cardigan"> کاردیگان</option>
                                                    <option value = "manto"> مانتو </option>
                                                    <option value = "rainingcoat"> بارونی </option>
                                                    <option value = "summerjacket"> انواع کت های جین و تابستانی </option>
                                                    <option value = "jean"> شلوار جین </option>
                                                    <option value = "coat&skirt"> کت و دامن به همراه هم </option>

                                                    <option value = "leathercoat"> کت چرم </option>
                                                    <option value = "winterjacket"> کاپشن </option>
                                                    <option value = "wintercoat"> کت زمستانی</option>
                                                    <option value = "palto"> پالتو </option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="productBrand"> نام برند:</label>
                                                <select dir = "rtl" class="form-control" id = "productBrand">
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
                                                <input type="text" dir="ltr" class="form-control eng-format" id="productLink">
                                            </div>
                                            <div class="form-group">
                                                <label for="productPic"> عکس:</label>
                                                <input type="file" class="eng-format" id="productPic" accept="image/*">
                                            </div>
                                            <div class="form-group">
                                                <label for="productSize"> سایز:</label>
                                                <select dir = "ltr"  class="eng-format form-control" id = "productSize">
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
                                                <input type="text" class="form-control eng-format" dir="ltr" maxlength="8" onkeyup="checkPrice(); activateOrderButton()" id="productPrice">
                                            </div>
                                            <div class="form-group">
                                                <span style="color:red" id="priceAlert">
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label for="quantity"> تعداد :</label>
                                                <input type="text" class="form-control eng-format" maxlength="2" id="orderQuantity">
                                            </div>
                                            <div class="form-group">
                                                <label for="customerName"> نام مشتری :</label>
                                                <input type="text" class="form-control eng-format" dir="rtl" maxlength="30" id="customerName">
                                            </div>
                                            <div class="form-group">
                                                <label for=""customerTel"> تلفن مشتری :</label>
                                                <input type="tel" class="form-control eng-format" dir="ltr" maxlength="11" onkeyup="checkCustomerTel(); activateOrderButton()" id="customerTel">
                                            </div>
                                            <div class="form-group">
                                                <span style="color:red" id="telAlert">
                                                </span> 
                                            </div>
                                            <button class="form-control btn btn-group btn-primary" id="submitOrderButton"> ثبت سفارش 
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
