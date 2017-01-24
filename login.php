<?php

ob_start();
session_start();
require 'src/benneks.php';


//it will never let you open index(login) page if session is set
if (isset($SESSION['user']) != "") {
    header("Location: home.php");
    exit;
}

$error = false;
$user = new user();
date_default_timezone_set("Asia/Tehran");
$userLastLoginDate = date("Y-m-d");
$userLastLoginTime = date("H:i:s");

if (isset($_POST['loginButton'])) {
//prevent sql injections/ clear user invalid inputs
    $email = trim($_POST['email']);
    $email = strip_tags($email);
    $email = htmlspecialchars($email);

    $password = trim($_POST['password']);
    $password = strip_tags($password);
    $password = htmlspecialchars($password);
//
    if (empty($email)) {
        $error = true;
        $emailError = "Please enter your email address.";
        echo $emailError;
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $emailError = "Please enter valid email address";
        echo $emailError;
    }

    if (empty($password)) {
        $error = true;
        $passError = "Please enter your password.";
        echo $passError;
    }
// if there is no error
    if (!$error) {
        $pass = hash('sha256', $password); //password using SHA256
        $query = "SELECT userID, userName, userPass, userAccess, userLock FROM benneks.users WHERE userEmail='" . $email . "'";
        $loginDetailsQuery = "UPDATE benneks.users SET userLastLoginDate = '$userLastLoginDate', userLastLoginTime = '$userLastLoginTime' WHERE userEmail='" . $email . "'"; //query to insert detail when user successfully logs in. 
        $loginRes = $user->loginUser($query);
        $row = mysqli_fetch_array($loginRes);
        $count = mysqli_num_rows($loginRes); // if username and password are correct it must return 1
        //For admin user
        if ($count == 1 && $row['userName'] == "benneksadmin" && $row['userPass'] == $pass) {
            $_SESSION['user'] = $row ['userID'];
            $_SESSION['userAccess'] = $row ['userAccess'];
            $user->loginUser($loginDetailsQuery);
            header("Location: admin.php");
        }
        //for Normal users
        else if ($count == 1 && $row['userPass'] == $pass && $row['userLock'] == 0) {
            $_SESSION['user'] = $row['userID'];
            $_SESSION['userAccess'] = $row ['userAccess'];
            $user->loginUser($loginDetailsQuery);
            header("Location: home.php");
        } else {
            $user->loginUser($loginDetailsQuery);
            $errMSG = "نام کاربری و یا کلمه عبور صحیح نمی باشد همچنین ممکن است حساب شما مسدود شده باشد.";
            echo $errMSG;
        }
    }
}
?>