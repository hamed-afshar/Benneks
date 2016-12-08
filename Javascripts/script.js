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
//Function to return TL Rate
function currencyRate() {
    var rateTL = 1200;
    return rateTL;
}

//Function to automatically calculate the price for sellers in calculator.php
function calculator(clothesType, productPrice) {
    var rateTL = 1200;
    var weightCost = 55000; //50000 per killo dor kargo + 5000 Peik Iran
    var shippingCost = 0;
    var benneksMargin = 0;
    var totalCost = 0;
    if (productPrice >= 0 && productPrice <= 100) {
        benneksMargin = 0.2;
    } else if (productPrice > 100 && productPrice <= 200) {
        benneksMargin = 0.15;
    } else {
        benneksMargin = 0.1;
    }
    switch (clothesType) {
        //Man and Women bag
        case "women-bag":
        case "man-bag":
            shippingCost = 50000;
            totalCost = (productPrice * rateTL) + ((productPrice * rateTL) * benneksMargin) + shippingCost;
            return totalCost;
            break;
            //Man and Women shoes
        case "women-shoes":
        case "man-shoes":
            shippingCost = 50000;
            totalCost = (productPrice * rateTL) + ((productPrice * rateTL) * benneksMargin) + shippingCost;
            return totalCost;
            break;
            //Man and Women products around 200 gr
        case "man-shirt":
        case "man-tshirt":
        case "man-underwear":
        case "man-short":
        case "man-overcoat":
        case "women-short":
        case "women-top":
        case "women-shirt":
        case "women-lingerie":
        case "women-tshirt":
        case "women-scarf":
        case "women-bikini":
        case "women-swimsuit":
        case "women-sleepwear":
        case "women-support":
            shippingCost = (weightCost * 200) / 1000;
            totalCost = (productPrice * rateTL) + ((productPrice * rateTL) * benneksMargin) + shippingCost;
            return totalCost;
            break;
            //Man and Women products around 450 gr
        case "man-pant":
        case "man-jean":
        case "man-sweater":
        case "women-cardigan":
        case "women-sweater":
        case "women-jean":
        case "women-pant":
        case "women-pancho":
        case "women-shomiz":
        case "women-blouse":
        case "women-skirt":
        case "women-dress":
            shippingCost = (weightCost * 450) / 1000;
            totalCost = (productPrice * rateTL) + ((productPrice * rateTL) * benneksMargin) + shippingCost;
            return totalCost;
            break;
            //Man and Women products around 600 gr
        case "man-coat":
        case "women-wintercoat":
        case "women-jacket":
        case "women-rainingcoat":
        case "women-manto":
        case "women-summerjacket":
            shippingCost = (weightCost * 600) / 1000;
            totalCost = (productPrice * rateTL) + ((productPrice * rateTL) * benneksMargin) + shippingCost;
            return totalCost;
            break;
            //Man and Women products around 800 gr
        case "man-sportwear":
        case "man-jean-coat":
        case "man-heavy-sweater":
        case "man-jacket":
        case "women-sportwear":
        case "women-jeancoat":
        case "women-leathercoat":
        case "women-winterjacket":
        case "women-heavysweater":
        case "women-jircoat":
        case "women-palto":
            shippingCost = (weightCost * 800) / 1000;
            totalCost = (productPrice * rateTL) + ((productPrice * rateTL) * benneksMargin) + shippingCost;
            return totalCost;
            break;
            //Man and Women products more than 1 kg
        case "man-heavy-jacket":
        case "man-leather-coat":
        case "man-palto":
        case "man-overcoat":
        case "man-suit":
        case "women-heavy":
            shippingCost = (weightCost * 1200) / 1000;
            totalCost = (productPrice * rateTL) + ((productPrice * rateTL) * benneksMargin) + shippingCost;
            return totalCost;
            break;
        case "wallet":
        case "belt":
        case "sunglass":
        case "perfium":
        case "watch":
        case "accessory":
        case "sucks":
            shippingCost = (weightCost * 120) / 1000;
            totalCost = (productPrice * rateTL) + ((productPrice * rateTL) * benneksMargin) + shippingCost;
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








