<?php
ob_start();
session_start();
require 'src/benneks.php';
if (isset($_SESSION['user']) != "") {
    header("Location: index.php");
    exit();
}

$user = new user();
$_SESSION['order'] = $user;
$errMSG = "";
date_default_timezone_set("Asia/Tehran");

if (isset($_POST['signupButton'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $accessLevel = "1";
    $registerDate = date("Y-m-d");
    $registerTime = date("H:i:s");

    $username = strip_tags($username);
    $email = strip_tags($email);
    $password = strip_tags($password);
    // password encrypt using SHA256()
    $password = hash('sha256', $password);
    //check email exist or not
    $query = "SELECT userEmail FROM benneks.users WHERE userEmail='" . $email . "'";
    $existUserResult = $user->existUser($query);
    $count = mysqli_num_rows($existUserResult);
    //if email not found then proceed
    if ($count == 0) {
        $query = "INSERT INTO benneks.users (userName, userEmail, userPass, userAccess, userRegisterDate, userRegisterTime) VALUES ('$username', '$email', '$password','$accessLevel','$registerDate','$registerTime')";
        $addUserRes = $user->addUser($query);
        // if signup process goes well then user will redirect to home.php 
        if ($addUserRes) {
            $errTyp = "sucess";
            $errMSG = "Successfully Registered, you may login now";
            header("Location: home.php");
        } else {
            $errTyp = "danger";
            $errMSG = "Something went wrong, try again later ...";
        }
    } else {
        $errTyp = "warning";
        $errMSG = "Sorry Username or Email already in use...";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset = "utf-8">
        <meta http-equiv = "X-UA-Compatible" content = "IE=edge">
        <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
        <meta name = "description" content = "benneks control panel">
        <meta name = "author" content = "hamed">
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"/></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"/></script>
<script type="text/javascript" src="./Javascripts/script.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="http://ifont.ir/apicode/33" rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="style.css" />  
<!-- custom fonts -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
<title>Benneks Order System</title>
</head>
<body style="background-color: #CF5F5C">
    <div class = "container-fluid">
        <div class = "row">
            <div class = "col-sm-4"> </div>
            <div class = "col-sm-4"> </div>
            <div class = "col-sm-4"> </div>
        </div>
        <div class = "row" style="margin-top: 100px">
            <div class = "col-sm-4"> </div>
            <div class = "col-sm-4">
                <ul class = "nav nav-pills nav-justified">
                    <li class = "active"> <a data-toggle = "pill" href="#signIn"> Sign In </a> </li>
                    <li> <a data-toggle = "pill" href = "#signUp"> Sign Up </a> </li>
                </ul>

                <div class = "list-group">
                    <h3  style="color: white"> <center> Welcome </center> </h3>	
                </div>

                <div class = "tab-content">
                    <div class = "tab-pane fade in active" id = "signIn">
                        <form class = "form-horizontal"  role = "form" action = "login.php" method = "post" >
                            <div class = "form-group" >
                                <label class = "control-label col-sm-4" for = "email" style="color:white"> Email: </label>
                                <div class="col-sm-5">
                                    <input type = "email" class = "form-control" name = "email" placeholder = "Enter Email">
                                </div>
                            </div>
                            <div class = "form-group">
                                <label class = "control-label col-sm-4" for = "password" style="color:white"> Password: </label>
                                <div class = "col-sm-5">
                                    <input type = "password" class = "form-control" name = "password" placeholder = "Enter Password">
                                </div>
                            </div>
                            <div class = "form-group">
                                <label class = "control-label col-sm-4" style="color:white"> Click to Login </label>
                                <div class = "col-sm-5">
                                    <button class = "btn btn-primary btn-block" class = "form-control" name = "loginButton"> Login </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id = "signUp" class = "tab-pane fade">
                        <form class = "form-horizontal" role = "form" method = "post" onsubmit="return submitActivation();">
                            <div class = "form-group has-feedback">
                                <label class = "control-label col-sm-4" for = "username" style="color:white"> Username: </label>
                                <div class = "col-sm-5">
                                    <input type = "text" class = "form-control" name = "username" id = "username" onkeyup = "checkUserName();" placeholder = "Choose Username" required>
                                    <span id = "userNameIcon" class = "glyphicon form-control-feedback"> </span>
                                    <span id = "userNameText">  </span>
                                </div>
                            </div>
                            <div class = "form-group has-feedback">
                                <label class = "control-label col-sm-4" for = "email" style="color:white"> Email: </label> 
                                <div class = "col-sm-5">
                                    <input type = "email" class = "form-control" name = "email" id = "email" onkeyup = "checkEmail();" placeholder = "Enter Email" required>
                                    <span id = "emailIcon" class = "glyphicon form-control-feedback"> </span>
                                    <span id = "emailText">  </span>
                                </div>
                            </div>
                            <div class = "form-group has-feedback">
                                <label class = "control-label col-sm-4" for = "password" style="color:white"> Password: </label>
                                <div class = "col-sm-5">
                                    <input type = "password" class = "form-control" name = "password" id = "password" onkeyup = "checkPass();" placeholder = "Password" required>
                                    <span id = "passIcon" class = "glyphicon form-control-feedback"> </span>
                                    <span id = "passText">  </span>
                                </div>
                            </div>
                            <div class = "form-group has-feedback">
                                <label class = "control-label col-sm-4" for = "re-password" style="color:white"> Password Again: </label>
                                <div class = "col-sm-5">
                                    <input type = "password" class = "form-control" name = "re-password" id = "re-password" onkeyup = "passAgain();"  placeholder = "Repeat Password" required>
                                    <span id = "passReIcon" class = "glyphicon form-control-feedback"> </span>
                                    <span id = "passReText">  </span>
                                </div>
                            </div>
                            <div class = "form-group">
                                <label class = "control-label col-sm-4" style="color:white"> Click to Join! </label>
                                <div class = "col-sm-5">
                                    <button class = "btn btn-primary btn-block" class = "form-control" name= "signupButton" id = "signupButton"> Signup </button>
                                </div>
                                <div class = "col-sm-12" id = "submitResultDialog" title = "Signup"> <?php echo $errMSG ?> </div>
                            </div>	
                        </form>
                    </div>
                </div>
            </div>

            <div class = "col-sm-4"> </div>
        </div>
    </div>
    <p id="test"> </p>
</body>
</html>
