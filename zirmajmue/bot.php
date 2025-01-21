<?php

require "functions.php";
require "config.php";
// require "jdf.php";
// require "keyboards.php";




$input = file_get_contents("php://input");
$update = json_decode($input, true);


if (array_key_exists(key: 'message', array: $update)) {
    $message = $update['message'];
    $text = $message['text'];
    $message_id = $message['message_id'];
    $from_id = $message['from']['id'];
    $first_name = $message['from']['first_name'];
    $user_name = $message['from']['user_name'];
    $chat_type = $message['chat']['type'];
    $date = $message['date'];
    $user = $db->query("SELECT * FROM `users` WHERE `user_id` = $from_id")->fetch_object();
}

debug($user->step);

 