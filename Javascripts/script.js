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
//Function to return currency and its Rate based on the selected country
var exchange = function (country) {
    switch (country) {
        case "ترکیه":
            var currency = "TL";
            var rate = 1230;
            break;
        case "انگلیس":
            var currency = "UK-Pound";
            var rate = 5300;
            break;
    }
    return {
        currency: currency,
        rate: rate
    }
}

//Function to automatically calculate the price for sellers in calculator.php
function calculator(userID, country, clothesType, productPrice) {
    switch (country) {
        case "ترکیه":
            var country = "Turkey";
            var currencyRate = 1230;
            var weightCost = 55000; //55000 per killo dor kargo + 5000 Peik Iran
            var shippingCost = 50000;
            break;
        case "انگلیس":
            var country = "UK";
            var currencyRate = 5200;
            var weightCost = 70000; //70000 per killo dor kargo + 5000 Peik Iran
            var shippingCost = 65000;
            break;
    }
//final price is calculated based on userID, userMargin variable keeps this value for adding up to users final price.
    switch (userID) {
        case "4":
        case "8":
        case "9":
        case "21":
        case "22":
        case "23":
            var userMargin = 0;
            break;
        default:
            var userMargin = 0;
            break;
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
    if (country === "UK") {
        benneksMargin = 0.17;
    }
    switch (clothesType) {
//Man and Women bag
        case "کیف زنانه":
        case "کیف مردانه":
            totalCost = (productPrice * currencyRate) + ((productPrice * currencyRate) * benneksMargin) + shippingCost + ((productPrice * currencyRate) * userMargin);
            return totalCost;
            break;
            //Man and Women shoes
        case "کفش زنانه":
        case "کفش مردانه":
            totalCost = (productPrice * currencyRate) + ((productPrice * currencyRate) * benneksMargin) + shippingCost + ((productPrice * currencyRate) * userMargin);
            return totalCost;
            break;
            //Man and Women products around 200 gr
        case "پیراهن مردانه":
        case "تی شرت مردانه":
        case "لباس زیر مردانه":
        case "شلوارک مردانه":
        case "مایو مردانه":
        case "شلوارک زنانه":
        case "تاپ زنانه":
        case "پیراهن زنانه":
        case "لباس زیر زنانه":
        case "تی شرت زنانه":
        case "کلاه، شال، روسری، دستکش":
        case "بیکینی":
        case "رو مایو زنانه":
        case "لباس خواب زنانه":
        case "ساپورت زنانه":
            shippingCost = (weightCost * 200) / 1000;
            totalCost = (productPrice * currencyRate) + ((productPrice * currencyRate) * benneksMargin) + shippingCost + ((productPrice * currencyRate) * userMargin);
            return totalCost;
            break;
            //Man and Women products around 450 gr
        case "شلوار معمولی مردانه":
        case "شلوار جین مردانه":
        case "پلیور مردانه سبک":
        case "کاردیگان زنانه":
        case "پلیورهای نازک زنانه":
        case "جین زنانه":
        case "شلوار معمولی زنانه":
        case "پانچو":
        case "شومیز زنانه":
        case "بلوز زنانه":
        case "دامن زنانه":
        case "پیراهن بلند زنانه":
            shippingCost = (weightCost * 450) / 1000;
            totalCost = (productPrice * currencyRate) + ((productPrice * currencyRate) * benneksMargin) + shippingCost + ((productPrice * currencyRate) * userMargin);
            return totalCost;
            break;
            //Man and Women products around 600 gr
        case "کت معمولی مردانه":
        case "کت زمستانی زنانه":
        case "کاپشن سبک زنانه":
        case "بارونی زنانه":
        case "مانتو زنانه":
        case "کت تابستانی زنانه":
            shippingCost = (weightCost * 600) / 1000;
            totalCost = (productPrice * currencyRate) + ((productPrice * currencyRate) * benneksMargin) + shippingCost + ((productPrice * currencyRate) * userMargin);
            return totalCost;
            break;
            //Man and Women products around 800 gr
        case "گرمکن مردانه":
        case "کت جین مردانه":
        case "پلیور مردانه سنگین":
        case "کاپشن سبک مردانه":
        case "گرمکن زنانه":
        case "کت جین زنانه":
        case "کت چرم زنانه":
        case "کاپشن سنگین زنانه":
        case "پلیور سنگین زنانه":
        case "کت جیر زنانه":
        case "پالتو زنانه":
            shippingCost = (weightCost * 800) / 1000;
            totalCost = (productPrice * currencyRate) + ((productPrice * currencyRate) * benneksMargin) + shippingCost + ((productPrice * currencyRate) * userMargin);
            return totalCost;
            break;
            //Man and Women products more than 1 kg
        case "کاپشن سنگین مردانه":
        case "کت چرم مردانه":
        case "پالتو مردانه":
        case "اورکت مردانه":
        case "کت شلوار مردانه":
        case "سنگین زنانه":
            shippingCost = (weightCost * 1200) / 1000;
            totalCost = (productPrice * currencyRate) + ((productPrice * currencyRate) * benneksMargin) + shippingCost + ((productPrice * currencyRate) * userMargin);
            return totalCost;
            break;
        case "کیف پول":
        case "کمربند":
        case "عینک":
        case "عطر":
        case "ساعت":
        case "زیورآلات":
        case "جوراب":
            shippingCost = (weightCost * 120) / 1000;
            totalCost = (productPrice * currencyRate) + ((productPrice * currencyRate) * benneksMargin) + shippingCost + ((productPrice * currencyRate) * userMargin);
            return totalCost;
            break;
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








