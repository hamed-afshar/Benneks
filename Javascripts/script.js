//Flags to keep the submit button activation status 
var usernameFlag = false;
var emailFlag = false;
var charFlag = false;
var passFlag = false;
var passAgainFlag = false;
//Flags to keep making order submit button status home.php
var priceFlag = false;
//var customerTelFlag = false;
////////////////////////////////////////////
var row = 0;
var index = 0;

//Function to check username existence on registeration
function checkUserName() {
    var username = document.getElementsByName("username")[0].value;
    // check if username contains illegall characters or les than 3 chars
    var illegalChar = /([\W0-9])+/g;
    if ((illegalChar.test(username)) || username.length < 3) {
        $('#userNameIcon').closest('.form-group').removeClass('has-success').addClass('has-error');
        $('#userNameIcon').removeClass('glyphicon-ok').addClass('glyphicon-remove');
        $("#signupButton").prop('disabled', true);
        charFlag = false;
        return charFlag
    }
    $.getJSON("/Benneks/phpscripts/registerCheck.php?input=username&value=" + username, function (data) {
        // if user is already exist in database, shows proper icon and make the signup button disabled
        if (data.icon == "remove-icon") {
            $('#userNameIcon').closest('.form-group').removeClass('has-success').addClass('has-error');
            $('#userNameIcon').removeClass('glyphicon-ok').addClass('glyphicon-remove');
            $("#signupButton").prop('disabled', true);
            usernameflag = false;
            return usernameFlag;
        }
        // if username is new then show appropriate icon and make the signup button active
        else if (data.icon == "success-icon") {
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

    $.getJSON("/Benneks/phpscripts/registerCheck.php?input=email&value=" + email, function (data) {
        // if email is already exist in database, then shows proper icon and make the signup button disabled
        if (data.icon == "remove-icon" || email.length < 5) {
            $('#emailIcon').closest('.form-group').removeClass('has-success').addClass('has-error');
            $('#emailIcon').removeClass('glyphicon-ok').addClass('glyphicon-remove');
            $("#signupButton").prop('disabled', true);
            emailFlag = false;
            return emailFlag;
        }
        // if email is new then show appropriate icon and make the signup button active
        else if (data.icon == "success-icon") {
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
        $("#signupButton").prop('disabled', true);
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
    if (passwordRe != password) {
        $('#passReIcon').closest('.form-group').removeClass('has-success').addClass('has-error');
        $('#passReIcon').removeClass('glyphicon-ok').addClass('glyphicon-remove');
        $("#signupButton").prop('disabled', true);
        passAgainFlag = false;
        return passAgainFlag;
    }
    // //if password-re is equal to pass
    else if (passwordRe == password) {
        $('#passReIcon').closest('.form-group').removeClass('has-error').addClass('has-success');
        $('#passReIcon').removeClass('glyphicon-remove').addClass('glyphicon-ok');
        passAgainFlag = true;
        return passAgainFlag;
    }
}

//function to check flags and enable submit button in register.php
function submitActivation() {
    if ((usernameFlag == true) && (emailFlag == true) && (charFlag == true) && (passFlag == true) && (passAgainFlag == true)) {
        $("#signupButton").prop('disabled', false);
    }
}

//Function to automatically calculate the price for sellers in calculator.php
function calculator() {
    var rateTL = 1200;
    var weightCost = 50000;
    var shippingCost = 0;
    var benneksMargin = 0;
    var totalCost = 0;
    var productType = document.getElementById("productType").value;
    var productPrice = document.getElementById("productPrice").value;
    if (productPrice >= 0 && productPrice <= 100) {
        benneksMargin = 0.2;
    } else if (productPrice > 100 && productPrice <= 200) {
        benneksMargin = 0.15;
    } else {
        benneksMargin = 0.1;
    }
    switch (productType) {
        case "bag":
            shippingCost = 40000;
            totalCost = (productPrice * rateTL) + ((productPrice * rateTL) * benneksMargin) + shippingCost;
            document.getElementById("finalPrice").setAttribute("disabled", false);
            document.getElementById("finalPrice").value = totalCost;
            break;
        case "shoes":
            shippingCost = 45000;
            totalCost = (productPrice * rateTL) + ((productPrice * rateTL) * benneksMargin) + shippingCost;
            document.getElementById("finalPrice").setAttribute("disabled", false);
            document.getElementById("finalPrice").value = totalCost;
            break;
        case "blouse":
        case "short":
        case "shomiz":
        case "top":
        case "skirt":
        case "womenshirt":
        case "manshirt":
        case "dress":
        case "lingerie":
        case "tshirt":
        case "scarf":
        case "bikini":
        case "swimsuit":
        case "sleepwear":
        case "support":
        case "pancho":
        case "pant":
            shippingCost = (weightCost * 200) / 1000;
            totalCost = (productPrice * rateTL) + ((productPrice * rateTL) * benneksMargin) + shippingCost;
            document.getElementById("finalPrice").setAttribute("disabled", false);
            document.getElementById("finalPrice").value = totalCost;
            break;
        case "cardigan":
        case "rainingcoat":
        case "manto":
        case "sweater":
        case "summerjacket":
        case "jean":
        case "coat&skirt":
            shippingCost = (weightCost * 450) / 1000;
            totalCost = (productPrice * rateTL) + ((productPrice * rateTL) * benneksMargin) + shippingCost;
            document.getElementById("finalPrice").setAttribute("disabled", false);
            document.getElementById("finalPrice").value = totalCost;
            break;
        case "wintercoat":
        case "palto":
        case "jacket":
            shippingCost = (weightCost * 600) / 1000;
            totalCost = (productPrice * rateTL) + ((productPrice * rateTL) * benneksMargin) + shippingCost;
            document.getElementById("finalPrice").setAttribute("disabled", false);
            document.getElementById("finalPrice").value = totalCost;
            break;
        case "jeancoat":
        case "leathercoat":
        case "winterjacket":
        case "heavysweater":
            shippingCost = (weightCost * 800) / 1000;
            totalCost = (productPrice * rateTL) + ((productPrice * rateTL) * benneksMargin) + shippingCost;
            document.getElementById("finalPrice").setAttribute("disabled", false);
            document.getElementById("finalPrice").value = totalCost;
            break;
        case "heavy":
            shippingCost = (weightCost * 1200) / 1000;
            totalCost = (productPrice * rateTL) + ((productPrice * rateTL) * benneksMargin) + shippingCost;
            document.getElementById("finalPrice").setAttribute("disabled", false);
            document.getElementById("finalPrice").value = totalCost;
            break;
        case "wallet":
        case "belt":
        case "sunglass":
        case "perfium":
        case "watch":
        case "accessory":
            shippingCost = (weightCost * 120) / 1000;
            totalCost = (productPrice * rateTL) + ((productPrice * rateTL) * benneksMargin) + shippingCost;
            document.getElementById("finalPrice").setAttribute("disabled", false);
            document.getElementById("finalPrice").value = totalCost;
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
        priceFlag = true;
        return priceFlag;
    }

}
//Function to check numeric quantity only in making order section in home.php
function checkQuantity() {
    var quantity = document.getElementById("orderQuantity").value;
    // check if quantity is all numeric
    var illegalChar = /[^0-9.]/g;
    if ((illegalChar.test(quantity))) {
        document.getElementById("quantityAlert").innerHTML = "برای تعداد تنها از اعداد استفاده نمایید";
        $("#submitOrderButton").prop('disabled', true);
        priceFlag = false;
        return priceFlag;
    } else
    {
        document.getElementById("quantityAlert").innerHTML = "";
        priceFlag = true;
        return priceFlag;
    }

}
// function to check customer Tel in making order section in home.php
// This function will not be used in phase 1
/*function checkCustomerTel() {
    var tel = document.getElementById("customerTel").value;
    var illegalChar = /[^0-9]/g;
    if ((illegalChar.test(tel)) || tel.length < 11) {
        document.getElementById("telAlert").innerHTML = "شماره موبایل معتبر باید وارد گردد";
        $("#submitOrderButton").prop('disabled', true);
        customerTelFlag = false;
        return customerTelFlag;
    } else
    {
        document.getElementById("telAlert").innerHTML = "";
        customerTelFlag = true;
        return customerTelFlag;
    }
}*/
// function to activate order buttonin home.php
function activateOrderButton() {
    if (priceFlag === true) {
        $("#submitOrderButton").prop('disabled', false);
    }
}




