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
$userID = $_SESSION['user'];

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
                            <a href="#"> <i class="fa fa-sign-out fa-fw" > </i> خروج</a>
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
                <div class="panel panel-default" dir="rtl" >
                    <div class="panel-heading">
                        <i class="fa fa-shopping-bag fa-fw"></i> لیست سفارشات
                    </div>
                    <!-- /.list-panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                <th> کد</th>
                                <th> عکس</th>
                                <th> خرید</th>
                                <th> ارسال</th>    
                                <th>وضعیت </th>
                                </thead>

                                <tbody>



                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
            </body>
            </html>
