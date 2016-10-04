<?php
/* ob_start();
  session_start();
  require 'src/benneks.php';
  // if session is not set this will redirect to login page
  if (!isset($_SESSION['user'])) {
  header("Location: register.php");
  exit();
  } */
?>


<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="benneks control panel">
        <meta name="author" content="hamed">

        <!-- Bootstrap -->
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />

        <!-- JQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"/></script>

        <!-- MetisMenu CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.5.2/metisMenu.min.css"/>

        <!--Bootstrap Again -->
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"/></script>
        
        <!--Javascript src -->
        <script type="text/javascript" src="./Javascripts/script.js"></script>
        
        <!--Farsi Font-->
         <link rel="stylesheet" href="http://ifont.ir/apicode/30">
        
        <!--CSS Style-->
        <link rel="stylesheet" type="text/css" href="style.css" />
        
        <!-- Custom Fonts -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
        
        <title>Benneks Order System</title>
</head>
<body>
    <div id ="wrapper">
        <!-- Navigation Bar -->
        <div class="navbar navbar-default navbar-static-top farsi" role = "navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="home.php">Benneks Control Panel</a>
            </div>
            <ul class="nav navbar-top-links navbar-right">
                <!-- dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-envelope fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <li>
                            <a href="#">
                                <div>
                                    <strong> New Order </strong>
                                    <span class="pull-right text-muted">
                                        <em> Today </em>
                                    </span>
                                </div>
                                <div> New order has been submitted </div>
                            </a>
                        </li>
                        <li class="divider"> </li>
                        <li>
                            <a class="text-center" href="#">
                                <strong> Read All Messages </strong>
                                <i class="fa fa-angle-right"> </i>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- Dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-calculator"></i>
                    </a>
                </li>
                <!-- dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"> </i> <i class="fa fa-caret-down"> </i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li> <a href="#"><i class="fa fa-user fa-fw"> </i> پروفایل کاربر </a> </li>
                        <li> <a href="#"><i class="fa fa-gear fa-fw"> </i> تنظیمات </a> </li>
                        <li class="divider"> </li>
                        <li> <a href="logout.php"> <i class="fa fa-sign-out fa-fw"> </i> خروج </a> </li>
                    </ul>
                </li>
            </ul>

        <!-- navigation bar on right -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu"
                    <li class="sidebar-search">
                        <div class="input-group custom-search-form">
                            <input type="text" class="form-control" placeholder="Search...">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </li>
                    <li>
                        <a href="home.php"> <i class="fa fa-dashboard fa-fw"></i> Dashboard </a>
                    </li>
            </div>
        </div>
    </div>
</body>
</html>
