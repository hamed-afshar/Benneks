<?php
ob_start();
session_start();
require 'src/benneks.php';
// if session is not set this will redirect to login page
if (!isset($_SESSION['user'])) {
    header("Location: register.php");
    exit();
}
?>


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
<a href="logout.php?logout"> Signout </a>
</head>
<body>
    <div class="container-fluid">
        <div class="jombotron text-center">
            <h1 id="test"> Benneks Orders System </h1>
        </div> 
        <h2 id="header"> Order List </h2>
        <select id="userSelect">
            <option value="admin"> Admin </option>
            <option value="hadice"> Hadice </option>
            <option value = "ghazal"> Ghazal </option>
        </select>
        <table id="orderTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th id="col0"> <input type = "image" src = "./icons/add_icon.png" height="32" width="32" id = "addRow" onclick = "addRow();"> </th>
                    <th id="col1"> ØªØ§Ø±ÛŒØ® Ø³Ù�Ø§Ø±Ø´ <hr> SiparÄ±ÅŸ TarÄ±khi </th>
                    <th id="col2"> Ù†Ø§Ù… Ù…Ø´ØªØ±ÛŒ <hr>  MuÅŸteri Adi </th>
                    <th id="col3"> ØªÙ„Ù�Ù† Ù…Ø´ØªØ±ÛŒ <hr> MuÅŸteri Tel </th>
                    <th id="col4"> Ù†ÙˆØ¹ Ù„Ø¨Ø§Ø³ <hr> Kiyafetler</th>
                    <th id="col5"> Ø¨Ø±Ù†Ø¯ <hr> Brand </th>
                    <th id="col6"> Ø³Ø§ÛŒØ² <hr> Size</th>
                    <th id="col7"> Ù†ÙˆØ¹ Ø®Ø±ÛŒØ¯ <hr> AlÄ±ÅŸveriÅŸ Yapmak</th>
                    <th id="col8"> ÙˆØ¨ Ø³Ø§ÛŒØª <hr> Website </th>
                    <th id="col9"> Ù„ÛŒÙ†Ú© <hr> Link </th>
                    <th id="col10"> Ù‚ÛŒÙ…Øª <hr> Fiyat </th>
                    <th id="col11"> Ø¹Ú©Ø³ <hr> Resim </th>
                    <th id="col12"> Ú©Ø¯ Ø¨Ù†Ú©Ø³ <hr> Benneks code </th>
                    <th id="col13"> Ù‡Ø²ÛŒÙ†Ù‡ Ø­Ù…Ù„ ØªØ§ Ø§ÛŒØ±Ø§Ù† <hr> Cargo Fiyat </th>
                    <th id="col14"> ÙˆØ²Ù† <hr> AÄŸÄ±rlÄ±k </th>
                    <th id="col15">  Ù‡Ø²ÛŒÙ†Ù‡ Ø­Ù…Ù„ Ø¯Ø± ØªØ±Ú©ÛŒÙ‡ <hr> Cargo Fiyat in Turkey </th>
                    <th id="col16"> Ù…Ø§Ù„ÛŒØ§Øª Ø®Ø±ÛŒØ¯ <hr> KDV </th>
                    <th id="col17"> ØªØ§Ø±ÛŒØ® Ø®Ø±ÛŒØ¯ Ø¯Ø± Ø¨Ù†Ú©Ø³ <hr> AlÄ±ÅŸ Tarihi </th>
                    <th id="col18">   ØªØ§Ø±ÛŒØ® ØªØ­ÙˆÛŒÙ„ Ø¨Ù‡ Ø¨Ù†Ú©Ø³ <hr> GeliÅŸ Tarihi </th>
                    <th id="col19"> ÙˆØ¶Ø¹ÛŒØª <hr> AÃ§Ä±klama </th>
                    <th id="col20"> ØªØ§Ø±ÛŒØ® Ø±Ø³ÛŒØ¯Ù† Ø¨Ù‡ Ø§ÛŒØ±Ø§Ù† <hr> Irana GeliÅŸ TarÄ±hÄ± </th>
                    <th id="col21"> Ù‡Ø²ÛŒÙ†Ù‡ Ù†Ù‡Ø§ÛŒÛŒ <hr> Toplam Fiyati </th>
                    <th id="col22"> Ø«Ø¨Øª <hr> GÃ¶nder </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <p id="alert"> </p>
        <button type="submit" onclick="hideTurkey()"> hide </button>
    </div>
</body>
</html>
