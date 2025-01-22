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
    $user_name = $message['chat']['username'];
    $chat_type = $message['chat']['type'];
    $date = $message['date'];
    $user = $db->query("SELECT * FROM `users` WHERE `user_id` = $from_id")->fetch_object();
}



if ($chat_type != 'private') {
    die;
}

if (preg_match('/^(\/start) inv_(.*)/', $text, $match)) {
    $user_referal_code = $match[2];
    if (! isset($user)) {

        // ADD NEW USER TO DB
        $random_str = generateRandomString();
        $sql1 = "INSERT INTO `omidreza_zirmajmue`.`users` (`user_id`, `referal_code`) VALUES (?,?)";
        $prepare = $db->prepare($sql1);
        $prepare->bind_param('is', $from_id, $random_str);
        $prepare->execute();
        $prepare->close();

        // UPDATE & ADD REFERAL
        $sql2 = "UPDATE
        `users` SET `referals` = `referals` + 1,
        `wallet` = `wallet` + {$wallet_add}
        WHERE `referal_code` = '{$user_referal_code}'";
        $db->query($sql2);

        // send message TO (INVITER)
        $sql3 = "SELECT `user_id` FROM `users` WHERE `referal_code` = '{$user_referal_code}'";
        $user_invite_data = $db->query($sql3);
        $user_invite_id = $user_invite_data->fetch_object()->user_id;
        $user_invite_referals = $user_invite_data->fetch_object()->referals;
        $user_invite_wallet = $user_invite_data->fetch_object()->wallet;




        $msg2 = "🥂یک نفر رقرال گرفتی\n" .
            "آیدی عددیش : `{$from_id}`\n" .
            "یوزر نیم : @{$user_name}\n" .
            "اسمش : `{$first_name}`";
        sendMessage($user_invite_id, $msg2, parse_mode: 'Markdown');
    }

    //send message to invited (NEW) user
    $msg1 = 'سلام خوشششش اومدید';
    sendMessage($from_id, $msg1);
}

if ($text == '/start') {
    if (! isset($user)) {
        $random_str = generateRandomString();
        $sql = "INSERT INTO `omidreza_zirmajmue`.`users` (`user_id`, `referal_code`) VALUES (?,?)";
        $prepare = $db->prepare($sql);
        $prepare->bind_param('is', $from_id, $random_str);
        $prepare->execute();
        $prepare->close();
        $msg = 'سلام خوش اومدی ! 🌚';
        sendMessage($from_id, $msg);
    }

    //send message to new user
    setStep('home');
    $msg = 'به ربات رفرال خوش آمدید 🌟';
    sendMessage($from_id, $msg, reply_markup: $keyboard_home);
    die;
}

$step = $user->step;

if ($step == 'home') {
    $msg = '☠️بیا کارت دارم☠️';
    sendMessage($from_id, $msg, reply_markup: $keyboard_home);
    die;
}
