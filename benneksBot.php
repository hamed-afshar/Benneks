<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require 'vendor/autoload.php';
use Telegram\Bot\Api;

$telegram = new Api();
$responce = $telegram->getMe();

file_put_contents('log.txt', $responce);
?>
