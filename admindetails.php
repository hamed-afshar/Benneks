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
    $query1 = "select orders.orderID, users.userName, orders.productPic, orders.productLink, orders.orderDate, orders.country, orders.productPrice, cost.rateTL, cost.originalTomanPrice ,cost.benneksPrice ,shipment.cargoName " .
            "FROM benneks.orders inner JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID inner JOIN benneks.cost ON orders.orderID = cost.orders_orderID inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID " .
            "inner JOIN benneks.users ON orders.users_userID = users.userID where users.userID IN (SELECT users.userID FROM benneks.users) $searchQuery  ORDER BY users.userName, orders.orderDate desc, orders.orderID desc LIMIT " . $startFrom . "," . $limit;
} else {
    $page = 1;
    $startFrom = ($page - 1) * $limit;
    $query1 = "select orders.orderID, users.userName, orders.productPic, orders.productLink ,orders.orderDate, orders.country, orders.productPrice, cost.rateTL, cost.originalTomanPrice ,cost.benneksPrice ,shipment.cargoName " .
            "FROM benneks.orders inner JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID inner JOIN benneks.cost ON orders.orderID = cost.orders_orderID inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID " .
            "inner JOIN benneks.users ON orders.users_userID = users.userID where users.userID IN (SELECT users.userID FROM benneks.users) $searchQuery  ORDER BY users.username, orders.orderDate desc, orders.orderID desc LIMIT " . $startFrom . "," . $limit;
};
// This session variable will be used in excel creator 
unset($_SESSION['searchQuery']);
$_SESSION['query1'] = $query1;
if (!$user->executeQuery($query1)) {
    echo mysqli_error($user->conn);
}
$queryResult1 = $user->executeQuery($query1);
//Get totall value(TL) and numbers for yesterday orders only for successfull orders. Cancelation and unknown status orders sustracted from this number.
$query2 = "SELECT FirstSet.turkeySUM, FirstSet.turkeyCount, SecondSet.ukSUM, secondSet.ukCount FROM "
        . "(SELECT SUM(CAST(orders.productPrice AS decimal(5,2))) AS turkeySUM, count(orders.orderID) AS turkeyCount FROM benneks.orders INNER JOIN benneks.stat ON orders.orderID = stat.orders_orderID WHERE orders.country = 'ترکیه' AND stat.orderStatus = 'انجام شده' AND orders.orderDate = subdate(current_date(), 1)) as FirstSet "
        . "INNER JOIN"
        . "(SELECT SUM(CAST(orders.productPrice AS decimal(5,2))) AS ukSUM, count(orders.orderID) AS ukCount FROM benneks.orders INNER JOIN benneks.stat ON orders.orderID = stat.orders_orderID WHERE orders.country = 'انگلیس' AND stat.orderStatus = 'انجام شده' AND orders.orderDate = subdate(current_date(), 1)) as SecondSet";
if (!$user->executeQuery($query2)) {
    echo mysqli_error($user->conn);
}
$queryResult2 = $user->executeQuery($query2);
$yesterdayValue = mysqli_fetch_row($queryResult2);
//Get totall value(TL) and numbers for Today orders only for successfull orders. Cancelation and unknown status orders sustracted from this number.
$query3 = "SELECT FirstSet.turkeySUM, FirstSet.turkeyCount, SecondSet.ukSUM, secondSet.ukCount FROM "
        . "(SELECT SUM(CAST(orders.productPrice AS decimal(5,2))) AS turkeySUM, count(orders.orderID) AS turkeyCount FROM benneks.orders INNER JOIN benneks.stat ON orders.orderID = stat.orders_orderID WHERE orders.country = 'ترکیه' AND stat.orderStatus = 'انجام شده' AND orders.orderDate = current_date()) as FirstSet "
        . "INNER JOIN"
        . "(SELECT SUM(CAST(orders.productPrice AS decimal(5,2))) AS ukSUM, count(orders.orderID) AS ukCount FROM benneks.orders INNER JOIN benneks.stat ON orders.orderID = stat.orders_orderID WHERE orders.country = 'انگلیس' AND stat.orderStatus = 'انجام شده' AND orders.orderDate = current_date()) as SecondSet";
