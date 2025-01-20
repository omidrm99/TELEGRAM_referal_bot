<?php

require "functions.php";



$input = file_get_contents("php://input");
$update = json_decode($input, true);
$message = $update['message'];
$text = $message['text'];


if (isset($text)) {
    sendMessage(207850708, $text);
}
