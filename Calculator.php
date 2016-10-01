<?php ?>
<!DOCTYPE html>
<html>
    <head>
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
                        <div class = "form-group" id = "calculator">	
                            <div class = "form-group">
                                <label class = "control-label col-sm-4" for = "productType"> Type: </label>
                                <div class = "col-sm-5">
                                    <select dir = "rtl" class = "form-control" id = "productType">

                                        <option value="" disabled selected>نوع لباس را مشخص نمایید</option>

                                        <option value = "bag"> انواع کیف </option>
                                        <option value = "shoes"> انواع کفش و بوت </option>

                                        <option value = "wallet"> کیف پول </option> 
                                        <option value = "belt"> کمربند </option>
                                        <option value = "sunglass"> عینک </option>
                                        <option value = "perfium"> عطر </option>
                                        <option value = "watch"> ساعت </option>
                                        <option value = "accessory"> اکسسوری </option>

                                        <option value = "shomiz"> شمیز و سرهمی </option>
                                        <option value = "short"> شلوارک </option>
                                        <option value = "blouse"> بلوز </option>
                                        <option value = "sweater"> پلیور </option>
                                        <option value = "top"> تاپ </option>
                                        <option value = "skirt"> دامن </option>
                                        <option value = "womenshirt"> پیراهن زنانه </option>
                                        <option value = "manshirt"> پیراهن مردانه </option>
                                        <option value = "dress"> پیراهن بلند زنانه </option>
                                        <option value = "lingerie"> لباس زیر </option>
                                        <option value = "tshirt"> تی شرت </option>
                                        <option value = "scarf"> انواع روسری و شال </option>
                                        <option value = "bikini"> مایو </option>
                                        <option value = "swimsuit"> رو مایو </option>
                                        <option value = "sleepwear"> لباس خواب </option>
                                        <option value = "support"> ساپورت </option>
                                        <option value = "pancho">  پانچو </option>
                                        <option value = "pant"> شلوار معمولی </option>

                                        <option value = "cardigan"> کاردیگان</option>
                                        <option value = "manto"> مانتو </option>
                                        <option value = "rainingcoat"> بارونی </option>
                                        <option value = "summerjacket"> انواع کت های جین و تابستانی </option>
                                        <option value = "jean"> شلوار جین </option>
                                        <option value = "coat&skirt"> کت و دامن به همراه هم </option>

                                        <option value = "leathercoat"> کت چرم </option>
                                        <option value = "winterjacket"> کاپشن </option>
                                        <option value = "wintercoat"> کت زمستانی</option>
                                        <option value = "palto"> پالتو </option>
                                    </select>
                                </div>
                            </div>
                            <div class = "form-group">
                                <label class = "control-label col-sm-4" for = "productPrice"> Price(TL): </label>
                                <div class = "col-sm-5">
                                    <input class = "form-control" id = "productPrice" placeholder = "Enter Price">
                                </div>
                            </div>
                            <div class = "form-group">
                                <div class = "col-sm-12">
                                    <button type = "button" class = "btn btn-primary btn-block" id = "calButton" onclick = "calculator()"> Calculate </button>
                                </div>
                            </div>
                            <div class = "form-group">
                                <label class = "control-label col-sm-5" for = "finalPrice"> Final Price(Toman): </label>
                                <div class = "col-sm-5">
                                    <input class = "form-control" id = "finalPrice" placeholder = "Finall Price" disabled = "true">
                                </div>
                            </div>
                    </form>
                    <span id = "test"> </span>
                    <span id = "test2"> </span>
                </div>
            </div>
        </div>
        <div class = "col-sm-4"> </div>
    </div>
</div>


</body>