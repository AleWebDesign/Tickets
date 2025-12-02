<?php

error_reporting(1);

$Token='';
$chat_id='';
$API="https://api.telegram.org/bot".$Token;
$url=$API."/sendMessage?chat_id=".$chat_id;

$body = $_GET["body"];

$data = [
    'text' => $body,
    'chat_id' => $chat_id,
    'parse_mode' => 'html'
];

$ejecutar = file_get_contents("https://api.telegram.org/bot$Token/sendMessage?" . http_build_query($data) );

echo $ejecutar;

?>