<?php
/* This page shows detailed information about orders in admin panel
 */
ob_start();
session_start();
require 'src/benneks.php';
// if Admin session is not set this will get access denied msg
if ($_SESSION['userAccess'] !== '2') {
    echo "اجازه دسترسی ندارید";
    exit();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$user = new user();
date_default_timezone_set("Asia/Tehran");
// fetch order table for a user that owns curent session ID with pagination
$limit = 50;
$userID = $_SESSION['user'];
// if search button submited then search query will be created
if (isset($_SESSION['searchQuery'])) {
    $searchQuery = "AND " . $_SESSION['searchQuery'];
} else {
    $searchQuery = "";
}
if (isset($_GET["page"])) {
    $page = $_GET["page"];
    $startFrom = ($page - 1) * $limit;
    $query1 = "select orders.orderID, users.userName, orders.productPic, orders.productLink ,orders.orderDate, orders.productPrice, cost.rateTL, cost.originalTomanPrice ,cost.benneksPrice ,shipment.cargoName " .
            "FROM benneks.orders inner JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID inner JOIN benneks.cost ON orders.orderID = cost.orders_orderID inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID " .
            "inner JOIN benneks.users ON orders.users_userID = users.userID where users.userID IN (SELECT users.userID FROM benneks.users) $searchQuery  ORDER BY users.userName, orders.orderDate desc, orders.orderID desc LIMIT " . $startFrom . "," . $limit;
} else {
    $page = 1;
    $startFrom = ($page - 1) * $limit;
    $query1 = "select orders.orderID, users.userName, orders.productPic, orders.productLink ,orders.orderDate, orders.productPrice, cost.rateTL, cost.originalTomanPrice ,cost.benneksPrice ,shipment.cargoName " .
            "FROM benneks.orders inner JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID inner JOIN benneks.cost ON orders.orderID = cost.orders_orderID inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID " .
            "inner JOIN benneks.users ON orders.users_userID = users.userID where users.userID IN (SELECT users.userID FROM benneks.users) $searchQuery  ORDER BY users.username, orders.orderDate desc, orders.orderID desc LIMIT " . $startFrom . "," . $limit;
};
if (!$user->executeQuery($query1)) {
    echo mysqli_error($user->conn);
}
$queryResult1 = $user->executeQuery($query1);
//Get totall value(TL) and numbers for yesterday orders only for successfull orders. Cancelation and unknown status orders sustracted from this number.
$query2 = "SELECT SUM(CAST(orders.productPrice AS decimal(5,2))), count(orders.orderID) FROM benneks.orders INNER JOIN benneks.stat ON orders.orderID = stat.orders_orderID WHERE stat.orderStatus = 'انجام شده' AND orders.orderDate = subdate(current_date(), 1)";
if (!$user->executeQuery($query2)) {
    echo mysqli_error($user->conn);
}
$queryResult2 = $user->executeQuery($query2);
$yesterdayValue = mysqli_fetch_row($queryResult2);
//Get totall value(TL) and numbers for Today orders only for successfull orders. Cancelation and unknown status orders sustracted from this number.
$query3 = "SELECT SUM(CAST(orders.productPrice AS decimal(5,2))), count(orders.orderID) FROM benneks.orders INNER JOIN benneks.stat ON orders.orderID = stat.orders_orderID WHERE stat.orderStatus = 'انجام شده' AND orders.orderDate = current_date()";
if (!$user->executeQuery($query2)) {
    echo mysqli_error($user->conn);
}
$queryResult3 = $user->executeQuery($query3);
$todayValue = mysqli_fetch_row($queryResult3);
//Get totall value(TL) and numbers for month orders only for successfull orders. Cancelation and unknown status orders sustracted from this number.
$query4 = "SELECT SUM(CAST(orders.productPrice AS decimal(5,2))), count(orders.orderID) FROM benneks.orders INNER JOIN benneks.stat ON orders.orderID = stat.orders_orderID WHERE stat.orderStatus = 'انجام شده' AND MONTH(orders.orderDate) = month(current_date());";
if (!$user->executeQuery($query4)) {
    echo mysqli_error($user->conn);
}
$queryResult4 = $user->executeQuery($query4);
$monthValue = mysqli_fetch_row($queryResult4);
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
                <div class="panel panel-success" dir="rtl" >
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"> 
                                <center> <i class="fa fa-shopping-bag fa-fw"></i> لیست سفارشات  <a href="admin.php"> <i class="fa fa-refresh fa-fw"></i> </a></center>
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 col-lg-push-4 col-md-push-4 col-sm-push-4" dir="rtl">
                                <div class="panel panel-primary" dir="rtl">
                                    <div class="panel-heading">
                                        <i class="fa fa-exchange fa-fw"></i> حجم مالی:
                                        <div class="form-inline">
                                            <div class="form-group">
                                                <label for="dayQuantity"> امروز:</label>
                                                <label id="dayQuantity" style="color: goldenrod"> <?php echo $todayValue[0]; ?>  </label>  
                                            </div>
                                            <div class="form-group">
                                                <label for="yesterdayQuantuty"> روز گذشته:</label>
                                                <label id="yesterdayQuantuty" style="color: goldenrod"> <?php echo $yesterdayValue[0]; ?> </label>  
                                            </div>
                                            <div class="form-group">
                                                <label for="monthQuantity"> ماه:</label>
                                                <label id="monthQuantity" style="color: goldenrod"> <?php echo $monthValue[0]; ?> </label> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 col-lg-push-4 col-md-push-4 col-sm-push-4" dir="rtl">
                                <div class="panel panel-primary" dir="rtl">
                                    <div class="panel-heading">
                                        <i class="fa fa-bullhorn fa-fw"></i> تعداد سفارشات:
                                        <div class="form-inline">
                                            <div class="form-group">
                                                <label for="dayQuantity"> امروز:</label>
                                                <label id="dayQuantity" style="color: goldenrod"> <?php echo $todayValue[1]; ?> </label> &nbsp &nbsp &nbsp
                                            </div>
                                            <div class="form-group">
                                                <label for="yesterdayQuantuty"> روز گذشته:</label>
                                                <label id="yesterdayQuantuty" style="color: goldenrod"> <?php echo $yesterdayValue[1]; ?> </label> &nbsp &nbsp &nbsp
                                            </div>
                                            <div class="form-group">
                                                <label for="monthQuantity"> ماه:</label>
                                                <label id="monthQuantity" style="color: goldenrod"> <?php echo $monthValue[1]; ?> </label> &nbsp &nbsp &nbsp
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 col-lg-pull-8 col-md-pull-8 col-sm-pull-8" dir="rtl">
                                <div class="panel panel-primary" dir="rtl">
                                    <div class="panel-heading">
                                        <form role = "form" action="search.php" method="post" dir="rtl">
                                            <i class="fa fa-filter fa-fw"></i> عبارت جستجو:
                                            <div class="form-group">
                                                <input type="search" class = "form-control" dir="ltr" id="searchInput" name="searchInput" placeholder="search...">
                                            </div>
                                            <div class="form-group">
                                                <label for="searchOption"> نوع فیلتر:</label>
                                                <select dir = "rtl" class = "form-control" id = "searchOption" name="searchOption">
                                                    <option value="code" selected> کد </option>
                                                    <option value="name"> نام </option>
                                                    <option value="done"> خریداری شده</option>
                                                    <option value="cancel"> لغو شده</option>
                                                    <option value="unknown"> نامشخص </option>
                                                    <option value="cargo"> کارگو </option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <input type="hidden" name="searchReq" value="admindetailsPage"/>
                                            </div>
                                            <button class="form-control btn btn-group btn-success" id="searchButton" name="searchButton" > جستجو
                                                <span>
                                                    <i class="fa fa-search"> </i>
                                                </span>
                                            </button>
                                        </form> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.list-panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped" style="text-align:center; overflow-x: scroll">
                                <thead>
                                    <tr>
                                        <th style="text-align: center"> کد</th>
                                        <th style="text-align: center"> کاربر</th>
                                        <th style="text-align: center">  عکس</th>
                                        <th style="text-align: center"> تاریخ سفارش</th>
                                        <th style="text-align: center"> قیمت اصلی(لیر)</th>
                                        <th style="text-align: center"> نرخ لیر(لیر)</th>
                                        <th style="text-align: center"> قیمت اصلی(تومان)</th>
                                        <th style="text-align: center">قیمت محاسبه شده </th>
                                        <th style="text-align: center">کد کارگو </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = mysqli_fetch_row($queryResult1)) {

                                        echo "<tr>";
                                        echo "<td> " . $row[0] . "</td>";
                                        echo "<td>" . $row[1] . "</td>";
                                        $picURL = str_replace(' ', '%20', $row[2]);
                                        $productLink = $row[3];
                                        echo "<td><a href=" . $productLink . "> <img src=" . $picURL . " class='img-rounded'" . "alt='بدون تصویر' width='100' height='100'> </a> </td>";
                                        echo "<td>" . $row[4] . "</td>";
                                        echo "<td>" . $row[5] . "</td>";
                                        echo "<td>" . $row[6] . "</td>";
                                        echo "<td>" . $row[7] . "</td>";
                                        echo "<td>" . $row[8] . "</td>";
                                        echo "<td>" . $row[9] . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                        $query5 = "select count(orders.orderID) FROM benneks.orders inner JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID inner JOIN benneks.cost ON orders.orderID = cost.orders_orderID inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID " .
                                "inner JOIN benneks.users ON orders.users_userID = users.userID where users.userID IN (SELECT users.userID FROM benneks.users) $searchQuery";
                        unset($_SESSION['searchQuery']);
                        $queryResult5 = $user->executeQuery($query5);
                        $records = mysqli_fetch_row($queryResult5);
                        $totalRecords = $records[0];
                        $totalPages = ceil($totalRecords / $limit);
                        echo "<div class='container'>";
                        echo "<ul class='pagination'>";
                        for ($i = 1; $i <= $totalPages; $i++) {
                            echo "<li><a href='admindetails.php?page=" . $i . "'>" . $i . "</a></li>";
                        }
                        echo "</ul>";
                        echo "</div>";
                        mysqli_close($user->conn);
                        ?>
                    </div>
                </div>
            </div>
        </div>
</body>
</html>


