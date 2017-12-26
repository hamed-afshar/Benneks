<?php
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
$limit = 30;
$userID = $_SESSION['user'];


// if search button submited then search query will be created
if (isset($_SESSION['searchQuery'])) {
    $searchQuery = "WHERE " . $_SESSION['searchQuery'];
} else {
    $searchQuery = "";
}
if (isset($_GET["page"])) {
    $page = $_GET["page"];
    $startFrom = ($page - 1) * $limit;
    $query1 = "SELECT orders.orderID, users.username ,orders.orderDate, orders.orderTime ,orders.productPrice, orders.productBrand, orders.productLink, orders.productPic, orders.clothesType, orders.productSize, orders.productColor, orders.orderQuantity, orders.country, stat.orderStatus, stat.orderStatusDescription FROM benneks.orders INNER JOIN benneks.stat ON stat.orders_orderID = orders.orderID INNER JOIN benneks.users ON users.userID = orders.users_userID $searchQuery ORDER BY orders.orderDate desc, orders.orderTime desc LIMIT " . $startFrom . "," . $limit;
} else {
    $page = 1;
    $startFrom = ($page - 1) * $limit;
    $query1 = "SELECT orders.orderID, users.username ,orders.orderDate, orders.orderTime ,orders.productPrice, orders.productBrand, orders.productLink, orders.productPic, orders.clothesType, orders.productSize, orders.productColor, orders.orderQuantity, orders.country ,stat.orderStatus, stat.orderStatusDescription FROM benneks.orders INNER JOIN benneks.stat ON stat.orders_orderID = orders.orderID INNER JOIN benneks.users ON users.userID = orders.users_userID $searchQuery ORDER BY orders.orderDate desc, orders.orderTime desc LIMIT " . $startFrom . "," . $limit;
};
//unset($_SESSION['searchQuery']);
if (!$user->executeQuery($query1)) {
    echo "خطا! در نحوه نمایش اطلاعات.";
}
$queryResult1 = $user->executeQuery($query1);
//Get totall value and numbers for yesterday orders
$query2 = "SELECT FirstSet.turkeySUM, FirstSet.turkeyCount, SecondSet.frSUM, SecondSet.frCount FROM " .
        "(SELECT SUM(CAST(orders.productPrice AS decimal(5,2))) AS turkeySUM, count(orders.orderID) AS turkeyCount FROM benneks.orders WHERE orders.orderDate = subdate(current_date(), 1) AND orders.country = 'ترکیه') as FirstSet " .
        "INNER JOIN " .
        "(SELECT SUM(CAST(orders.productPrice AS decimal(5,2))) AS frSUM, count(orders.orderID) AS frCount FROM benneks.orders WHERE orders.orderDate = subdate(current_date(), 1) AND orders.country = 'فرانسه') as SecondSet";
if (!$user->executeQuery($query2)) {
    echo mysqli_error($user->conn);
}
$queryResult2 = $user->executeQuery($query2);
$yesterdayValue = mysqli_fetch_row($queryResult2);

//Get totall value and numbers for Today orders
$query3 = "SELECT FirstSet.turkeySUM, FirstSet.turkeyCount, SecondSet.frSUM, SecondSet.frCount FROM " .
        "(SELECT SUM(CAST(orders.productPrice AS decimal(5,2))) AS turkeySUM, count(orders.orderID) AS turkeyCount FROM benneks.orders WHERE orders.orderDate = current_date() AND orders.country = 'ترکیه') as FirstSet " .
        "INNER JOIN " .
        "(SELECT SUM(CAST(orders.productPrice AS decimal(5,2))) AS frSUM, count(orders.orderID) AS frCount FROM benneks.orders WHERE orders.orderDate = current_date() AND orders.country = 'فرانسه') as SecondSet";
