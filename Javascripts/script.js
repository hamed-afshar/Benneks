//Flags to keep the submit button activation status 
var usernameFlag = false;
var emailFlag = false;
var charFlag = false;
var passFlag = false;
var passAgainFlag = false;
//Flags to keep making order submit button status home.php
var priceFlag = false;
//Function to check username existence on registeration
function checkUserName() {
    var username = document.getElementsByName("username")[0].value;
    // check if username contains illegall characters or les than 3 chars
    var illegalChar = /([\W0-9])+/g;
    if ((illegalChar.test(username)) || username.length < 3) {
        $('#userNameIcon').closest('.form-group').removeClass('has-success').addClass('has-error');
        $('#userNameIcon').removeClass('glyphicon-ok').addClass('glyphicon-remove');
        // $("#signupButton").prop('disabled', true);
        charFlag = false;
        return charFlag;
    }
    $.getJSON("/phpscripts/registerCheck.php?input=username&value=" + username, function (data) {
        // if user is already exist in database, shows proper icon and make the signup button disabled
        if (data.icon === "remove-icon") {
            $('#userNameIcon').closest('.form-group').removeClass('has-success').addClass('has-error');
            $('#userNameIcon').removeClass('glyphicon-ok').addClass('glyphicon-remove');
            usernameFlag = false;
            return usernameFlag;
        }
        // if username is new then show appropriate icon and make the signup button active
        else if (data.icon === "success-icon") {
            $('#userNameIcon').closest('.form-group').removeClass('has-error').addClass('has-success');
            $('#userNameIcon').removeClass('glyphicon-remove').addClass('glyphicon-ok');
            usernameFlag = true;
            charFlag = true;
            return usernameFlag;
        }

    });
}

//Function to check email exsitence on registration
function checkEmail() {
    var email = document.getElementsByName("email")[1].value;
    $.getJSON("/phpscripts/registerCheck.php?input=email&value=" + email, function (data) {
        // if email is already exist in database, then shows proper icon and make the signup button disabled
        if (data.icon === "remove-icon" || email.length < 5) {
            $('#emailIcon').closest('.form-group').removeClass('has-success').addClass('has-error');
            $('#emailIcon').removeClass('glyphicon-ok').addClass('glyphicon-remove');
            emailFlag = false;
            return emailFlag;
        }
        // if email is new then show appropriate icon and make the signup button active
        else if (data.icon === "success-icon") {
            $('#emailIcon').closest('.form-group').removeClass('has-error').addClass('has-success');
            $('#emailIcon').removeClass('glyphicon-remove').addClass('glyphicon-ok');
            emailFlag = true;
            return emailFlag;
        }

    });
}

//Function to check password rules
function checkPass() {
    var password = document.getElementsByName("password")[1].value;
    //if password lenght is less than 6 character
    if (password.length < 6) {
        $('#passIcon').closest('.form-group').removeClass('has-success').addClass('has-error');
        $('#passIcon').removeClass('glyphicon-ok').addClass('glyphicon-remove');
        passFlag = false;
        return passFlag;
    }
// //if password lenght is more than 6 character
    else {
        $('#passIcon').closest('.form-group').removeClass('has-error').addClass('has-success');
        $('#passIcon').removeClass('glyphicon-remove').addClass('glyphicon-ok');
        passFlag = true;
        return passFlag;
    }
}

//Function to check repeated password
function passAgain() {
    var passwordRe = document.getElementById("re-password").value;
    var password = document.getElementsByName("password")[1].value;
    //if password-re is not equal to pass
    if (passwordRe !== password) {
        $('#passReIcon').closest('.form-group').removeClass('has-success').addClass('has-error');
        $('#passReIcon').removeClass('glyphicon-ok').addClass('glyphicon-remove');
        passAgainFlag = false;
        return passAgainFlag;
    }
// //if password-re is equal to pass
    else if (passwordRe === password) {
        $('#passReIcon').closest('.form-group').removeClass('has-error').addClass('has-success');
        $('#passReIcon').removeClass('glyphicon-remove').addClass('glyphicon-ok');
        passAgainFlag = true;
        return passAgainFlag;
    }
}

//function to check flags and enable submit button in register.php
function submitActivation() {
    if ((usernameFlag === false) || (emailFlag === false) || (charFlag === false) || (passFlag === false) || (passAgainFlag === false)) {
        alert("خطا! یکی از فیلدهای ورود اطلاعات به درستی وارد نشده است");
        return false;
    }
}

