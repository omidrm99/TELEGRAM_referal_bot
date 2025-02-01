<?php

if (array_key_exists(key: 'message', array: $update)) {
    $message = $update['message'];
    $text = $message['text'];
    $message_id = $message['message_id'];
    $from_id = $message['from']['id'];
    $first_name = $message['from']['first_name'];
    $user_name = $message['chat']['username'];
    $chat_type = $message['chat']['type'];
    $date = $message['date'];
    $user = $db->query("SELECT * FROM `users` WHERE `user_id` = $from_id")->fetch_object();
}