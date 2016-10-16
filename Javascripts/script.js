//Flags to keep the submit button activation status 
var usernameFlag = false;
var emailFlag = false;
var charFlag = false;
var passFlag = false;
var passAgainFlag = false;
//Flags to keep making order submit button status home.php
var priceFlag = false;
var customerTelFlag = false;
////////////////////////////////////////////
var row = 0;
var index = 0;

//function to add new row to order table
function addRow() {
    var table = document.getElementById("orderTable").getElementsByTagName('tbody')[0];
    var tr = document.createElement("tr");
    for (var col = 0; col < 23; col++) {
        var td = document.createElement("td");
        var input = document.createElement("INPUT");
        var idCol = "row" + row + "-" + "col" + col;
        var idRow = "row" + row;
        tr.id = idRow;
        td.appendChild(input);
        td.setAttribute("id", idCol);
        tr.appendChild(td);
    }
    table.appendChild(tr);
    // select each row for adding different attributes to it
    var r = document.getElementById("row" + row);
    // Automatically add index number for each row
    r.cells[0].innerHTML = index;
    //Automatically add date picker in column 1
    document.getElementById("row" + row + "-" + "col1").innerHTML = "";
    r.cells[1].innerHTML = '<input  type="date" id="orderDate-row' + row + '-col1" />';
    //automatically add customer name field in column 2
    document.getElementById("row" + row + "-" + "col2").innerHTML = "";
    r.cells[2].innerHTML = '<input  type="input" id="customerName-row' + row + '-col2" />';
    //Automatically add tel number field in column 3
    document.getElementById("row" + row + "-" + "col3").innerHTML = "";
    r.cells[3].innerHTML = '<input type="number" id="customerTel-row' + row + '-col3" />';
    //Automatically Add option menu for clothes type in column 4
    document.getElementById("row" + row + "-" + "col4").innerHTML = "";
    r.cells[4].innerHTML = '<select id = clothesType-row' + row + '-col4 > <option value="shoes"> کفش </option> />';
    //r.cells[4].innerHTML = '<select> <option value="shoes"> کفش </option> />';
    //Automatically add brand in column 5
    document.getElementById("row" + row + "-" + "col5").innerHTML = "";
    r.cells[5].innerHTML = '<input type="input" id="brand-row' + row + '-col5" />';
    //Automatically add size in column 6
    document.getElementById("row" + row + "-" + "col6").innerHTML = "";
    r.cells[6].innerHTML = '<input type="input" id="size-row' + row + '-col6" />';
    //Automatically add option menu for shopping type in column 7
    document.getElementById("row" + row + "-" + "col7").innerHTML = "";
    r.cells[7].innerHTML = '<select id = shoppingType-row' + row + '-col7 > <option value="store"> فروشگاه </option> />';
    //Automatically add website name in column 8
    document.getElementById("row" + row + "-" + "col8").innerHTML = "";
    r.cells[8].innerHTML = '<input type="input" id="website-row' + row + '-col8" />';
    //Automatically add link in column 9
    document.getElementById("row" + row + "-" + "col9").innerHTML = "";
    r.cells[9].innerHTML = '<input type="input" id="link-row' + row + '-col9" />';
    //Automatically add price to column 10 
    document.getElementById("row" + row + "-" + "col10").innerHTML = "";
    r.cells[10].innerHTML = '<input type="input" id="price-row' + row + '-col10" />';
    //Automatically add upload file for picture in column 11
    document.getElementById("row" + row + "-" + "col11").innerHTML = "";
    r.cells[11].innerHTML = '<input type="file" id="orderPic-row' + row + '-col11" />';
    //Automatically add benneks code to column 12
    document.getElementById("row" + row + "-" + "col12").innerHTML = "";
    r.cells[12].innerHTML = '<input type="input" id="benneksCode-row' + row + '-col12" />';
    //Automatically add iranDeliverPrice to column 13
    document.getElementById("row" + row + "-" + "col13").innerHTML = "";
    r.cells[13].innerHTML = '<input type="input" id="iranDeliverPrice-row' + row + '-col13" />';
    //Automatically add weight in column 14
    document.getElementById("row" + row + "-" + "col14").innerHTML = "";
    r.cells[14].innerHTML = '<input type="input" id="weight-row' + row + '-col14" />';
    //Automatically add turkeyDeliverPrice in column 15
    document.getElementById("row" + row + "-" + "col15").innerHTML = "";
    r.cells[15].innerHTML = '<input type="input" id="turkeyDeliverPrice-row' + row + '-col15" />';
    ;
    //Automatically add KDV in column 16
    document.getElementById("row" + row + "-" + "col16").innerHTML = "";
    r.cells[16].innerHTML = '<input type="input" id="KDV-row' + row + '-col16" />';
    //Automatically Benneks shopping date in column 17
    document.getElementById("row" + row + "-" + "col17").innerHTML = "";
    r.cells[17].innerHTML = '<input  type="date" id="benneksShoppingDate-row' + row + '-col17" />';
    //Automatically Benneks delivery date in column 18
    document.getElementById("row" + row + "-" + "col18").innerHTML = "";
    r.cells[18].innerHTML = '<input  type="date" id="benneksDeliverDate-row' + row + '-col18" />';
    //Automatically add option menu for status in column 19
    document.getElementById("row" + row + "-" + "col19").innerHTML = "";
    r.cells[19].innerHTML = '<select id = status-row' + row + '-col19 > <option value="status"> تحویل داده  </option> />';
    //Automatically add Iran delivery date in column 20
    document.getElementById("row" + row + "-" + "col20").innerHTML = "";
    r.cells[20].innerHTML = '<input  type="date" id="iranDeliverDate-row' + row + '-col20" />';
    //Automatically add totallPrice in column 21
    document.getElementById("row" + row + "-" + "col21").innerHTML = "";
    r.cells[21].innerHTML = '<input type="input" id="totallPrice-row' + row + '-col21" />';
    //Automatically add submit button for status in column 22
    document.getElementById("row" + row + "-" + "col22").innerHTML = "";
    r.cells[22].innerHTML = '<input type = "image" src = "./icons/add_icon.png" height="32" width="32" onclick = "submit()"/>';
    row = row + 1;
    index = index + 1;
}

