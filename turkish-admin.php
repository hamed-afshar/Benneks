<?php
/*
 * Admin panel for Turkish admin user
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

//Get the totall number of orders from begining
$query2 = "select count(orders.orderID) from benneks.orders WHERE orders.country = 'ترکیه' and MONTH(orders.orderDate) = MONTH(NOW()) and YEAR(orders.orderDate) = YEAR(NOW());";
if (!$user->executeQuery($query2)) {
    echo mysqli_error($user->conn);
}
$queryResult2 = $user->executeQuery($query2);
$totallMonthValue = mysqli_fetch_row($queryResult2);

//Get the totall number of purchased orders for current month
$query3 = "select count(orders.orderID) from benneks.orders inner join benneks.stat on orders.orderID = stat.orders_orderID where orders.country = 'ترکیه' and stat.orderStatus = 'انجام شده-tamam' or stat.orderStatus = 'انجام شده'  and MONTH(orders.orderDate) = MONTH(NOW()) and YEAR(orders.orderDate) = YEAR(NOW());";
if (!$user->executeQuery($query3)) {
    echo mysqli_error($user->conn);
}
$queryResult3 = $user->executeQuery($query3);
$totallDoneMonthValue = mysqli_fetch_row($queryResult3);

//Get the totall number of canceled orders for current month
$query4 = "select count(orders.orderID) from benneks.orders inner join benneks.stat on orders.orderID = stat.orders_orderID where stat.orderStatus = 'لغو-İptal' or stat.orderStatus = 'لغو' and MONTH(orders.orderDate) = MONTH(NOW()) and YEAR(orders.orderDate) = YEAR(NOW()) and orders.country = 'ترکیه';";
if (!$user->executeQuery($query4)) {
    echo mysqli_error($user->conn);
}
$queryResult4 = $user->executeQuery($query4);
$totallCanceledMonthValue = mysqli_fetch_row($queryResult4);

//Get the tottal number of unknown orders for current month
$query5 = "select count(orders.orderID) from benneks.orders inner join benneks.stat on orders.orderID = stat.orders_orderID where stat.orderStatus is null and MONTH(orders.orderDate) = MONTH(NOW()) and YEAR(orders.orderDate) = YEAR(NOW()) and orders.country = 'ترکیه'";
if (!$user->executeQuery($query5)) {
    echo mysqli_error($user->conn);
}
$queryResult5 = $user->executeQuery($query5);
$totallNullMonthValue = mysqli_fetch_row($queryResult5);

//Get the current kargo Number
$query6 = "select max(cargoName) from benneks.shipment where cargoName REGEXP '^[0-9]{3}+$';";
if (!$user->executeQuery($query6)) {
    echo mysqli_error($user->conn);
}
$queryResult6 = $user->executeQuery($query6);
$currentKargo = mysqli_fetch_row($queryResult6);

//Get the totall number of available purchased items in office
$query7 = "select count(*) from benneks.shipment where shipment.officeArrivalDate is not null and shipment.cargoName is Null;";
if (!$user->executeQuery($query7)) {
    echo mysqli_error($user->conn);
}
$queryResult7 = $user->executeQuery($query7);
$totallAvailableOffice = mysqli_fetch_row($queryResult7);

//Get the totall number of orders on the way to office
$query8 = "select count(*) from benneks.shipment where benneksShoppingDate is not null and officeArrivalDate is null;";
if (!$user->executeQuery($query8)) {
    echo mysqli_error($user->conn);
}
$queryResult8 = $user->executeQuery($query8);
$ordersWayOffice = mysqli_fetch_row($queryResult8);

//Get the totall number of unknown orders 
$query9 = "select count(*) from benneks.stat where stat.orderStatus is Null;";
if (!$user->executeQuery($query9)) {
    echo mysqli_error($user->conn);
}
$queryResult9 = $user->executeQuery($query9);
$unknownOrders = mysqli_fetch_row($queryResult9);

//Get the totall number of returning item available in the office
$query10 = "select count(*) from benneks.stat where stat.orderStatus = 'عودت-İade';";
if (!$user->executeQuery($query10)) {
    echo mysqli_error($user->conn);
}
$queryResult10 = $user->executeQuery($query10);
$totallReturnOffice = mysqli_fetch_row($queryResult10);
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
<script type="text/JavaScript" src="./Javascripts/jquery.print.js" /></script>
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
        $(document).on("click", ".open-officeDeliveryModal", function () {
            var orderID = $(this).data('id');
            $(".modal-body #rowID").val(orderID);
        });
    });
</script>
<!-- script for turkey return modal -->
<script>
    $(document).ready(function () {
        $(document).on("click", ".open-turkeyReturnModal", function () {
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
                            <a href="turkish-admin.php"> <i class="fa fa-th-list fa-fw"> </i> Sipariş Liste </a>
                        </li>
                        <li>
                            <a href="kargoMaker.php"> <i class="fa fa-truck fa-fw" > </i> Yeni Kargo Liste Yapmak</a>
                        </li>
                        <li>
                            <a href="#"> <i class="fa fa-gear fa-fw" > </i> profil</a>
                        </li>
                        <li>
                            <a href="logout.php?logout"> <i class="fa fa-sign-out fa-fw" > </i> çıkış</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div id ="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <center> <h1 class="page-header" dir="ltr">Türk Admin Paneli <a href="turkish-admin.php"> <i class="fa fa-refresh fa-fw"></i> </a></h1> </center>
                </div>
                <div class="panel panel-success" dir="ltr" >
                    <div class="panel-heading">
                        <div class="row"> 
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <i class="fa fa-bullhorn fa-fw"></i> Bu ayki Raporu:
                                        <div class="form-inline">
                                            <div class="form-group">
                                                <label for="totallMonthValue">şimdilik ay bütun siparişleri: </label>
                                                <label id="totallMonthValue" style="color: goldenrod"> <?php echo $totallMonthValue[0]; ?> </label> 
                                            </div>
                                            <div class="form-group">
                                                <label for="totallDoneMonthValue">şimdilik ay satin almak siparişleri:  </label>
                                                <label id="totallDoneMonthValue" style="color: goldenrod"> <?php echo $totallDoneMonthValue[0]; ?> </label> 
                                            </div>
                                            <div class="form-group">
                                                <label for="totallCanceledMonthValue"> şimdilik ay iptalli siparişleri: </label>
                                                <label id="totallCanceledMonthValue" style="color: goldenrod"> <?php echo $totallCanceledMonthValue[0]; ?> </label> 
                                            </div>
                                            <div class="form-group">
                                                <label for="totallNullMonthValue"> şimdilik ay yeni siparişleri: </label>
                                                <label id="totallNullMonthValue" style="color: goldenrod"> <?php echo $totallNullMonthValue[0]; ?> </label> 
                                            </div>
                                            <br>
                                            <div class="form-group">
                                                <label for="currentKargo"> en son irana yolamiş kargo numarasi</label>
                                                <label id="currentKargo" style="color: goldenrod"> <?php echo $currentKargo[0] ?> <a href='#kargoPrintModal' style="color: yellow" data-toggle='modal' data-target='#kargoPrintModal' class='open-kargoPrintModal'> <i class="fa fa-print fa-fw"></i> </a> </label> 
                                            </div>
                                            <div class="form-group">
                                                <label for="nextKargo"> sonraki kargo numarasi:</label>
                                                <label id="nextKargo" style="color: goldenrod"> <?php echo $currentKargo[0] + 1 ?> </label> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <i class="fa fa-bullhorn fa-fw"></i> Offisde Raporu:
                                        <div class="form-inline">
                                            <div class="form-group">
                                                <label for="totallAvailableOffice">Officde galmiş siparişleri saysi: </label>
                                                <label id="totallAvailableOffice" style="color: goldenrod"> <?php echo $totallAvailableOffice[0]; ?> </label> 
                                            </div>
                                            <div class="form-group">
                                                <label for="orderWayOffice">Office siparişleri yolda saysi:  </label>
                                                <label id="orderWayOffice" style="color: goldenrod"> <?php echo $ordersWayOffice[0]; ?> </label> 
                                            </div>
                                            <div class="form-group">
                                                <label for="uknownOrders"> Bütun yeni siparişleri ki hala satin almamiş: </label>
                                                <label id="unknownOrders" style="color: goldenrod"> <?php echo $unknownOrders[0]; ?> </label> 
                                            </div>
                                            <div class="form-group">
                                                <label for="returnOrders"> Bütun iade siparişler officde saysi: </label>
                                                <label id="returnOrders" style="color: goldenrod"> <?php echo $totallReturnOffice[0]; ?> </label> 
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 col-lg col-md col-sm">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <form role = "form" method="post" name="searchForm" id="searchForm"  action="search.php">
                                            <i class="fa fa-star fa-fw"></i> Arama Kodu:
                                            <input type="search" class = "form-control" dir="ltr" id="searchInput" name="searchInput" placeholder="search...">
                                            <label for="searchOption"> <i class="fa fa-filter fa-fw"></i> Arama Filtresi Türü:</label>
                                            <div class="form-group">
                                                <select class = "form-control" id = "searchOption" name="searchOption">
                                                    <option value="code"> kod </option>
                                                    <option value="done"> satın almış siparişleri</option>
                                                    <option value="cancel"> iptal edilmiştir</option>
                                                    <option value="unknown"> bilinmeyen </option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <input type="hidden" name="searchReq" value="turkish-admin"/>
                                            </div>
                                            <div class="form-group">
                                                <button class="form-control btn btn-group btn-success" id="searchButton" name="searchButton" > arama
                                                    <span>
                                                        <i class="fa fa-search"> </i>
                                                    </span>
                                                </button>
                                            </div>
                                            <div class="form-group">
                                                <button class="form-control btn btn-group btn-danger" id="cancelSearchButton" name="cancelSearchButton" > aramayı iptal et
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
                                        <th style="text-align: center"> Kod</th>
                                        <th style="text-align: center"> Kullanıcı adı</th>
                                        <th style="text-align: center"> Sipariş Tarihi</th>

                                        <th style="text-align: center"> Fiyat</th>
                                        <th style="text-align: center"> Marka</th>    
                                        <th style="text-align: center">Link </th>
                                        <th style="text-align: center">Resim </th>

                                        <th style="text-align: center">Beden</th>
                                        <th style="text-align: center">Renk </th>


                                        <th style="text-align: center">Durum </th>
                                        <th style="text-align: center">Ayrıntılar </th>
                                    </tr>
                                </thead>
                                <tbody>
<?php
while ($row = mysqli_fetch_row($queryResult1)) {

    echo "<tr>";
    echo "<td> " . $row[0] .
    "<hr> "
    . "<a href='#addModal' data-toggle='modal' data-target='#addModal' data-id='$row[0]' class='open-addModal' > <i class='fa fa-check fa-fw fa-lg'></i> </a>"
    . "<a href='#cancelModal' data-toggle='modal' data-target='#cancelModal' data-id='$row[0]' class='open-cancelModal'> <i class='fa fa-times fa-fw fa-lg'></i> </a>"
    . "<a href='#officeDeliveryModal' data-toggle='modal' data-target='#officeDeliveryModal' data-id='$row[0]' class='open-officeDeliveryModal'> <i class='fa fa-home fa-fw fa-lg'></i> </a>"
    . "<a href='#turkeyReturnModal' data-toggle='modal' data-target='#turkeyReturnModal' data-id='$row[0]' class='open-turkeyReturnModal'> <i class='fa fa-exchange fa-fw fa-lg'></i> </a>"
    . " </td>";
    echo "<td>" . $row[1] . "</td>";
    echo "<td>" . $row[2] . "</td>";

    echo "<td>" . $row[4] . "</td>";
    echo "<td>" . $row[5] . "</td>";
    echo "<td> <a href= " . $row[6] . ">لینک محصول" . "</a> </td>";
    $picURL = str_replace(' ', '%20', $row[7]);
    echo "<td><a href=" . $picURL . "> <img src=" . $picURL . " class='img-rounded'" . "alt='بدون تصویر' width='100' height='100'> </a> </td>";

    echo "<td>" . $row[9] . "</td>";
    echo "<td>" . $row[10] . "</td>";


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
    echo "<li><a href='turkish-admin.php?page=" . $i . "'>" . $i . "</a></li>";
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
                                    hrefTextPrefix: 'turkish-admin.php?page='
                                });
                            });
                        </script>
<?php
echo "</div>";
?>
                    </div>
                </div>
                <!--cancel order modal -->
                <div class = "modal fade" id = "cancelModal" role="dialog">
                    <div class="modal-dialog">
                        <!--modal content -->
                        <div class="modal-content">
                            <div class="modal-header" style="padding: 35px 50px;">
                                <button type="button" class="close" data-dismiss = "modal">&times; </button>
                                <h4><span class = "glyphicon glyphicon-remove"> </span> Sipariş İptali </h4>
                            </div>
                            <div class="modal-body" style="padding:40px 50px;">
                                <form role="form" action="cancelOrder.php" method="post">
                                    <div class="form-group">
                                        <input type="hidden" id="incomingPage" name="incomingPage" value="turkish-Admin">
                                    </div>
                                    <div class="form-group">
                                        <label for="rowID"> <span class="glyphicon glyphicon-asterisk"></span> Kod </label>
                                        <input type="text" class="form-control" name="rowID" id="rowID">
                                    </div>
                                    <div class="form-group">
                                        <label for="cancelDetails"><span class="glyphicon glyphicon-hand-right"></span>  İptal Sebebi</label>
                                        <select class = "form-control" id = "cancelDetails" name="cancelDetails"> 
                                            <option value = "نبودن سایز-bednı bıtmış">bednı bıtmış</option>
                                            <option value = "تمام شدن محصول-tukendı">tukendı</option>
                                            <option value = "موجود نبودن رنگ-renkleri bitmiş">renkleri bitmiş</option>
                                            <option value = "اطلاعات ناقص-bilgileri tamamlanmamiş">bilgileri tamamlanmamiş</option>
                                            <option value = "به درخواست کاربر-muşteri istedin için">muşteri istedin için</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-block" name="submitButton" id="submitButton"> İptal </button>
                                    <button type="submit" class="btn btn-danger btn-block" name="resetButton" id="resetButton"> Reset </button>
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
                                <h4><span class = "glyphicon glyphicon-shopping-cart"> </span> Satin Almak Sipariş </h4>
                            </div>
                            <div class="modal-body" style="padding:40px 50px;">
                                <form role="form" action="addorder.php" method="post">
                                    <div class="form-group">
                                        <input type="hidden" id="incomingPage" name="incomingPage" value="turkish-Admin">
                                    </div>
                                    <div class="form-group">
                                        <label for="rowID"> <span class="glyphicon glyphicon-asterisk"></span> Kod </label>
                                        <input type="text" class="form-control" name="rowID" id="rowID">
                                    </div>
                                    <div class="form-group">
                                        <label for="shoppingDate"><span class="glyphicon glyphicon-calendar"></span>  Satin Almak Tarihi</label>
                                        <input type="date" class="form-control" name="shoppingDate" id="shoppingDate"> 
                                    </div>
                                    <button type="submit" class="btn btn-success btn-block" name="submitButton" id="submitButton"> Kayit </button>
                                    <button type="submit" class="btn btn-danger btn-block" name="resetButton" id="resetButton"> Reset </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Turkey Office delivery Modal -->
                <div class = "modal fade" id = "officeDeliveryModal" role="dialog">
                    <div class="modal-dialog">
                        <!--modal content -->
                        <div class="modal-content">
                            <div class="modal-header" style="padding: 35px 50px;">
                                <button type="button" class="close" data-dismiss = "modal">&times; </button>
                                <h4><span class = "glyphicon glyphicon-home"> </span> Office Galmış </h4>
                            </div>
                            <div class="modal-body" style="padding:40px 50px;">
                                <form role="form"  method="post" action="officeArrival.php">
                                    <div class="form-group">
                                        <input type="hidden" id="incomingPage" name="incomingPage" value="turkish-Admin">
                                    </div>
                                    <div class="form-group">
                                        <label for="rowID"> <span class="glyphicon glyphicon-asterisk"></span> Sipariş Kodu</label>
                                        <input type="text" class="form-control" name="rowID" id="rowID" readonly onclick="return false;">
                                    </div>
                                    <div class="form-group">
                                        <label for="officeArrivalDate"><span class="glyphicon glyphicon-calendar"></span>  Gönderme Tarihi</label>
                                        <input type="date" class="form-control" name="officeArrivalDate" id="officeArrivalDate"> 
                                    </div>
                                    <!-- javascript to pass print variable to js file which is supouse to print the code -->
                                    <script>
                                        function printCodeFunc() {
                                            $("#rowID").print({
                                                mediaPrint: false,
                                                iframe: false,
                                                title: null
                                            });
                                        }
                                    </script>

                                    <button type="submit" class="btn btn-success btn-block" name="submitButton" id="submitButton"> Kayıt</button>
                                    <button type="button" class="btn btn-info btn-block" name="printButton" id="printButton" onclick="printCodeFunc();"> Print </button>
                                    <button type="submit" class="btn btn-danger btn-block" name="resetButton" id="resetButton"> Reset </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Turkey Return Modal --> 
                <div class = "modal fade" id = "turkeyReturnModal" role="dialog">
                    <div class="modal-dialog">
                        <!--modal content -->
                        <div class="modal-content">
                            <div class="modal-header" style="padding: 35px 50px;">
                                <button type="button" class="close" data-dismiss = "modal">&times; </button>
                                <h4><span class = "glyphicon glyphicon-transfer"> </span> İade </h4>
                            </div>
                            <div class="modal-body" style="padding:40px 50px;">
                                <form role="form" action="return.php" method="post" dir="ltr">
                                    <div class="form-group">
                                        <input type="hidden" id="incomingPage" name="incomingPage" value="turkish-Admin">
                                    </div>
                                    <div class="form-group">
                                        <label for="rowID"> <span class="glyphicon glyphicon-asterisk"></span> Sipariş Kodu</label>
                                        <input type="text" class="form-control" name="rowID" id="rowID" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="returnReason"><span class="glyphicon glyphicon-hand-right"></span>  Neden</label>
                                        <select dir = "ltr" class = "form-control" id = "returnReason" name="returnReason"> 
                                            <option value = "اشتباه بودن سفارش-yanlış sipariş">yanlış sipariş</option>
                                            <option value = "خراب بودن محصول-bozuk sipariş">bozuk sipariş </option>
                                            <option value = "به درخواست کاربر-muşteri istedin için">muşteri istedin için</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="returnDescription"><span class="glyphicon glyphicon-comment"></span>  açıklama</label>
                                        <input type="text" class="form-control" name="returnComment" id="returnComment"> 
                                    </div>

                                    <button type="submit" class="btn btn-success btn-block" name="submitButton" id="submitButton"> Kayıt </button>
                                    <button type="submit" class="btn btn-danger btn-block" name="resetButton" id="resetButton"> Reset </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!--print kargo modal -->
                <div class = "modal fade" id = "kargoPrintModal" role="dialog">
                    <div class="modal-dialog">
                        <!--modal content -->
                        <div class="modal-content">
                            <div class="modal-header" style="padding: 35px 50px;">
                                <button type="button" class="close" data-dismiss = "modal">&times; </button>
                                <h4><span class = "glyphicon glyphicon-print"> </span> Print Kargo </h4>
                            </div>
                            <div class="modal-body" style="padding:40px 50px;">
                                <form role="form" action="printKargo.php" method="post">
                                    <div class="form-group">
                                        <input type="hidden" id="incomingPage" name="incomingPage" value="turkish-Admin">
                                    </div>
                                    <div class="form-group">
                                        <label for="kargoID"> <span class="glyphicon glyphicon-asterisk"></span> Kargo Kodu </label>
                                        <input type="text" class="form-control" name="kargoID" id="kargoID">
                                    </div>
                                    <button type="submit" class="btn btn-success btn-block" name="printButton" id="printButton"> Print </button>
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
?>



