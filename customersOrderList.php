<?php
/*
 * This page shows all orders for customer which are submited in the system
 */
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
// fetch order table for a user that owns curent session ID with pagination
$limit = 10;
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
    $query = "SELECT users.userID, orders.productPic, orders.orderDate, orders.orderID, members.customerCode, "
            . "members.customerName, stat.orderStatus, stat.orderStatusDescription, "
            . "shipment.cargoName, purchaseInfo.orderSalePrice, purchaseInfo.advancedPayment "
            . "FROM benneks.orders INNER JOIN benneks.users ON orders.users_userID = users.userID "
            . "INNER JOIN benneks.members ON members.customerCode = orders.members_customerCode "
            . "INNER JOIN benneks.stat ON stat.orders_orderID = orders.orderID "
            . "INNER JOIN benneks.shipment ON shipment.orders_orderID = orders.orderID "
            . "INNER JOIN benneks.purchaseInfo ON purchaseInfo.purchaseID = orders.purchaseInfo_purchaseID "
            . "WHERE users.userID = '$userID' $searchQuery ORDER BY orders.orderID DESC LIMIT  " . $startFrom . "," . $limit;
} else {
    $page = 1;
    $startFrom = ($page - 1) * $limit;
    $query = "SELECT users.userID, orders.productPic, orders.orderDate, orders.orderID, members.customerCode, "
            . "members.customerName, stat.orderStatus, stat.orderStatusDescription, "
            . "shipment.cargoName, purchaseInfo.orderSalePrice, purchaseInfo.advancedPayment "
            . "FROM benneks.orders INNER JOIN benneks.users ON orders.users_userID = users.userID "
            . "INNER JOIN benneks.members ON members.customerCode = orders.members_customerCode "
            . "INNER JOIN benneks.stat ON stat.orders_orderID = orders.orderID "
            . "INNER JOIN benneks.shipment ON shipment.orders_orderID = orders.orderID "
            . "INNER JOIN benneks.purchaseInfo ON purchaseInfo.purchaseID = orders.purchaseInfo_purchaseID "
            . "WHERE users.userID = '$userID' $searchQuery ORDER BY orders.orderID DESC LIMIT  " . $startFrom . "," . $limit;
};
if (!$user->executeQuery($query)) {
    echo mysqli_error($user->conn);
}
$queryResult = $user->executeQuery($query);

//Get totall numbers of today orders
$query3 = "SELECT count(orders.orderID) FROM benneks.orders INNER JOIN benneks.users ON orders.users_userID = users.userID WHERE orders.users_userID = '$userID' AND orders.orderDate = current_date()";
if (!$user->executeQuery($query3)) {
    echo mysqli_error($user->conn);
}
$queryResult3 = $user->executeQuery($query3);
$todayQuantity = mysqli_fetch_row($queryResult3);
//Get totall value(TL) of month orders
$query4 = "SELECT SUM(CAST(cost.benneksPrice AS decimal(8))), count(orders.orderID) FROM benneks.orders INNER JOIN benneks.users ON orders.users_userID = users.userID INNER JOIN benneks.cost ON cost.orders_orderID = orders.orderID WHERE orders.users_userID = '$userID' AND MONTH(orders.orderDate) = month(current_date())";
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
<!-- script for payment modal -->
<script>
    $(document).ready(function () {
        $(document).on("click", ".open-paymentModal", function () {
            var customerCode = $(this).data('id');
            $(".modal-body #customerCode").val(customerCode);
        });
    });
</script>
<!-- script for add modal -->
<script>
    $(document).ready(function () {
        $(document).on("click", ".open-delModal", function () {
            var orderID = $(this).data('id');
            $(".modal-body #rowID").val(orderID);
        });
    });
