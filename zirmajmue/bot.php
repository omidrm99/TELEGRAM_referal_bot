<?php

require "functions.php";
require "config.php";
// require "jdf.php";
require "keyboards.php";




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



if ($chat_type != 'private') {
    die;
}

if(preg_match(pattern: '/^(\/start) inv_(.*)/', subject: $text, matches: $match)) {
    debug($match);
    die;
}

if ($text == '/start') {
    if (! isset($user)) {
        $random_str = generateRandomString();
        $sql = "INSERT INTO `omidreza_zirmajmue`.`users` (`user_id`, `referal_code`) VALUES (?,?)";
        $prepare = $db->prepare($sql);
        $prepare->bind_param('is', $from_id, $random_str);
        $prepare->execute();
        $prepare->close();
    }
    setStep('home');
    $msg = 'سلام خوش اومدی !☀️';
    sendMessage($from_id, $msg, reply_markup: $keyboard_home);
    die;
}

$step = $user->step;

if ($step == 'home') {
    sendMessage($from_id, 'hi', reply_markup: $keyboard_home);
}