//Function to automatically calculate the price for sellers in calculator.php
function calculator(userID, country, clothesType, productPrice) {
    switch (country) {
        case "ترکیه":
            var country = "Turkey";
            var currency = "TL";
            var currencyRate = 1480;
            var weightCost = 50000; //45000 per killo dor kargo + 5000 Peik Iran
            var shippingCost = 45000;
            break;
        case "انگلیس":
            var country = "UK";
            var currency = "UK-Pound";
            var currencyRate = 5300;
            var weightCost = 80000; //70000 per killo dor kargo + 5000 Peik Iran
            var shippingCost = 80000;
            break;
        case "فرانسه":
            var country = "FR";
            var currency = "Euro";
            var currencyRate = 4900;
            var weightCost = 80000; //70000 per killo dor kargo + 5000 Peik Iran
            var shippingCost = 80000;
            break;
    }
//final price is calculated based on userID, userMargin variable keeps this value for adding up to users final price.
    switch (userID) {
        case "1":
        case "3":
        case "5":
        case "6":
        case "7":
        case "8":
        case "9":
        case "22":
        case "25":
        case "32":
            var discount = 8000;
            var userMargin = 0;
            break;
        default:
            var discount = 0;
            var userMargin = 0;
            break;
    }
    // if uk selected as a country then discount is zero
    if (country === "UK" || country === "FR") {
        var discount = 0;
    }

    var benneksMargin = 0;
    var totalCost = 0;
    // set benneksmargin based on product price
    if (productPrice >= 0 && productPrice <= 100) {
        benneksMargin = 0.2;
    } else if (productPrice > 100 && productPrice <= 200) {
        benneksMargin = 0.15;
    } else {
        benneksMargin = 0.1;
    }
// if buying from uk then benneks margin should be 15% regardless of productPrice
    if (country === "UK" || country === "FR") {
        benneksMargin = 0.23;
    }
    switch (clothesType) {
//Man and Women bag
        case "کیف زنانه":
        case "کیف مردانه":
        case "کیف بچه گانه":
            weight = 1000;
            totalCost = (productPrice * currencyRate) + ((productPrice * currencyRate) * benneksMargin) + shippingCost + ((productPrice * currencyRate) * userMargin) - discount;
            //return totalCost
            return {
                totalCost: totalCost,
                productWeight: weight,
                benneksMargin: benneksMargin,
                currencyRate: currencyRate,
                currency: currency,
                iranDeliverCost: shippingCost
            }
            break;
            //Man and Women shoes
        case "کفش زنانه":
        case "کفش مردانه":
        case "کفش بچه گانه":
            weight = 1000;
            totalCost = (productPrice * currencyRate) + ((productPrice * currencyRate) * benneksMargin) + shippingCost + ((productPrice * currencyRate) * userMargin) - discount;
            return {
                totalCost: totalCost,
                productWeight: weight,
                benneksMargin: benneksMargin,
                currencyRate: currencyRate,
                currency: currency,
                iranDeliverCost: shippingCost
            }
            break;
            //Man and Women products around 200 gr
        case "پیراهن مردانه":
        case "تی شرت مردانه":
        case "لباس زیر مردانه":
        case "شلوارک مردانه":
        case "مایو مردانه":
        case "شلوارک زنانه":
        case "تاپ زنانه":
        case "لباس زیر زنانه":
        case "تی شرت زنانه":
        case "کلاه، شال، روسری، دستکش":
        case "بیکینی":
        case "رو مایو زنانه":
        case "لباس خواب زنانه":
        case "ساپورت زنانه":
        case "بلوز زنانه":
        case "دامن زنانه":
        case "بلوز شلوار ست بچه گانه":
        case "تی شرت بچه گانه":
        case "سرهمی بچه گانه":
        case "لباس خواب بچه گانه":
        case "شلوارک جین بچه گانه":
        case "مایو بچه گانه":
        case "پلیور بچه گانه":
        case "پیراهن بچه گانه":
        case "دامن بچه گانه":
            weight = 200;
            shippingCost = (weightCost * 200) / 1000;
            totalCost = (productPrice * currencyRate) + ((productPrice * currencyRate) * benneksMargin) + shippingCost + ((productPrice * currencyRate) * userMargin) - discount;
            //return totalCost;
            return {
                totalCost: totalCost,
                productWeight: weight,
                benneksMargin: benneksMargin,
                currencyRate: currencyRate,
                currency: currency,
                iranDeliverCost: shippingCost
            }
            break;
            //Man and Women products around 300 gr
        case "کاردیگان سبک زنانه":
        case "پلیورهای نازک زنانه":
        case "شلوار معمولی زنانه":
        case "پانچو":
        case "سرهمی زنانه":
        case "مانتو زنانه":
        case "سوئیت شرت زنانه":
            weight = 300;
            shippingCost = (weightCost * 300) / 1000;
            totalCost = (productPrice * currencyRate) + ((productPrice * currencyRate) * benneksMargin) + shippingCost + ((productPrice * currencyRate) * userMargin) - discount;
            //return totalCost;
            return {
                totalCost: totalCost,
                productWeight: weight,
                benneksMargin: benneksMargin,
                currencyRate: currencyRate,
                currency: currency,
                iranDeliverCost: shippingCost
            }
            break;
            //Man and Women products around 400 gr
        case "شلوار معمولی مردانه":
        case "شلوار جین مردانه":
        case "پلیور مردانه سبک":
        case "کاردیگان زنانه":
        case "جین زنانه":
        case "کاردیگان سنگین زنانه":
        case "بارونی زنانه":
        case "کت تابستانی زنانه":
        case "پیراهن زنانه":
        case "کاپشن بچه گانه":
        case "گرمکن بچه گانه":
            weight = 400;
            shippingCost = (weightCost * 400) / 1000;
            totalCost = (productPrice * currencyRate) + ((productPrice * currencyRate) * benneksMargin) + shippingCost + ((productPrice * currencyRate) * userMargin) - discount;
            //return totalCost;
            return {
                totalCost: totalCost,
                productWeight: weight,
                benneksMargin: benneksMargin,
                currencyRate: currencyRate,
                currency: currency,
                iranDeliverCost: shippingCost
            }
            break;
            //Man and Women products around 600 gr
        case "کت معمولی مردانه":
        case "کت زمستانی زنانه":
        case "کاپشن سبک زنانه":
        case "کت جین زنانه":
        case "کت چرم زنانه":
        case "پلیور سنگین زنانه":
        case "پالتو زنانه سبک":
            weight = 600;
            shippingCost = (weightCost * 600) / 1000;
            totalCost = (productPrice * currencyRate) + ((productPrice * currencyRate) * benneksMargin) + shippingCost + ((productPrice * currencyRate) * userMargin) - discount;
            //return totalCost;
            return {
                totalCost: totalCost,
                productWeight: weight,
                benneksMargin: benneksMargin,
                currencyRate: currencyRate,
                currency: currency,
                iranDeliverCost: shippingCost
            }
            break;
            //Man and Women products around 800 gr
        case "گرمکن مردانه":
        case "کت جین مردانه":
        case "پلیور مردانه سنگین":
        case "کاپشن سبک مردانه":
        case "گرمکن زنانه":
        case "کاپشن سنگین زنانه":
        case "کت جیر زنانه":
        case "پالتو زنانه سنگین":
            weight = 800;
            shippingCost = (weightCost * 800) / 1000;
            totalCost = (productPrice * currencyRate) + ((productPrice * currencyRate) * benneksMargin) + shippingCost + ((productPrice * currencyRate) * userMargin) - discount;
            //return totalCost;
            return {
                totalCost: totalCost,
                productWeight: weight,
                benneksMargin: benneksMargin,
                currencyRate: currencyRate,
                currency: currency,
                iranDeliverCost: shippingCost
            }
            break;
            //Man and Women products more than 1 kg
        case "کاپشن سنگین مردانه":
        case "کت چرم مردانه":
        case "پالتو مردانه":
        case "اورکت مردانه":
        case "کت شلوار مردانه":
        case "سنگین زنانه":
            weight = 1200;
            shippingCost = (weightCost * 1200) / 1000;
            totalCost = (productPrice * currencyRate) + ((productPrice * currencyRate) * benneksMargin) + shippingCost + ((productPrice * currencyRate) * userMargin) - discount;
            //return totalCost;
            return {
                totalCost: totalCost,
                productWeight: weight,
                benneksMargin: benneksMargin,
                currencyRate: currencyRate,
                currency: currency,
                iranDeliverCost: shippingCost
            }
            break;
        case "کیف پول":
        case "کمربند":
        case "عینک":
        case "عطر":
        case "ساعت":
        case "زیورآلات":
        case "جوراب":
        case "انواع کلاه بچه گانه":
        case "جوراب و لباس زیر بچه گانه":
        case "اسباب بازی بچه گانه":
        case "تمامی لباس های نوزادی":
            weight = 120;
            shippingCost = (weightCost * 120) / 1000;
            totalCost = (productPrice * currencyRate) + ((productPrice * currencyRate) * benneksMargin) + shippingCost + ((productPrice * currencyRate) * userMargin) - discount;
            //return totalCost;
            return {
                totalCost: totalCost,
                productWeight: weight,
                benneksMargin: benneksMargin,
                currencyRate: currencyRate,
                currency: currency,
                iranDeliverCost: shippingCost
            }
            break;
    }

}
//Function to check iran deliver
function iranDeliverFunc(action) {
    var orderID = document.getElementById("rowID").value;
    var iranArrivalDate = document.getElementById("iranArrivalDate").value;
    var cargoName = document.getElementById("cargoName").value;
    if (cargoName === "") {
        alert("لطفا شماره کارگور را وارد نمایید.");
        return false;
    }
    switch (action) {
        case "search" :
            $.getJSON("./irandeliver.php?orderID=" + orderID + "&iranArrivalDate=" + iranArrivalDate + "&cargoName=" + cargoName, function (data) {
                var result = data.result;
                var msg = data.msg;
                var counterMsg = data.counterMsg;
                var counterErrorMsg = data.counterErrorMsg;
                if (result === "success-search") {
                    document.getElementById("iranDeliverMsg").innerHTML = msg;
                    $("#searchButton").prop('hidden', true);
                    $("#changeCargoButton").show("slow");
                    $("#Not-changeCargoButton").show("slow");
                }
                if (result === "wrong-search") {
                    document.getElementById("iranDeliverMsg").innerHTML = msg;
                }
                if (result === "null-search") {
                    document.getElementById("iranDeliverMsg").innerHTML = msg;
                }
                document.getElementById("counterMsg").innerHTML = counterMsg;
                document.getElementById("counterErrorMsg").innerHTML = counterErrorMsg;

            });

    }
}