</script>
<!-- script for avaılable modal -->
<script>
    $(document).ready(function () {
        $(document).on("click", ".open-availableModal", function () {
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
                            <a href="home.php#orderPanel"> <i class="fa fa-tags fa-fw"></i>سفارش گذاری </a>
                        </li>
                        <li>
                            <a href="calculator.php"> <i class="fa fa-calculator fa-fw"></i> ماشین حساب </a>
                        </li>
                        <li>
                            <a href="orderlist.php"> <i class="fa fa-th-list fa-fw"> </i> لیست سفارشات </a>
                        </li>
                        <li>
                            <a href="#printKargoModal" data-toggle='modal' data-target='#printKargoModal' class='open-printKargoModal'> <i class="fa fa-print fa-fw"> </i> پرینت کارگو </a>
                        </li>
                        <li>
                            <a href="#printCustomerExcelModal" data-toggle='modal' data-target='#printCustomerExcelModal' class='open-printCustomerExcelModal'> <i class="fa fa-file-excel-o fa-fw" > </i> اکسل فروش</a>
                        </li>
                        <li>
                            <a href="customersOrderList.php"> <i class="fa fa-list fa-fw" > </i> سفارشات مشتریان</a>
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
                                <center> <i class="fa fa-shopping-bag fa-fw"></i> لیست سفارشات  <a href="orderlist.php"> <i class="fa fa-refresh fa-fw"></i></a></center> 
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" dir="rtl">
                                <div class="panel panel-primary" dir="rtl">
                                    <div class="panel-heading">
                                        <i class="fa fa-exchange fa-fw"></i> خلاصه وضعیت:
                                        <div class="form-inline">
                                            <div class="form-group">
                                                <label for="dayQuantity">  تعداد سفارشات امروز شما:</label>
                                                <label id="dayQuantity" style="color: goldenrod"> <?php echo $todayQuantity[0]; ?>  </label> &nbsp &nbsp
                                            </div>
                                            <div class="form-group">
                                                <label for="yesterdayQuantuty">  تعداد سفارشات شما در این ماه(میلادی):</label>
                                                <label id="yesterdayQuantuty" style="color: goldenrod"> <?php echo $monthValue[1]; ?> </label> &nbsp &nbsp
                                            </div>
                                            <div class="form-group">
                                                <label for="monthQuantity">  سفارشات شما در ماه میلادی(تومان):</label>
                                                <label id="monthQuantity" style="color: goldenrod"> <?php echo $monthValue[0]; ?> </label>  &nbsp &nbsp
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" dir="rtl">
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
                                                    <option value="customerCode" selected> کد مشتری </option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <input type="hidden" name="searchReq" value="customersOrderList"/>
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
                    <div class="panel-heading">
                        <i class="fa fa-shopping-bag fa-fw"></i> لیست سفارشات
                    </div>
                    <!-- /.list-panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped" id="orderTable">
                                <th style="text-align: center"> عکس</th>
                                <th style="text-align: center"> تاریخ</th>
                                <th style="text-align: center"> کد سفارش</th>
                                <th style="text-align: center"> کد مشتری</th>
                                <th style="text-align: center"> نام مشتری</th>
                                <th style="text-align: center"> وضعیت</th>
                                <th style="text-align: center"> جزئیات</th>
                                <th style="text-align: center">کد کارگو </th>
                                <th style="text-align: center"> قیمت فروش </th>
                                <th style="text-align: center"> بیعانه</th>
                                <th style="text-align: center"> مانده</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = mysqli_fetch_row($queryResult)) {
                                        echo "<tr>";
                                        $picURL = str_replace(' ', '%20', $row[1]);
                                        echo "<td> <img src = " . $picURL . " class='img-rounded'" . "alt='بدون تصویر' width='100' height='100'> </a> </td>";
                                        echo "<td>" . $row[2] . "</td>";
                                        echo "<td>" . $row[3] . "</td>";
                                        echo "<td>" . $row[4] .
                                        "<hr> "
                                        . "<a href='#paymentModal' data-toggle='modal' data-target='#paymentModal' data-id='$row[4]' class='open-paymentModal' > <i class='fa fa-money fa-fw fa-lg'></i> </a>"
                                        . " </td>";
                                        echo "<td>" . $row[5] . "</td>";
                                        echo "<td>" . $row[6] . "</td>";
                                        echo "<td>" . $row[7] . "</td>";
                                        echo "<td>" . $row[8] . "</td>";
                                        echo "<td>" . $row[9] . "</td>";
                                        echo "<td>" . $row[10] . "</td>";
                                        $remaining = intval($row[9]) - intval($row[10]);
                                        echo "<td>" . $remaining . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                        //Pagination and query to get data
                        $query2 = "SELECT count(orders.orderID), users.userID, orders.productPic, orders.orderDate, orders.orderID, members.customerCode, "
                                . "members.customerName, stat.orderStatus, stat.orderStatusDescription, "
                                . "shipment.cargoName, purchaseInfo.orderSalePrice, purchaseInfo.advancedPayment "
                                . "FROM benneks.orders INNER JOIN benneks.users ON orders.users_userID = users.userID "
                                . "INNER JOIN benneks.members ON members.customerCode = orders.members_customerCode "
                                . "INNER JOIN benneks.stat ON stat.orders_orderID = orders.orderID "
                                . "INNER JOIN benneks.shipment ON shipment.orders_orderID = orders.orderID "
                                . "INNER JOIN benneks.purchaseInfo ON purchaseInfo.purchaseID = orders.purchaseInfo_purchaseID "
                                . "WHERE users.userID = '$userID' $searchQuery ORDER BY orders.orderID DESC";
                        $queryResult2 = $user->executeQuery($query2);
                        $records = mysqli_fetch_row($queryResult2);
                        $totalRecords = $records[0];
                        $totalPages = ceil($totalRecords / $limit);
                        echo "<div class='container'>";
                        echo "<ul class='pagination'>";
                        for ($i = 1; $i <= $totalPages; $i++) {
                            echo "<li><a href='customersOrderList.php?page=" . $i . "'>" . $i . "</a></li>";
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
                                    hrefTextPrefix: 'customersOrderList.php?page='
                                });
                            });
                        </script>
                        <?php
                        echo "</div>";
                        ?>
                    </div>
                </div>

                <!--print kargo modal -->
                <div class = "modal fade" id = "printKargoModal" role="dialog">
                    <div class="modal-dialog">
                        <!--modal content -->
                        <div class="modal-content">
                            <div class="modal-header" style="padding: 35px 50px;">
                                <button type="button" class="close" data-dismiss = "modal">&times; </button>
                                <h4><span class = "glyphicon glyphicon-print"> </span> پرینت کارگو</h4>
                            </div>
                            <div class="modal-body" style="padding:40px 50px;">
                                <form role="form" action="printUsersKargo.php" method="post" dir="rtl">
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
                <!-- customer excel modal -->
                <div class = "modal fade" id = "printCustomerExcelModal" role="dialog">
                    <div class="modal-dialog">
                        <!--modal content -->
                        <div class="modal-content">
                            <div class="modal-header" style="padding: 35px 50px;">
                                <button type="button" class="close" data-dismiss = "modal">&times; </button>
                                <h4><span class = "glyphicon glyphicon-print"> </span> گزارش اکسل فروش مشتریان</h4>
                            </div>
                            <div class="modal-body" style="padding:40px 50px;">
                                <form role="form" action="customerExcelFile.php" method="post" dir="rtl">
                                    <div class="form-group">
                                        <label for="startDate"> تاریخ شروع: </label>
                                        <input type="date" value="2018-05-04" class="form-control" name="startDate" id="startDate">
                                    </div>
                                    <div class="form-group">
                                        <label for="finishDate"> تاریخ پایان: </label>
                                        <input type="date" class="form-control" name="finishDate" id="finishDate">
                                    </div>
                                    <button type="submit" class="btn btn-success btn-block" name="submitButton" id="submitButton"> دریافت فایل اکسل </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- payment modal -->

                <div class = "modal fade" id = "paymentModal" role="dialog">

                    <div class="modal-dialog">
                        <!--modal content -->
                        <div class="modal-content">
                            <?php
                            //if submit button in payment modal submited
                            if (isset($_POST['paymentSubmit'])) {
                                $customerCode = $_POST['customerCode'];
                                $paymentUploadDesc = $_POST['paymentUploadDesc'];
                                //find purchaseinfo description andd add payment description to it
                                $query1  = "SELECT purchaseInfo_purchaseID from benneks.orders where members_customerCode = '$customerCode'";
                                if(!$user->executeQuery($query1)) {
                                    echo mysqli_error($user->conn);
                                }
                                $queryResult1 = $user->executeQuery($query1);
                                $row = mysqli_fetch_array($queryResult1);
                                $purchaseID = $row[0];
                                $query2 = "SELECT paymentExtraDesc FROM benneks.purchaseinfo WHERE purchaseID = '$purchaseID'";
                                if(!$user->executeQuery($query2)) {
                                    echo mysqli_error($user->conn);
                                }
                                $queryResult2 = $user->executeQuery($query2);
                                $row = mysqli_fetch_array($queryResult2);
                                $paymentExtraDesc = $row[0];
                                $query3 = "UPDATE benneks.purchaseinfo SET purchaseExtraDesc = '$paymentExtraDesc + '/' + $paymentUploadDesc' WHERE purchaseID = $purchaseID ";
                                if(!$user->executeQuery($query3)) {
                                    echo mysqli_error($user->conn);
                                }
                                // create folder for each customer based on customerCode and upload the product pic into database
                                $userPaymentDir = $_SESSION['user'] . "-payment";
                                $customerDir = $userPaymentDir . '/' . $customerCode;
                                if (file_exists('paymentpics/' . $userPaymentDir . '/' . $customerCode)) {
                                    $targetDir = 'paymentpics/' . $userPaymentDir . '/' . $customerCode . '/';
                                } else {
                                    mkdir('paymentpics/' . $userPaymentDir . '/' . $customerCode);
                                    $targetDir = 'paymentpics/' . $userPaymentDir . '/' . $customerCode . '/';
                                }

                                $fileName = time() . rand(11, 99) . basename($_FILES['paymentpic']['name']);
                                $targetPath1 = $targetDir . $fileName;
                                echo $targetPath1;
                                move_uploaded_file($_FILES['paymentpic']["tmp_name"], $targetPath1);
                            }
                            ?>

                            <div class="modal-header" style="padding: 35px 50px;">
                                <button type="button" class="close" data-dismiss = "modal">&times; </button>
                                <h4><span class = "glyphicon glyphicon-money"> </span> ورود اطلاعات واریز پول</h4>
                            </div>
                            <div class="modal-body" style="padding:40px 50px;">
                                <form role="form" method="post" dir="rtl" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="customerCode"> کد مشتری </label>
                                        <input type="text" class="form-control" name="customerCode" id="customerCode" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="paymentpic"> عکس فیش واریزی</label>
                                        <input type="file" class="eng-format" id = "paymentpic" name = "paymentpic" accept="image/*">
                                    </div>
                                    <div class="form-group">
                                        <label for="paymentUploadDesc">توضیحات:</label>
                                        <textarea rows="4" cols="50" dir="rtl" class="form-control eng-format" id="paymentUploadDesc" name="paymentUploadDesc"> </textarea>
                                    </div>
                                    <input type="submit" class="btn btn-success btn-block" name="paymentSubmit" id="paymentSubmit"> 
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </body>
            </html>




