<?php

define('API_KEY', '463292906:AAFXW6XGDdA3jZqFkBwKjTH8qWBbIl_w0vs');
$keyboard = ['a', 'b'];

function bot($method, $data = []) {
    $url = "https://api.telegram.org/bot" . API_KEY . "/" . $method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $res = curl_exec($ch);
    file_put_contents('ch.txt', $data);
    if (curl_error($ch)) {
        var_dump(curl_error($ch));
    } else {
        return json_decode($res);
    }
}

$content = file_get_contents("php://input");
//fetch update
$update = json_decode($content, TRUE);
$message = $update["message"];
$chatID = $message["chat"]["id"];
$text = $message["text"];
//statement begining
if ($text == "/start") {
    //bot('sendMessage', ['chat_id' => $chatID, 'text' => "hello hamedi"]);
    /*$reply_markup = replyKeyboardMarkup([
        'keyboard' => $keyboard,
        'resize_keyboard' => TRUE,
        'one_time_keyboard' => TRUE
    ]);*/
    bot('sendMessage', ['chat_id' => $chatID, 'text' => "hello hamedi", 'replyKeyboardMarkup', 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true]);
    //$txt = json_encode(array(chat_id => $chatID, text => "hello hamedi"));
    //file_put_contents('log.txt', $txt);
    //bot('sendMessage', $txt);
}
?>

