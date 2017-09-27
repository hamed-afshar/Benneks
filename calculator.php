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
                            <div class="form-group">
                                <label class = "control-label col-sm-4" for="country"> Country </label>
                                <div class="col-sm-5">
                                    <select dir="rtl" class="form-control" id="country" name ="country">
                                        <option value="ترکیه" selected> ترکیه </option>
                                        <option value="انگلیس"> انگلیس </option>
                                        <option value="فرانسه"> فرانسه </option>
                                    </select>
                                </div>
                            </div>
                            <div class = "form-group">
                                <label class = "control-label col-sm-4" for = "productType"> Type: </label>
                                <div class = "col-sm-5">
                                    <select dir = "rtl" class = "form-control" id = "clothesType" name="clothesType">
                                         <option value="" disabled selected style="color: red">انواع لباس زنانه:</option>
                                                \\Women bag and shoes
                                                <option value = "کیف زنانه"> انواع کیف زنانه </option>
                                                <option value = "کفش زنانه"> انواع کفش و بوت ودمپایی زنانه</option>
                                                \\unisex products around 120 gr                         
                                                <option value = "کیف پول"> کیف پول مردانه یا زنانه </option> 
                                                <option value = "کمربند"> کمربند مردانه یا زنانه </option>
                                                <option value = "عینک"> عینک  مردانه یا زنانه</option>
                                                <option value = "عطر"> عطر مردانه یا زنانه </option>
                                                <option value = "ساعت"> ساعت  مردانه یا زنانه</option>
                                                <option value = "زیورآلات"> اکسسوری مردانه یا زنانه </option>
                                                <option value = "جوراب"> جوراب زنانه یا مردانه </option>
                                                \\Women products around 200 gr
                                                <option value = "شلوارک زنانه"> شلوارک  زنانه</option> 
                                                <option value = "تاپ زنانه"> تاپ زنانه </option> 
                                                <option value = "لباس زیر زنانه"> لباس زیر  زنانه</option> 
                                                <option value = "تی شرت زنانه"> تی شرت زنانه </option> 
                                                <option value = "کلاه، شال، روسری، دستکش"> انواع شال، کلاه، روسری و دستکش </option> 
                                                <option value = "بیکینی"> مایو زنانه </option> 
                                                <option value = "رو مایو زنانه">  رو مایو زنانه </option> 
                                                <option value = "لباس خواب زنانه"> لباس خواب  زنانه</option> 
                                                <option value = "ساپورت زنانه"> ساپورت  زنانه</option> 
                                                <option value = "بلوز زنانه"> بلوز زنانه </option>  
                                                <option value = "دامن زنانه"> دامن زنانه </option>
                                                \\Women products around 300 gr
                                                <option value = "کاردیگان سبک زنانه"> کاردیگان سبک زنانه</option>
                                                <option value = "پلیورهای نازک زنانه">  پلیورهای نازک زنانه</option>
                                                <option value = "شلوار معمولی زنانه"> شلوار معمولی  زنانه</option> 
                                                <option value = "سرهمی زنانه"> سرهمی  زنانه</option>                
                                                <option value = "پانچو">  پانچو  زنانه</option>
                                                <option value = "مانتو زنانه"> مانتو  زنانه</option>
                                                <option value = "پیراهن زنانه"> پیراهن زنانه </option>
                                                <option value = "سوئیت شرت زنانه"> سوئیت شرت زنانه </option>
                                                \\Women products around 400 gr
                                                <option value = "کاردیگان سنگین زنانه"> کاردیگان سنگین زنانه</option>
                                                <option value = "جین زنانه"> شلوار جین زنانه </option>
                                                <option value = "بارونی زنانه"> بارونی  زنانه</option>
                                                <option value = "کت تابستانی زنانه"> انواع کت های تابستانی زنانه </option>
                                                \\Women products around 600 gr
                                                <option value = "کت زمستانی زنانه"> کت زمستانی زنانه</option>
                                                <option value = "کاپشن سبک زنانه"> کاپشن سبک  زنانه</option>
                                                <option value = "کت جین زنانه"> کت جین  زنانه</option>
                                                <option value = "کت چرم زنانه"> کت چرم  زنانه</option>
                                                <option value = "پلیور سنگین زنانه"> پلیورهای سنگین زنانه</option>
                                                <option value = "پالتو زنانه سبک"> پالتو سبک  زنانه</option>
                                                \\Women products 800 gr
                                                <option value = "کت جیر زنانه"> کت جیر زنانه</option>
                                                <option value = "پالتو زنانه سنگین"> پالتو سنگین زنانه</option>
                                                <option value = "گرمکن زنانه"> ست گرمکن زنانه</option>
                                                <option value = "کاپشن سنگین زنانه"> کاپشن سنگین  زنانه</option>
                                                \\ Women products more than 1 kg
                                                <option value = "سنگین زنانه"> کاپشن و کت و پالتو های سنگین و ضخیم  زنانه</option>
                                                <option value = "سنگین زنانه">انواع کت دامن زنانه</option>
                                                <option value = "سنگین زنانه"> انواع کت شلوار زنانه</option>
                                                \\\\\Man Products
                                                <option value="" disabled style="color:red">انواع لباس مردانه:</option>
                                                \\ Man bag & shoes
                                                <option value = "کفش مردانه"> انواع کفش و بوت ودمپایی مردانه </option>
                                                <option value = "کیف مردانه"> کیف دستی مردانه </option>
                                                \\ Man products around 200 gr
                                                <option value = "پیراهن مردانه"> پیراهن مردانه </option>
                                                <option value = "تی شرت مردانه"> تی شرت مردانه </option>
                                                <option value = "لباس زیر مردانه"> لباس زیر مردانه </option>
                                                <option value = "شلوارک مردانه"> شلوارک مردانه </option>
                                                <option value = "مایو مردانه"> مایو مردانه</option>
                                                \\ Man products around 450 gr
                                                <option value = "شلوار معمولی مردانه"> شلوار معمولی مردانه </option>
                                                <option value = "شلوار جین مردانه"> شلوار جین مردانه </option>     
                                                <option value = "پلیور مردانه سبک"> پلیور مردانه سبک </option>
                                                \\Man products around 600 gr
                                                <option value = "کت معمولی مردانه"> کت معمولی مردانه</option>
                                                \\Man products around 800 gr
                                                <option value = "گرمکن مردانه"> ست گرمکن مردانه</option>
                                                <option value = "کت جین مردانه"> کت جین مردانه</option>
                                                <option value = "پلیور مردانه سنگین"> پلیور مردانه سنگین </option>
                                                <option value = "کاپشن سبک مردانه"> کاپشن سبک مردانه </option>
                                                \\Man product more than 1 kg
                                                <option value = "کاپشن سنگین مردانه"> کاپشن سنگین مردانه</option>
                                                <option value = "کت چرم مردانه"> کت چرم مردانه</option>
                                                <option value = "پالتو مردانه"> پالتو مردانه</option>
                                                <option value = "اورکت مردانه"> اورکت مردانه</option>
                                                <option value = "کت شلوار مردانه"> کت شلوار مردانه</option>
                                                \\KIDS Shoes and bags
                                                <option value="" disabled style="color: red">انواع لباس بچه گانه:</option>
                                                <option value = "کفش بچه گانه"> کفش و دمپایی و صندل بچه گانه</option>
                                                <option value = "کیف بچه گانه"> انواع کیف بچه گانه</option>
                                                \\KIDS Accessories 120 gr
                                                <option value = "انواع کلاه بچه گانه"> انواع کلاه بچه گانه</option>
                                                <option value = "جوراب و لباس زیر بچه گانه"> جوراب و لباس زیر بچه گانه</option>
                                                <option value = "اسباب بازی بچه گانه"> اسباب بازی بچه گانه</option>
                                                \\KIDS products equal to 200 gram
                                                <option value = "بلوز شلوار ست بچه گانه"> بلوز شلوار ست بچه گانه</option>
                                                <option value = "تی شرت بچه گانه"> تی شرت بچه گانه</option>
                                                <option value = "سرهمی بچه گانه"> سرهمی بچه گانه</option>
                                                <option value = "لباس خواب بچه گانه"> لباس خواب بچه گانه</option>
                                                <option value = "شلوارک جین بچه گانه"> شلوارک جین بچه گانه</option>
                                                <option value = "مایو بچه گانه"> مایو بچه گانه</option>
                                                <option value = "پلیور بچه گانه"> پلیور بچه گانه</option>
                                                <option value = "پیراهن بچه گانه"> پیراهن بچه گانه</option>
                                                <option value = "دامن بچه گانه"> دامن بچه گانه</option>
                                                \\KIDS products equal to 450 gram  
                                                <option value = "کاپشن بچه گانه"> کاپشن بچه گانه</option>
                                                <option value = "گرمکن بچه گانه"> گرمکن بچه گانه</option>
                                                \\Baby clothes all 120 gram
                                                <option value = "تمامی لباس های نوزادی"> تمامی لباس های نوزادی</option>
                                    </select>
                                </div>
                            </div>
                            <div class = "form-group">
                                <label class = "control-label col-sm-4" for = "productPrice"> Price(TL): </label>
                                <div class = "col-sm-5">
                                    <input class = "form-control" id = "productPrice" placeholder = "Enter Price">
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="hidden" id="userID" name="userID" value="<?php echo $_SESSION['user']; ?>" >
                            </div>
                            <script>
                                function calculate() {
                                    var userID = document.getElementById("userID").value;
                                    var country = document.getElementById("country").value;
                                    var clothesType = document.getElementById("clothesType").value;
                                    var productPrice = document.getElementById("productPrice").value;
                                    var finalPrice = calculator(userID, country, clothesType, productPrice);
                                    document.getElementById('finalPrice').value = finalPrice.totalCost;
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