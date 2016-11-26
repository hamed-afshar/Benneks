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
date_default_timezone_set("Asia/Tehran");
// fetch order table for a user that owns curent session ID
$userID = $_SESSION['user'];
$query = "SELECT orders.orderID, users.username ,orders.orderDate, orders.orderTime ,orders.productPrice, orders.productBrand, orders.productLink, orders.productPic, orders.productSize ,orders.orderQuantity, stat.orderStatus, stat.orderStatusDescription FROM benneks.orders INNER JOIN benneks.stat ON stat.orders_orderID = orders.orderID INNER JOIN benneks.users ON users.userID = orders.users_userID ORDER BY orders.orderDate desc, orders.orderTime desc";
if (!$user->executeQuery($query)) {
    echo mysqli_error($user->conn);
}
$queryResult = $user->executeQuery($query);
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
<!-- script for add modal -->
<script>
    $(document).ready(function () {
        $(document).on("click", ".open-addModal", function () {
            var orderID = $(this).data('id');
            $(".modal-body #rowID").val(orderID);
        });
    });
</script>
<!-- script for cancel modal -->
<script>
    $(document).ready(function () {
        $(document).on("click", ".open-cancelModal", function () {
            var orderID = $(this).data('id');
            $(".modal-body #rowID").val(orderID);
        });
    });
</script>
<!-- script for iran Delivery modal -->
<script>
    $(document).ready(function () {
        $(document).on("click", ".open-iranDeliverModal", function () {
            var orderID = $(this).data('id');
            $(".modal-body #rowID").val(orderID);
        });
    });