//function to submit a order row from order table into database
function submit() {
    var row = 0;
    //Order Information
    //var orderID="";
    var orderDate = document.getElementById("row" + row + "-" + "col1").value;
    var clothesType = document.getElementById("row" + row + "-" + "col4").value;
    var brand = document.getElementById("row" + row + "-" + "col5").value;
    var size = document.getElementById("row" + row + "-" + "col6").value;
    var shoppingType = document.getElementById("row" + row + "-" + "col7").value;
    var website = document.getElementById("row" + row + "-" + "col8").value;
    var link = document.getElementById("row" + row + "-" + "col9").value;
    var price = document.getElementById("row" + row + "-" + "col10").value;
    var pic = document.getElementById("row" + row + "-" + "col11").value;
    //var bennekscode = document.getElementById("").value;
    var iranDeliverPrice = document.getElementById("row" + row + "-" + "col12").value;
    var weight = document.getElementById("row" + row + "-" + "col13").value;
    var turkeyDeliverPrice = document.getElementById("row" + row + "-" + "col14").value;
    var KDV = document.getElementById("row" + row + "-" + "col15").value;
    var benneksShoppingDate = document.getElementById("row" + row + "-" + "col16").value;
    var benneksDeliverDate = document.getElementById("row" + row + "-" + "col17").value;
    var status = document.getElementById("row" + row + "-" + "col18").value;
    var iranDeliverDate = document.getElementById("row" + row + "-" + "col19").value;
    var totallPrice = document.getElementById("row" + row + "-" + "col20").value;
    // Customer Information
    //var customerID = document.getElementById("").value;
    var customerName = document.getElementById("row" + row + "-" + "col2").value;
    var customerTel = document.getElementById("row" + row + "-" + "col3").value;
    // send data to php script with json
    $.getJSON("/phpscripts/addDB.php?orderDate=" + orderDate + "&clothesType=" + clothesType + "&brand=" + brand + "&size=" + size
            + "&shoppingType=" + shoppingType + "&website=" + website + "&link=" + link + "&price=" + price + "&pic=" + pic + "&iranDeliverPrice=" + iranDeliverPrice
            + "&weight=" + weight + "&turkeyDeliverPrice=" + turkeyDeliverPrice + "&KDV=" + KDV + "&benneksShoppingDate=" + benneksShoppingDate + "&benneksDeliverDate=" + benneksDeliverDate
            + "&status" + status + "&iranDeliverDate=" + iranDeliverDate + "&totallPrice=" + totallPrice, function (data) {
                $("#alert").text(data.msg);
            });


}

// This function is used to hide useless column for Iranian 
function hideIran() {
    $(document).ready(function () {
        $("[id$=col14]").hide();
        $("[id$=col15]").hide();
        $("[id$=col16]").hide();
        $("[id$=col17]").hide();
        $("[id$=col18]").hide();
        $("[id$=col19]").hide();
    })
}

//This function is used to hide useless column for Turkish
function hideTurkey() {
    $(document).ready(function () {
        $("[id$=col1]").hide();
        $("[id$=col2]").hide();
        $("[id$=col3]").hide();
        $("[id$=col4]").hide();
        $("[id$=col5]").hide();
        $("[id$=col6]").hide();
        $("[id$=col7]").hide();
        $("[id$=col8]").hide();
        $("[id$=col9]").hide();
        $("[id$=col10]").hide();
        $("[id$=col13]").hide();
        $("[id$=col20]").hide();
        $("[id$=col21]").hide();
    })
}

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
    $.getJSON("/phpscripts/registerCheck.php?input=username&value=" + username, function (data) {
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

    $.getJSON("/phpscripts/registerCheck.php?input=email&value=" + email, function (data) {
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

//function to check flags and enable submit button
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
// function to check customer Tel in making order section in home.php
function checkCustomerTel() {
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
}

function activateOrderButton() {
    if ((priceFlag === true) && (customerTelFlag === true)) {
        $("#submitOrderButton").prop('disabled', false);
    }
}