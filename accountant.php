<?php
/*
 * This page is responsible to provide financial information for accountant
 */
ob_start();
session_start();
require 'src/benneks.php';
// if accountant session is not set this will get access denied msg
if ($_SESSION['userAccess'] !== '4') {
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
    $query1 = "SELECT users.userName, users.userID, orders.orderDate, members.customerName, members.customerCode, members.customerSocialID, "
            . "orders.orderID, cost.benneksPrice, purchaseInfo.orderSalePrice, purchaseInfo.advancedPayment, members.paymentLink, orders.orderTime, stat.orderStatus, purchaseinfo.paymentExtraDesc "
            . "FROM benneks.orders INNER JOIN benneks.members ON orders.members_customerCode = members.customerCode "
            . "INNER JOIN benneks.cost ON orders.orderID = cost.orders_orderID "
            . "INNER JOIN benneks.purchaseInfo ON orders.purchaseInfo_purchaseID = purchaseInfo.purchaseID "
            . "INNER JOIN benneks.stat ON stat.orders_orderID = orders.orderID "
            . "INNER JOIN benneks.users ON orders.users_userID = users.userID $searchQuery ORDER BY orderDate DESC, orderTime DESC LIMIT " . $startFrom . "," . $limit;
} else {
    $page = 1;
    $startFrom = ($page - 1) * $limit;
    $query1 = "SELECT users.userName, users.userID, orders.orderDate, members.customerName, members.customerCode, members.customerSocialID, "
            . "orders.orderID, cost.benneksPrice, purchaseInfo.orderSalePrice, purchaseInfo.advancedPayment, members.paymentLink, orders.orderTime, stat.orderStatus, purchaseinfo.paymentExtraDesc "
            . "FROM benneks.orders INNER JOIN benneks.members ON orders.members_customerCode = members.customerCode "
            . "INNER JOIN benneks.cost ON orders.orderID = cost.orders_orderID "
            . "INNER JOIN benneks.purchaseInfo ON orders.purchaseInfo_purchaseID = purchaseInfo.purchaseID "
            . "INNER JOIN benneks.stat ON stat.orders_orderID = orders.orderID "
            . "INNER JOIN benneks.users ON orders.users_userID = users.userID $searchQuery ORDER BY orderDate DESC, orderTime DESC LIMIT " . $startFrom . "," . $limit;
};

if (!$user->executeQuery($query1)) {
    echo "خطا! در نحوه نمایش اطلاعات.";
}
$queryResult1 = $user->executeQuery($query1);
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
<!-- printer jquery plugin -->
<script type="text/JavaScript" src="./Javascripts/jQuery.print.js" /></script>
<!--CSS Style-->
<link rel="stylesheet" type="text/css" href="style.css" />

<!-- Custom Fonts -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">

