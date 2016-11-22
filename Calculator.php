<?php
ob_start();
session_start();
require 'src/benneks.php';
// if session is not set this will redirect to login page
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"/></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"/></script>
<script type="text/javascript" src="./Javascripts/script.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="http://ifont.ir/apicode/33" rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="style.css" />
<title>Benneks Order System</title>
</head>
<body>
    <div class = "container-fluid">
        <div class = "row">
            <div class = "col-sm-4"> </div>
            <div class = "col-sm-4"> </div>
            <div class = "col-sm-4"> </div>
        </div>
        <div class = "row">
            <div class = "col-sm-4"> </div>
            <div class = "col-sm-4"> 
                <div class = "list-group">
                    <h3 class = "list-group-item list-group-item-success"> <center> Bennkes Calculator</center> </h3>
                    <form class = "form-horizontal"  role = "form" method = "post">	
                        <div class = "form-group" id = "calculator" style="margin-top: 40px">	
                            <div class = "form-group">
                                <label class = "control-label col-sm-4" for = "productType"> Type: </label>
                                <div class = "col-sm-5">
                                    <select dir = "rtl" class = "form-control" id = "clothesType" name="clothesType">
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
                                        <option value = "women-blouse"> بلوز زنانه </option> 
                                        <option value = "women-top"> تاپ زنانه </option> 
                                        <option value = "women-skirt"> دامن زنانه </option> 
                                        <option value = "women-shirt"> پیراهن زنانه </option> 
                                        <option value = "women-dress"> پیراهن بلند زنانه </option> 
                                        <option value = "women-lingerie"> لباس زیر  زنانه</option> 
                                        <option value = "women-tshirt"> تی شرت زنانه </option> 
                                        <option value = "women-scarf"> انواع روسری و شال  زنانه</option> 
                                        <option value = "women-bikini"> مایو زنانه </option> 
                                        <option value = "women-swimsuit">  رو مایو زنانه </option> 
                                        <option value = "women-sleepwear"> لباس خواب  زنانه</option> 
                                        <option value = "women-support"> ساپورت  زنانه</option> 
                                        \\Women products around 450 gr
                                        <option value = "women-cardigan"> کاردیگان زنانه</option>
                                        <option value = "women-manto"> مانتو  زنانه</option>
                                        <option value = "women-rainingcoat"> بارونی  زنانه</option> 
                                        <option value = "women-summerjacket"> انواع کت های تابستانی زنانه </option>
                                        <option value = "women-jean"> شلوار جین زنانه </option>
                                        <option value = "women-coat&skirt"> کت و دامن به همراه هم زنانه </option>
                                        <option value = "women-shomiz"> شمیز و سرهمی  زنانه</option> 
                                        <option value = "women-sweater">  پلیورهای نازک زنانه</option> 
                                        <option value = "women-pancho">  پانچو  زنانه</option> 
                                        <option value = "women-pant"> شلوار معمولی  زنانه</option> 
                                        \\Women products around 600 gr
                                        <option value = "women-wintercoat"> کت زمستانی زنانه</option>
                                        <option value = "women-palto"> پالتو سبک  زنانه</option>
                                        <option value = "women-jacket"> کاپشن سبک  زنانه</option>
                                        \\Women products 800 gr
                                        <option value = "women-sportwear"> گرمکن زنانه</option>
                                        <option value = "women-jeancoat"> کت جین  زنانه</option>
                                        <option value = "women-leathercoat"> کت چرم  زنانه</option>
                                        <option value = "women-winterjacket"> کاپشن سنگین  زنانه</option>
                                        <option value = "women-heavysweater"> پلیورهای سنگین زنانه</option>
                                        \\ Women products more than 1 kg
                                        <option value = "women-heavy"> کاپشن و کت و پالتو های سنگین و ضخیم  زنانه</option>
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
                            </div>
                            <div class = "form-group">
                                <label class = "control-label col-sm-4" for = "productPrice"> Price(TL): </label>
                                <div class = "col-sm-5">
                                    <input class = "form-control" id = "productPrice" placeholder = "Enter Price">
                                </div>
                            </div>
                            <script>
                                function calculate() {
                                    var clothesType = document.getElementById("clothesType").value;
                                    var productPrice = document.getElementById("productPrice").value;
                                    var finalPrice = calculator(clothesType, productPrice);
                                    document.getElementById('finalPrice').value = finalPrice;
                                }
                            </script>
                            <div class = "form-group">
                                <div class = "col-sm-12">
                                    <button type = "button" class = "btn btn-primary btn-block" id = "calButton" onclick = "calculate();"> Calculate </button>
                                </div>
                            </div>
                            <div class = "form-group">
                                <label class = "control-label col-sm-5" for = "finalPrice"> Final Price(Toman): </label>
                                <div class = "col-sm-5">
                                    <input class = "form-control" id = "finalPrice" placeholder = "Finall Price" readonly="">
                                </div>
                            </div>
                            <div class="form-group"><center> <a href="home.php"> بازگشت به صفحه اصلی </a> </center> </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>


</body>