//Function to check numeric prices only in making order section in home.php
function checkPrice() {
    var price = document.getElementById("productPrice").value;
    // check if price is all numeric
    var illegalChar = /[^0-9.]/g;
    if ((illegalChar.test(price))) {
        document.getElementById("priceAlert").innerHTML = "برای قیمت گذاری تنها از اعداد استفاده نمایید";
        $("#submitOrderButton").prop('disabled', true);
        priceFlag = false;
        return priceFlag;
    } else
    {
        document.getElementById("priceAlert").innerHTML = "";
        $("#submitOrderButton").prop('disabled', false);
        priceFlag = true;
        return priceFlag;
    }

}
// function for order submition validation
function validateForm() {
    var clothesType = document.forms["orderForm"]["clothesType"].value;
    var productBrand = document.forms["orderForm"]["productBrand"].value;
    var productLink = document.forms["orderForm"]["productLink"].value;
    var productPic = document.forms["orderForm"]["productPic"].value;
    var productSize = document.forms["orderForm"]["productSize"].value;
    var productColor = document.forms["orderForm"]["productColor"].value;
    var productPrice = document.forms["orderForm"]["productPrice"].value;
    if (clothesType === "" || productBrand === "" || productPic === "" || productLink === "" || productSize === "" || productColor === "" || productPrice === "") {
        alert("خطا! یکی از اطلاعات ورودی پر نشده است. لطفا تمامی اطلاعات را وارد نمایید");
        return false;
    }
}