</script>
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
                            <a href="admin.php"> <i class="fa fa-th-list fa-fw"> </i> لیست سفارشات </a>
                        </li>
                        <li>
                            <a href="calculator.php"> <i class="fa fa-calculator fa-fw"></i> ماشین حساب </a>
                        </li>
                        <li>
                            <a href="admindetails.php"> <i class="fa fa-area-chart fa-fw"> </i> گزارش مفصل </a>
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
                <div class="panel panel-default" dir="rtl" >
                    <div class="panel-heading">
                        <i class="fa fa-shopping-bag fa-fw"></i> لیست سفارشات
                        <center><a href="admin.php"> <i class="fa fa-refresh fa-fw"></i> به روز رسانی </center> </a>
                    </div>
                    <!-- /.list-panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped" style="text-align:center; overflow-x: scroll">
                                <thead>
                                    <tr>
                                        <th style="text-align: center"> کد</th>
                                        <th style="text-align: center"> کاربر</th>
                                        <th style="text-align: center"> تاریخ سفارش</th>
                                        <th style="text-align: center"> زمان سفارش</th>
                                        <th style="text-align: center"> قیمت</th>
                                        <th style="text-align: center"> برند</th>    
                                        <th style="text-align: center">لینک </th>
                                        <th style="text-align: center">عکس </th>
                                        <th style="text-align: center">سایز </th>
                                        <th style="text-align: center">تعداد </th>
                                        <th style="text-align: center">وضعیت </th>
                                        <th style="text-align: center">جزئیات </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = mysqli_fetch_row($queryResult)) {

                                        echo "<tr>";
                                        echo "<td> " . $row[0] .
                                        "<hr> "
                                        . "<a href='#addModal' data-toggle='modal' data-target='#addModal' data-id='$row[0]' class='open-addModal' > <i class='fa fa-check fa-fw fa-lg'></i> </a>"
                                        . "<a href='#cancelModal' data-toggle='modal' data-target='#cancelModal' data-id='$row[0]' class='open-cancelModal'> <i class='fa fa-times fa-fw fa-lg'></i> </a>"
                                        . "<a href='#cancelModal' data-toggle='modal' data-target='#iranDeliverModal' data-id='$row[0]' class='open-iranDeliverModal'> <i class='fa fa-plane fa-fw fa-lg'></i> </a>"
                                        . " </td>";
                                        echo "<td>" . $row[1] . "</td>";
                                        echo "<td>" . $row[2] . "</td>";
                                        echo "<td>" . $row[3] . "</td>";
                                        echo "<td>" . $row[4] . "</td>";
                                        echo "<td>" . $row[5] . "</td>";
                                        echo "<td> <a href= " . $row[6] . ">لینک محصول" . "</a> </td>";
                                        $picURL = str_replace(' ', '%20', $row[7]);
                                        echo "<td><a href=" . $picURL . "> <img src=" . $picURL . " class='img-rounded'" . "alt='بدون تصویر' width='100' height='100'> </a> </td>";
                                        echo "<td>" . $row[8] . "</td>";
                                        echo "<td>" . $row[9] . "</td>";
                                        echo "<td>" . $row[10] . "</td>";
                                        echo "<td>" . $row[11] . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--cancel order modal -->
                <div class = "modal fade" id = "cancelModal" role="dialog">
                    <div class="modal-dialog">
                        <!--modal content -->
                        <div class="modal-content">
                            <div class="modal-header" style="padding: 35px 50px;">
                                <button type="button" class="close" data-dismiss = "modal">&times; </button>
                                <h4><span class = "glyphicon glyphicon-briefcase"> </span> لغو سفارش </h4>
                            </div>
                            <div class="modal-body" style="padding:40px 50px;">
                                <form role="form" action="cancelOrder.php" method="post" dir="rtl">
                                    <div class="form-group">
                                        <label for="rowID"> کد سفارش </label>
                                        <input type="text" class="form-control" name="rowID" id="rowID">
                                    </div>
                                    <div class="form-group">
                                        <label for="cancelDetails"><span class="glyphicon glyphicon-hand-left"></span>  دلیل لغو سفارش</label>
                                        <select dir = "rtl" class = "form-control" id = "cancelDetails" name="cancelDetails"> 
                                            <option value = "نبودن سایز">موجود نبودن سایز </option>
                                            <option value = "تمام شدن محصول">به اتمام رسیدن زمان</option>
                                            <option value = "موجود نبودن رنگ">موجود نبودن رنگ</option>
                                            <option value = "اطلاعات ناقص">ناقص بودن اطلاعات ورودی </option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-danger btn-block" name="submitButton" id="submitButton"> لغو سفارش  </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!--accept order modal -->
                <div class = "modal fade" id = "addModal" role="dialog">
                    <div class="modal-dialog">
                        <!--modal content -->
                        <div class="modal-content">
                            <div class="modal-header" style="padding: 35px 50px;">
                                <button type="button" class="close" data-dismiss = "modal">&times; </button>
                                <h4><span class = "glyphicon glyphicon-briefcase"> </span> خرید محصول </h4>
                            </div>
                            <div class="modal-body" style="padding:40px 50px;">
                                <form role="form" action="addorder.php" method="post" dir="rtl">
                                    <div class="form-group">
                                        <label for="rowID"> کد سفارش </label>
                                        <input type="text" class="form-control" name="rowID" id="rowID">
                                    </div>
                                    <div class="form-group">
                                        <label for="shoppingDate"><span class="glyphicon glyphicon-calendar"></span>  تاریخ خرید</label>
                                        <input type="date" class="form-control" name="shoppingDate" id="shoppingDate" > 
                                    </div>

                                    <button type="submit" class="btn btn-success btn-block" name="submitButton" id="submitButton"> ثبت </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Iran Deliver order modal -->
                <div class = "modal fade" id = "iranDeliverModal" role="dialog">
                    <div class="modal-dialog">
                        <!--modal content -->
                        <div class="modal-content">
                            <div class="modal-header" style="padding: 35px 50px;">
                                <button type="button" class="close" data-dismiss = "modal">&times; </button>
                                <h4><span class = "glyphicon glyphicon-briefcase"> </span> خرید محصول </h4>
                            </div>
                            <div class="modal-body" style="padding:40px 50px;">
                                <form role="form" action="iranDeliver.php" method="post" dir="rtl">
                                    <div class="form-group">
                                        <label for="rowID"> <span class="glyphicon glyphicon-asterisk"></span> کد سفارش </label>
                                        <input type="text" class="form-control" name="rowID" id="rowID">
                                    </div>
                                    <div class="form-group">
                                        <label for="benneksDeliverDate"><span class="glyphicon glyphicon-calendar"></span>  تاریخ ارسال</label>
                                        <input type="date" class="form-control" name="benneksDeliverDate" id="benneksDeliverDate" > 
                                    </div>
                                    <div class="form-group">
                                        <label for="productsWeight"><span class="glyphicon glyphicon-scale"></span>  وزن کالا به گرم</label>
                                        <input type="text" class="form-control" name="productsWeight" id="productsWeight" maxlength="4"> 
                                    </div>
                                    <div class="form-group">
                                        <label for="cargoName"> <span class="glyphicon glyphicon-road"></span> کد کارگو </label> 
                                        <input type="text" class="form-control" name="cargoName" id="cargoName">
                                    </div>
                                    <button type="submit" class="btn btn-success btn-block" name="submitButton" id="submitButton"> ثبت </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </body>
            </html>