if (!$user->executeQuery($query3)) {
    echo mysqli_error($user->conn);
}
$queryResult3 = $user->executeQuery($query3);
$todayValue = mysqli_fetch_row($queryResult3);
//Get totall value and numbers for month orders
$query4 = "SELECT FirstSet.turkeySUM, FirstSet.turkeyCount, SecondSet.frSUM, SecondSet.frCount FROM " .
        "(SELECT SUM(CAST(orders.productPrice AS decimal(5,2))) AS turkeySUM, count(orders.orderID) AS turkeyCount FROM benneks.orders WHERE MONTH(orders.orderDate) = month(current_date()) AND orders.country = 'ترکیه') as FirstSet " .
        "INNER JOIN " .
        "(SELECT SUM(CAST(orders.productPrice AS decimal(5,2))) AS frSUM, count(orders.orderID) AS frCount FROM benneks.orders WHERE MONTH(orders.orderDate) = MONTH(current_date()) AND orders.country = 'فرانسه') as SecondSet";
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

<!-- script for change Kargo modal -->
<script>
    $(document).ready(function () {
        $(document).on("click", ".open-changeKargoModal", function () {
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
<!-- script for return modal -->
<script>
    $(document).ready(function () {
        $(document).on("click", ".open-returnModal", function () {
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
                            <a href="#"> <i class="fa fa-money fa-fw" > </i> انتقال وجه</a>
                        </li>
                        <li>
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span class="fa fa-file-excel-o fa-fw"></span>
                                    اکسل
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <li><a href='#orderListMakerModal' data-toggle='modal' data-target='#orderListMakerModal' class='open-addModal' >سفارش ساز</a></li>
                                    <li><a href="#"> گزارش کارگو</a></li>
                                    <li><a href="#finalReportModal" data-toggle='modal' data-target='#finalReportModal' class='open-finalReportModal' > گزارش جامع</a></li>
                                    <li><a href="#iranPrintModal" data-toggle='modal' data-target='#iranPrintModal' class='open-iranPrintModal' > لیست رسیده به ایران</a></li>
                                </ul>

                            </div>
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
                                                <label for="dayQuantity"> سفارشات امروز لیر:</label>
                                                <label id="dayQuantity" style="color: goldenrod"> <?php echo $todayValue[0]; ?>  </label>  
                                            </div>
                                            <div class="form-group">
                                                <label for="yesterdayQuantuty"> سفارشات روز گذشته لیر :</label>
                                                <label id="yesterdayQuantuty" style="color: goldenrod"> <?php echo $yesterdayValue['0']; ?> </label>  
                                            </div>
                                            <div class="form-group">
                                                <label for="monthQuantity"> ماه لیر:</label>
                                                <label id="monthQuantity" style="color: goldenrod"> <?php echo $monthValue[0]; ?> </label> 
                                            </div>
                                            <div class="form-group">
                                                <label for="dayQuantity"> امروز یورو:<br></label>
                                                <label id="dayQuantity" style="color: goldenrod"> <?php echo $todayValue[2]; ?>  </label>  
                                            </div>
                                            <div class="form-group">
                                                <label for="yesterdayQuantuty"> روز گذشته یورو :</label>
                                                <label id="yesterdayQuantuty" style="color: goldenrod"> <?php echo $yesterdayValue['2']; ?> </label>  
                                            </div>
                                            <div class="form-group">
                                                <label for="monthQuantity"> ماه یورو:</label>
                                                <label id="monthQuantity" style="color: goldenrod"> <?php echo $monthValue[2]; ?> </label> 
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
                                                <label for="dayQuantity"> امروز ترکیه:</label>
                                                <label id="dayQuantity" style="color: goldenrod"> <?php echo $todayValue[1]; ?> </label> 
                                            </div>
                                            <div class="form-group">
                                                <label for="yesterdayQuantuty"> سفارشات روز گذشته ترکیه:</label>
                                                <label id="yesterdayQuantuty" style="color: goldenrod"> <?php echo $yesterdayValue[1]; ?> </label> 
                                            </div>
                                            <div class="form-group">
                                                <label for="monthQuantity"> ماه ترکیه:</label>
                                                <label id="monthQuantity" style="color: goldenrod"> <?php echo $monthValue[1]; ?> </label> 
                                            </div>
                                            <div class="form-group">
                                                <label for="dayQuantity"> امروز فرانسه:</label>
                                                <label id="dayQuantity" style="color: goldenrod"> <?php echo $todayValue[3]; ?> </label> 
                                            </div>
                                            <div class="form-group">
                                                <label for="yesterdayQuantuty"> روز گذشته فرانسه:</label>
                                                <label id="yesterdayQuantuty" style="color: goldenrod"> <?php echo $yesterdayValue[3]; ?> </label> 
                                            </div>
                                            <div class="form-group">
                                                <label for="monthQuantity"> ماه فرانسه:</label>
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
                                                    <option value="turkey"> ترکیه</option>
                                                    <option value="uk"> انگلیس</option>
                                                    <option value="fr"> فرانسه</option>
                                                    <option value="cancel"> لغو شده</option>
                                                    <option value="return"> عودت</option>
                                                    <option value="unknown"> نامشخص </option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <input type="hidden" name="searchReq" value="adminPage"/>
                                            </div>
                                            <div class="form-group">
                                                <button class="form-control btn btn-group btn-success" id="searchButton" name="searchButton" > جستجو
                                                    <span>
                                                        <i class="fa fa-search"> </i>
                                                    </span>
                                                </button>
                                            </div>
                                            <div class="form-group">
                                                <button class="form-control btn btn-group btn-danger" id="cancelSearchButton" name="cancelSearchButton" > لغو جستجو
                                                    <span>
                                                        <i class="fa fa-ban"> </i>
                                                    </span>
                                                </button>
                                            </div>
                                        </form>                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.list-panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped" id="orderDataTable" style="text-align:center; overflow-x: scroll">
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
                                        <th style="text-align: center">نوع لباس </th>
                                        <th style="text-align: center">سایز </th>
                                        <th style="text-align: center">رنگ </th>
                                        <th style="text-align: center">تعداد </th>
                                        <th style="text-align: center">کشور </th>
                                        <th style="text-align: center">وضعیت </th>
                                        <th style="text-align: center">جزئیات </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = mysqli_fetch_row($queryResult1)) {

                                        echo "<tr>";
                                        echo "<td> " . $row[0] .
                                        "<hr> "
                                        . "<a href='#changeKargoModal' data-toggle='modal' data-target='#changeKargoModal' data-id='$row[0]' class='open-changeKargoModal' > <i class='fa fa-pencil fa-fw fa-lg'></i> </a>"
                                        . "<a href='#cancelModal' data-toggle='modal' data-target='#iranDeliverModal' data-id='$row[0]' class='open-iranDeliverModal'> <i class='fa fa-plane fa-fw fa-lg'></i> </a>"
                                        . "<a href='#returnModal' data-toggle='modal' data-target='#returnModal' data-id='$row[0]' class='open-returnModal'> <i class='fa fa-exchange fa-fw fa-lg'></i> </a>"
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
                                        echo "<td>" . $row[12] . "</td>";
                                        echo "<td>" . $row[13] . "</td>";
                                        echo "<td>" . $row[14] . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                        $query5 = "SELECT COUNT(orders.orderID) FROM benneks.orders INNER JOIN benneks.stat ON stat.orders_orderID = orders.orderID INNER JOIN benneks.users ON users.userID = orders.users_userID $searchQuery";
                        $queryResult5 = $user->executeQuery($query5);
                        $records = mysqli_fetch_row($queryResult5);
                        $totalRecords = $records[0];
                        $totalPages = ceil($totalRecords / $limit);
                        echo "<div class='container'>";
                        echo "<ul class='pagination'>";
                        for ($i = 1; $i <= $totalPages; $i++) {
                            echo "<li><a href='admin.php?page=" . $i . "'>" . $i . "</a></li>";
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
                                    hrefTextPrefix: 'admin.php?page='
                                });
                            });
                        </script>
                        <?php
                        echo "</div>";
                        ?>
                    </div>
                </div>
                <!--Iran Deliver order modal -->
                <div class = "modal fade" id = "iranDeliverModal" role="dialog">
                    <div class="modal-dialog">
                        <!--modal content -->
                        <div class="modal-content">
                            <div class="modal-header" style="padding: 35px 50px;">
                                <button type="button" class="close" data-dismiss = "modal">&times; </button>
                                <h4><span class = "glyphicon glyphicon-plane"> </span> ارسال به ایران </h4>
                            </div>
                            <div class="modal-body" style="padding:40px 50px;">
                                <form role="form"  method="post" action="irandeliver.php" dir="rtl">
                                    <div class="form-group">
                                        <label for="cargoName"> <span class="glyphicon glyphicon-road"></span> کد کارگو </label> 
                                        <input type="text" class="form-control" name="cargoName" id="cargoName" onclick="return false;">
                                    </div>
                                    <div class="form-group">
                                        <label for="iranDeliverDate"><span class="glyphicon glyphicon-calendar"></span>  تاریخ رسیدن به ایران:</label>
                                        <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" name="iranArrivalDate" id="iranArrivalDate"> 
                                    </div>
                                    <div class="form-group">
                                        <label for="rowID"> <span class="glyphicon glyphicon-asterisk"></span> کد سفارش </label>
                                        <input type="text" class="form-control" name="rowID" id="rowID" onclick="return false;">
                                    </div>

                                    <button type="button" class="btn btn-success btn-block" name="searchButton" id="searchButton" onclick="iranDeliverFunc('search');"> بررسی و ثبت </button>
                                    <div class="form-group">
                                        <center> <p id="iranDeliverMsg">  </p> </center>
                                    </div>
                                    <div class="form-group">
                                        <center> <p id="counterMsg" style="color: black">  </p> </center>
                                    </div>
                                    <div class="form-group">
                                        <center> <p id="counterErrorMsg" style="color: red">  </p> </center>
                                    </div>
                                    <button type="button" class="btn btn-danger btn-block" id="Not-changeCargoButton" onclick="$('#iranDeliverModal').modal('hide');"> انصراف </button>   
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!--change kargo modal -->
                <div class = "modal fade" id = "changeKargoModal" role="dialog">
                    <div class="modal-dialog">
                        <!--modal content -->
                        <div class="modal-content">
                            <div class="modal-header" style="padding: 35px 50px;">
                                <button type="button" class="close" data-dismiss = "modal">&times; </button>
                                <h4><span class = "glyphicon glyphicon-pencil"> </span> تغییر کارگو </h4>
                            </div>
                            <div class="modal-body" style="padding:40px 50px;">
                                <form role="form"  method="post" action="changekargo.php" dir="rtl">
                                    <div class="form-group">
                                        <label for="rowID"> <span class="glyphicon glyphicon-asterisk"></span> کد سفارش </label>
                                        <input type="text" class="form-control" name="rowID" id="rowID" readonly="" onclick="return false;">
                                    </div>
                                    <div class="form-group">
                                        <label for="cargoName"> <span class="glyphicon glyphicon-road"></span> کد کارگو </label> 
                                        <input type="text" class="form-control" name="cargoName" id="cargoName" onclick="return false;">
                                    </div>          
                                    <button type="submit" class="btn btn-success btn-block" name="submitButton" id="submitButton"> ثبت </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!--orderlist Maker Modal -->
                <div class = "modal fade" id = "orderListMakerModal" role="dialog">
                    <div class="modal-dialog">
                        <!--modal content -->
                        <div class="modal-content">
                            <div class="modal-header" style="padding: 35px 50px;">
                                <button type="button" class="close" data-dismiss = "modal">&times; </button>
                                <h4><span class = "glyphicon glyphicon-list-alt"> </span> ساخت لیست سفارشات </h4>
                            </div>
                            <div class="modal-body" style="padding:40px 50px;">
                                <form role="form" action="orderlistmaker.php" method="post" dir="rtl">
                                    <div class="form-group">
                                        <label for="orderID"> شروع از تاریخ: </label>
                                        <input type="date" class="form-control" name="orderDate" id="orderDate">
                                    </div>
                                    <div class="form-group">
                                        <label for="orderID"> شروع از ساعت: </label>
                                        <input type="time" class="form-control" name="orderTime" id="orderTime">
                                    </div>
                                    <div class="form-group">
                                        <label for="country"><span class="glyphicon glyphicon-flag"></span>  کشور:</label>
                                        <select dir = "rtl" class = "form-control" id = "country" name="country"> 
                                            <option value = "انگلیس">انگلیس</option>
                                            <option value = "ترکیه">ترکیه</option>
                                            <option value = "فرانسه">فرانسه</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-block" name="submitButton" id="submitButton"> ایجاد فایل اکسل </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Return Modal -->
                <div class = "modal fade" id = "returnModal" role="dialog">
                    <div class="modal-dialog">
                        <!--modal content -->
                        <div class="modal-content">
                            <div class="modal-header" style="padding: 35px 50px;">
                                <button type="button" class="close" data-dismiss = "modal">&times; </button>
                                <h4><span class = "glyphicon glyphicon-refresh"> </span> عودت محصول در ایران </h4>
                            </div>
                            <div class="modal-body" style="padding:40px 50px;">
                                <form role="form" action="returniran.php" method="post" dir="rtl">
                                    <div class="form-group">
                                        <label for="rowID"> کد سفارش </label>
                                        <input type="text" class="form-control" name="rowID" id="rowID">
                                    </div>
                                    <div class="form-group">
                                        <label for="returnReason"><span class="glyphicon glyphicon-hand-left"></span>  دلیل عودت سفارش</label>
                                        <select dir = "rtl" class = "form-control" id = "returnReason" name="returnReason"> 
                                            <option value = "دیر رسیدن کالا">دیر رسیدن کالا </option>
                                            <option value = "اشتباه بودن محصول">اشتباه بودن محصول</option>
                                            <option value = "خراب بودن محصول">خراب بودن محصول</option>
                                            <option value = "تعویض با جنس دیگر">تعویض با جنس دیگر</option>
                                            <option value = "دیر رسیدن کالا">دیر رسیدن کالا </option>
                                            <option value = "به درخواست کاربر">به درخواست کاربر </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="returnDescription"><span class="glyphicon glyphicon-comment"></span>  توضیحات</label>
                                        <input type="text" class="form-control" name="returnComment" id="returnComment"> 
                                    </div>

                                    <button type="submit" class="btn btn-success btn-block" name="submitButton" id="submitButton"> ثبت </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!--finalReport Modal -->
                <div class = "modal fade" id = "finalReportModal" role="dialog">
                    <div class="modal-dialog">
                        <!--modal content -->
                        <div class="modal-content">
                            <div class="modal-header" style="padding: 35px 50px;">
                                <button type="button" class="close" data-dismiss = "modal">&times; </button>
                                <h4><span class = "glyphicon glyphicon-refresh"> </span> گزارش جامع </h4>
                            </div>
                            <div class="modal-body" style="padding:40px 50px;">
                                <form role="form" action="finalreport.php" method="post" dir="rtl">
                                    <div class="form-group">
                                        <label for="startDate"> تاریخ شروع گزارش: </label>
                                        <input type="date" class="form-control" name="startDate" id="startDate">
                                    </div>
                                    <div class="form-group">
                                        <label for="finishDate"> تاریخ پایان گزارش: </label>
                                        <input type="date" class="form-control" name="finishDate" id="finishDate">
                                    </div>
                                    <div class="form-group">
                                        <label for="countryReport"><span class="glyphicon glyphicon-hand-left"></span>  کشور</label>
                                        <select dir = "rtl" class = "form-control" id = "countryReport" name="countryReport"> 
                                            <option value = "ترکیه">ترکیه</option>
                                            <option value = "انگلیس">انگلیس</option>
                                            <option value = "فرانسه">فرانسه</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-block" name="submitButton" id="submitButton"> ساخت گزارش </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!--print iran arrival modal -->
                <div class = "modal fade" id = "iranPrintModal" role="dialog">
                    <div class="modal-dialog">
                        <!--modal content -->
                        <div class="modal-content">
                            <div class="modal-header" style="padding: 35px 50px;">
                                <button type="button" class="close" data-dismiss = "modal">&times; </button>
                                <h4><span class = "glyphicon glyphicon-print"> </span> پرینت</h4>
                            </div>
                            <div class="modal-body" style="padding:40px 50px;">
                                <form role="form" action="printIranArrival.php" method="post" dir="rtl">
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
            </div>
        </div>
    </div>
</body>
</html>