if (!$user->executeQuery($query2)) {
    echo mysqli_error($user->conn);
}
$queryResult3 = $user->executeQuery($query3);
$todayValue = mysqli_fetch_row($queryResult3);
//Get totall value(TL) and numbers for month orders only for successfull orders. Cancelation and unknown status orders sustracted from this number.
$query4 = $query3 = "SELECT FirstSet.turkeySUM, FirstSet.turkeyCount, SecondSet.ukSUM, secondSet.ukCount FROM "
        . "(SELECT SUM(CAST(orders.productPrice AS decimal(5,2))) AS turkeySUM, count(orders.orderID) AS turkeyCount FROM benneks.orders INNER JOIN benneks.stat ON orders.orderID = stat.orders_orderID WHERE orders.country = 'ترکیه' AND stat.orderStatus = 'انجام شده' AND MONTH(orders.orderDate) = MONTH(current_date())) as FirstSet "
        . "INNER JOIN"
        . "(SELECT SUM(CAST(orders.productPrice AS decimal(5,2))) AS ukSUM, count(orders.orderID) AS ukCount FROM benneks.orders INNER JOIN benneks.stat ON orders.orderID = stat.orders_orderID WHERE orders.country = 'انگلیس' AND stat.orderStatus = 'انجام شده' AND MONTH(orders.orderDate) = MONTH(current_date())) as SecondSet";
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
<!-- Pagination jquery plugin -->
<script type="text/javascript" src="./Javascripts/jquery.simplePagination.js"></script>

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
                                                <label for="dayQuantity"> امروز لیر:</label>
                                                <label id="dayQuantity" style="color: goldenrod"> <?php echo $todayValue[0]; ?>  </label>  
                                            </div>
                                            <div class="form-group">
                                                <label for="yesterdayQuantuty"> روز گذشته لیر :</label>
                                                <label id="yesterdayQuantuty" style="color: goldenrod"> <?php echo $yesterdayValue['0']; ?> </label>  
                                            </div>
                                            <div class="form-group">
                                                <label for="monthQuantity"> ماه لیر:</label>
                                                <label id="monthQuantity" style="color: goldenrod"> <?php echo $monthValue[0]; ?> </label> 
                                            </div>
                                            <div class="form-group">
                                                <label for="dayQuantity"> امروز پوند:</label>
                                                <label id="dayQuantity" style="color: goldenrod"> <?php echo $todayValue[2]; ?>  </label>  
                                            </div>
                                            <div class="form-group">
                                                <label for="yesterdayQuantuty"> روز گذشته پوند :</label>
                                                <label id="yesterdayQuantuty" style="color: goldenrod"> <?php echo $yesterdayValue['2']; ?> </label>  
                                            </div>
                                            <div class="form-group">
                                                <label for="monthQuantity"> ماه پوند:</label>
                                                <label id="monthQuantity" style="color: goldenrod"> <?php echo $monthValue[2]; ?> </label> 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <i class="fa fa-circle fa-fw"></i> گزارشات:
                                        <a href='excelcreator.php'> <i class='fa fa-file-excel-o fa-fw'> </i> </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 col-lg-push-4 col-md-push-4 col-sm-push-4" dir="rtl">
                                <div class="panel panel-primary" dir="rtl">
                                    <div class="panel-heading">
                                        <i class="fa fa-bullhorn fa-fw"></i> تعداد سفارشات:
                                        <div class="form-inline">
                                            <div class="form-group">
                                                <label for="dayQuantity"> امروز ترکیه:</label>
                                                <label id="dayQuantity" style="color: goldenrod"> <?php echo $todayValue[1]; ?> </label> 
                                            </div>
                                            <div class="form-group">
                                                <label for="yesterdayQuantuty"> روز گذشته ترکیه:</label>
                                                <label id="yesterdayQuantuty" style="color: goldenrod"> <?php echo $yesterdayValue[1]; ?> </label> 
                                            </div>
                                            <div class="form-group">
                                                <label for="monthQuantity"> ماه ترکیه:</label>
                                                <label id="monthQuantity" style="color: goldenrod"> <?php echo $monthValue[1]; ?> </label> 
                                            </div>
                                            <div class="form-group">
                                                <label for="dayQuantity"> امروز پوند:</label>
                                                <label id="dayQuantity" style="color: goldenrod"> <?php echo $todayValue[3]; ?> </label> 
                                            </div>
                                            <div class="form-group">
                                                <label for="yesterdayQuantuty"> روز گذشته پوند:</label>
                                                <label id="yesterdayQuantuty" style="color: goldenrod"> <?php echo $yesterdayValue[3]; ?> </label> 
                                            </div>
                                            <div class="form-group">
                                                <label for="monthQuantity"> ماه انگلیس:</label>
                                                <label id="monthQuantity" style="color: goldenrod"> <?php echo $monthValue[3]; ?> </label> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 col-lg-pull-8 col-md-pull-8 col-sm-pull-8" dir="rtl">
                                <div class="panel panel-primary" dir="rtl">
                                    <div class="panel-heading">
                                        <form role = "form" method="post" name="searchForm" id="searchForm"  action="search.php">
                                            <i class="fa fa-filter fa-fw"></i> عبارت جستجو:
                                            <input type="search" class = "form-control" dir="ltr" id="searchInput" name="searchInput" placeholder="search...">
                                            <label for="searchOption"></span>  نوع فیلتر:</label>
                                            <div class="form-group">
                                                <select dir = "rtl" class = "form-control" id = "searchOption" name="searchOption">
                                                    <option value="code"> کد </option>
                                                    <option value="name"> نام </option>
                                                    <option value="done"> خریداری شده</option>
                                                    <option value="cargo"> کارگو</option>
                                                    <option value="turkey"> ترکیه</option>
                                                    <option value="uk"> انگلیس</option>
                                                    <option value="cancel"> لغو شده</option>
                                                    <option value="unknown"> نامشخص </option>
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
                                        <th style="text-align: center"> ردیف</th>
                                        <th style="text-align: center"> کد</th>
                                        <th style="text-align: center"> کاربر</th>
                                        <th style="text-align: center">  عکس</th>
                                        <th style="text-align: center"> تاریخ سفارش</th>
                                        <th style="text-align: center"> کشور</th>
                                        <th style="text-align: center"> قیمت سایت</th>
                                        <th style="text-align: center"> نرخ ارز</th>
                                        <th style="text-align: center"> قیمت اصلی(تومان)</th>
                                        <th style="text-align: center">قیمت محاسبه شده </th>
                                        <th style="text-align: center">کد کارگو </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Creating table
                                    $i = 1; // keep the row number
                                    while ($row = mysqli_fetch_row($queryResult1)) {
                                        echo "<tr>";
                                        echo "<td> " . $i . "</td>";
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
                                        echo "<td>" . $row[10] . "</td>";
                                        echo "</tr>";
                                        $i++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                        //Pagination
                        // query to get data
                        $query5 = "select count(orders.orderID) FROM benneks.orders inner JOIN benneks.shipment ON orders.orderID = shipment.orders_orderID inner JOIN benneks.cost ON orders.orderID = cost.orders_orderID inner JOIN benneks.stat ON orders.orderID = stat.orders_orderID " .
                                "inner JOIN benneks.users ON orders.users_userID = users.userID where users.userID IN (SELECT users.userID FROM benneks.users) $searchQuery";
                        $queryResult5 = $user->executeQuery($query5);
                        $records = mysqli_fetch_row($queryResult5);
                        $totalRecords = $records[0];
                        // calculate total pages and create links based on numbers of the pages
                        $totalPages = ceil($totalRecords / $limit);
                        echo "<div class='container'>";
                        echo "<ul class='pagination'>";
                        for ($i = 1; $i <= $totalPages; $i++) {
                            echo "<li><a href='admindetails.php?page=" . $i . "'>" . $i . "</a></li>";
                        }
                        echo "</ul>";
                        mysqli_close($user->conn);
                        ?>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $('.pagination').pagination({
                                    items: <?php echo $totalRecords; ?>,
                                    itemsOnPage: <?php echo $limit; ?>,
                                    cssStyle: 'light-theme',
                                    currentPage: <?php echo $page; ?>,
                                    hrefTextPrefix: 'admindetails.php?page='
                                });
                            });
                        </script>
                        <?php
                        echo "</div>";
                        ?>
                    </div>
                </div>
            </div>
        </div>
</body>
</html>


