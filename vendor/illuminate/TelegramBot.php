<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require 'vendor/autoload.php';
use Telegram\Bot\Api;

$_ENV['TELEGRAM_BOT_Token'] = '463292906:AAFXW6XGDdA3jZqFkBwKjTH8qWBbIl_w0vs';
$telegram = new Api();

$response = $telegram->getMe();

file_put_contents('log.txt', $response);

?>

