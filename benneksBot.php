<?php
$benneksBotToken = "463292906:AAFXW6XGDdA3jZqFkBwKjTH8qWBbIl_w0vs";
$telegramURL = "https://api.telegram.org/bot".$benneksBotToken."/";

//read incoming info and grab the chatID
$content = file_get_contents("php://input");
$update = json_decode($content, TRUE);
$message = $update["message"];
$chatID = $message["chat"]["id"];
//file_put_contents('log.txt', $chatID);


//compose reply
$reply = "hello everyone!";

//send reply
$sendto = $telegramURL."sendmessage?chat_id=".$chatID."&text=".$reply;
file_get_contents($sendto);
file_put_contents('log.txt', $sendto);
echo $sendto;
?>