//function to check if order has already assgined a kargo code or not. if yes then it is not possible to cancel or return this order

function checkCancelFunc(action) {
    var orderID = document.getElementById("rowID").value;
    var cancelDetails = document.getElementById("cancelDetails").value;
    var incomingPage = document.getElementById("incomingPage").value;
    switch (action) {
        case "submit":
            $.getJSON("./cancelOrder.php?action=" + action + "&orderID=" + orderID + "&cancelDetails=" + cancelDetails + "&incomingPage=" + incomingPage, function (data) {
                // if cargo code has already been assigned to this order then show a suitable message
                var result = data.result;
                var msg = data.msg;
                if (result === "exsist") {
                    document.getElementById("msg").innerHTML = msg;
                } else {
                    document.getElementById("msg").innerHTML = msg;
                }
            });
            break;
    }
}

//function to check order status once it arrives to the office
function checkOfficeArrivalFunc(action) {
    var orderID = document.getElementById("rowID").value;
    var officeArrivalDate = document.getElementById("officeArrivalDate").value;
    switch (action) {
        case "submit":
            $.getJSON("./officeArrival.php?action=" + action + "&orderID=" + orderID + "&officeArrivalDate=" + officeArrivalDate, function (data) {
                //if order has canceled before office arrival suitable msg will pops up
                var result = data.result;
                var msg = data.msg;
                if (result === "exsist") {
                    document.getElementById("OfficeDeliverMsg").innerHTML = msg;
                } else {
                    document.getElementById("OfficeDeliverMsg").innerHTML = msg;
                }
            });
            break;
    }
}