<title>Benneks Order System</title>
</head>
<body>
    <div id ="wrapper">
        <!-- Navigation Bar -->
        <div class="navbar navbar-default navbar-static-top" role = "navigation" style="margin-bottom: 0">
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
                            <a href="accountant.php"> <i class="fa fa-th-list fa-fw"> </i> لیست خرید ها </a>
                        </li>
                        <li>
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span class="fa fa-file-excel-o fa-fw"></span>
                                    Excel
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <li><a href="#accountantModal" data-toggle='modal' data-target='#accountantModal' class='open-accountantModal' > گزارش حسابداری</a></li>  
                                </ul>
                            </div>
                        </li>
                        <li>
                            <a href="logout.php?logout"> <i class="fa fa-sign-out fa-fw" > </i> خروج</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div id ="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <center> <h1 class="page-header" dir="rtl">پنل حسابداری <a href="accountant.php"> <i class="fa fa-refresh fa-fw"></i> </a></h1> </center>
                </div>
                <div class="panel panel-success" dir="rtl" >
                    <div class="panel-heading">
                        <div class="row"> 
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="panel panel-primary">

                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">

                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 col-lg col-md col-sm">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <form role = "form" method="post" name="searchForm" id="searchForm"  action="search.php">
                                            <i class="fa fa-star fa-fw"></i> جستجو کد:
                                            <input type="search" class = "form-control" dir="ltr" id="searchInput" name="searchInput" placeholder="search...">
                                            <label for="searchOption"> <i class="fa fa-filter fa-fw"></i> انتخاب فیلتر</label>
                                            <div class="form-group">
                                                <select class = "form-control" id = "searchOption" name="searchOption">
                                                    <option value="code"> کد </option>
                                                    <option value="customerCode"> کد مشتری </option>
                                                    <option value="sellerName"> کد فروشنده</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <input type="hidden" name="searchReq" value="accountant"/>
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
                                        <th style="text-align: center"> نام فروشنده</th>
                                        <th style="text-align: center"> تاریخ</th>
                                        <th style="text-align: center"> زمان</th>
                                        <th style="text-align: center"> نام مشتری</th>
                                        <th style="text-align: center"> کد مشتری</th>
                                        <th style="text-align: center"> آیدی مشتری</th>
                                        <th style="text-align: center"> کد کالا</th>    
                                        <th style="text-align: center">قیمت خرید </th>
                                        <th style="text-align: center">قیمت فروش </th>
                                        <th style="text-align: center">بیعانه </th>
                                        <th style="text-align: center">لینک پرداخت </th>
                                        <th style="text-align: center">وضعیت </th>
                                        <th style="text-align: center"> توضیحات </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = mysqli_fetch_row($queryResult1)) {

                                        echo "<tr>";
                                        echo "<td>" . $row[0] . "</td>";
                                        echo "<td>" . $row[2] . "</td>";
                                        echo "<td>" . $row[11] . "</td>";
                                        echo "<td>" . $row[3] . "</td>";
                                        echo "<td>" . $row[4] . "</td>";
                                        echo "<td>" . $row[5] . "</td>";
                                        echo "<td>" . $row[6] . "</td>";
                                        echo "<td>" . $row[7] . "</td>";
                                        echo "<td>" . $row[8] . "</td>";
                                        echo "<td>" . $row[9] . "</td>";
                                        echo "<td> <a href= " . $row[10] . ">Link" . "</a> </td>";
                                        echo "<td>" . $row[12] . "</td>";
                                        echo "<td>" . $row[13] . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <!--accountant information modal -->
                        <div class = "modal fade" id = "accountantModal" role="dialog">
                            <div class="modal-dialog">
                                <!--modal content -->
                                <div class="modal-content">
                                    <div class="modal-header" style="padding: 35px 50px;">
                                        <button type="button" class="close" data-dismiss = "modal">&times; </button>
                                        <h4><span class = "glyphicon glyphicon-print"> </span> اطلاعات حسابداری کارگو رسیده به ایران</h4>
                                    </div>
                                    <div class="modal-body" style="padding:40px 50px;">
                                        <form role="form" action="accountantExcel.php" method="post" dir="rtl">
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
                        <?php
                        $query5 = "SELECT COUNT(orders.orderID), COUNT(users.userID) FROM benneks.orders INNER JOIN benneks.members ON "
                                . "members.customerCode = orders.members_customerCode INNER JOIN benneks.users "
                                . "ON orders.users_userID = users.userID $searchQuery";

                        if (!$user->executeQuery($query5)) {
                            echo mysqli_error($user->conn);
                        }
                        $queryResult5 = $user->executeQuery($query5);
                        $records = mysqli_fetch_row($queryResult5);
                        $totalRecords = $records[0];
                        $totalPages = ceil($totalRecords / $limit);
                        echo "<div class='container'>";
                        echo "<ul class='pagination'>";
                        for ($i = 1; $i <= $totalPages; $i++) {
                            echo "<li><a href='accountant.php?page=" . $i . "'>" . $i . "</a></li>";
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
                                    hrefTextPrefix: 'accountant.php?page='
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
    </div>
</body>
</html>