//function for return items in turkey
function returnTurkeyFunc(action) {
    var orderID = document.getElementById("rowID").value;
    var returnReason = document.getElementById("returnReason").value;
    switch (action) {
        case "submit":
            $.getJSON("./return-turkey.php?action=" + action + "&orderID=" + orderID + "&returnReason=" + returnReason, function (data) {
                //only orders that has arived to the office can be returned to the tury
                var result = data.result;
                var msg = data.msg;
                if (result === "exsist") {
                    document.getElementById("returnTurkeyMsg").innerHTML = msg;
                } else {
                    document.getElementById("returnTurkeyMsg").innerHTML = msg;
                }
            });
            break;
    }
}

//function for add order in turkey
function addOrderCheck(action) {
    var orderID = document.getElementById("rowID").value;
    var shoppingDate = document.getElementById("shoppingDate").value;
    var supplierRefCode = document.getElementById("supplierRefCode").value;
    switch (action) {
        case "submit" :
            $.getJSON("./addorder.php?action=" + action + "&orderID=" + orderID + "&shoppingDate=" + shoppingDate + "&supplierRefCode=" + supplierRefCode, function (data) {
                //check the order status and shows a proper msg
                var result = data.result;
                var msg = data.msg;
                if (result === "exsist") {
                    document.getElementById("addOrderMsg").innerHTML = msg;
                } else {
                    document.getElementById("addOrderMsg").innerHTML = msg;
                }
            });
            break;
        case "reset" :
            $.getJSON("./addorder.php?action=" + action + "&orderID=" + orderID + "&shoppingDate=" + shoppingDate, function (data) {
                //check the order status and shows a proper msg
                var result = data.result;
                var msg = data.msg;
                if (result === "exsist") {
                    document.getElementById("addOrderMsg").innerHTML = msg;
                } else {
                    document.getElementById("addOrderMsg").innerHTML = msg;
                }
            });
            break;
    }
}
//Function to add member and customer information in to the database
function addMemberFunc(action) {
    var customerName = document.getElementById("customerName").value;
    var customerTel = document.getElementById("customerTel").value;
    var customerTelegramID = document.getElementById("customerTelegramID").value;
    if (customerName === "" || customerTel === "" || customerTelegramID === "") {
        alert("خطا یکی از اطلاعات مرتبط با مشتری وارد نشده است!");
    } else {
        $.getJSON("./addmember.php?action=" + action + "&customerName=" + customerName + "&customerTel=" + customerTel + "&customerTelegramID=" + customerTelegramID, function (data) {
            //check the member status
            var result = data.result;
            var msg = data.msg;
            if (result === "exsist") {
                //if customer has already exist in the db
                document.getElementById("memberMsg").innerHTML = msg;
                $("#submitOrderButton").prop('disabled', false);
                $("#memberSubmitButton").prop('disabled', true);
                $("#customerName").prop('disabled', true);
                $("#customerTel").prop('disabled', true);
                $("#customerTelegramID").prop('disabled', true);
            }
            //if customer does not exist in the db and this new customer added to the system
            if (result === "success") {
                document.getElementById("memberMsg").innerHTML = msg;
                $("#submitOrderButton").prop('disabled', false);
                $("#memberSubmitButton").prop('disabled', true);
            }
        });
    }

}

//Function to check numeric numbers only in customer Tel section in home.php
function checkTel() {
    var tel = document.getElementById("customerTel").value;
    // check if price is all numeric
    var illegalChar = /[^0-9.]/g;
    if ((illegalChar.test(tel)) || tel.length < 11) {
        document.getElementById("telAlert").innerHTML = "تلفن موبایل به صورت کامل، فقط از اعداد استفاده کنید";
        $("#submitOrderButton").prop('disabled', true);
        $("#memberSubmitButton").prop('disabled', true);
        priceFlag = false;
        return telFlag;
    } else
    {
        document.getElementById("telAlert").innerHTML = "";
        $("#memberSubmitButton").prop('disabled', false);
        priceFlag = true;
        return telFlag;
    }

}


//Function to print code on label
function printCodeFunc(printCode) {
    var printContent = printCode;
    window.print();
}






