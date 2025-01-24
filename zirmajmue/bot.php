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
    if (! isset($user)) {

        // ADD NEW USER TO DB
        $random_str = generateRandomString();
        $sql1 = "INSERT INTO `omidreza_zirmajmue`.`users` (`user_id`, `referal_code`) VALUES (?,?)";
        $prepare = $db->prepare($sql1);
        $prepare->bind_param('is', $from_id, $random_str);
        $prepare->execute();
        $prepare->close();



        $user_referal_code = $match[2];

        $sql = "SELECT * FROM `users` WHERE `referal_code` = '{$user_referal_code}'";
        $user_inviter_data = $db->query($sql)->fetch_object();
    }


    // check referal code
    if (isset($user_inviter_data->user_id)) {

        // UPDATE & ADD REFERAL
        $sql2 = "UPDATE
            `users` SET `referals` = `referals` + 1,
            `wallet` = `wallet` + {$wallet_add},
            `last_referal_id` = {$from_id}
            WHERE `referal_code` = '{$user_referal_code}'";
        $db->query($sql2);


        // send message TO (INVITER)
        $user_inviter_id = $user_inviter_data->user_id;
        $user_inviter_referals = $user_inviter_data->referals;
        $user_inviter_wallet = $user_inviter_data->wallet;

        $msg1 = "🥂یک نفر رقرال گرفتی
یوزر نیم : @{$user_name}
اسمش : {$first_name}
موجودی حساب شما : {$user_inviter_wallet} تتر";
        sendMessage($user_inviter_id, $msg1);




        $sql3 = "INSERT INTO `omidreza_zirmajmue`.`referals` (`inviter_user_id`, `invited_user_id`) VALUES (?,?)";
        $prepare = $db->prepare($sql3);
        $prepare->bind_param('ii', $user_inviter_id, $from_id);
        $prepare->execute();
        $prepare->close();



        $msg2 = 'سلام خوششششششششششششششش اومدید';
        sendMessage($from_id, $msg2);
        die;
    }
    //send message to invited (NEW) user
    $msg3 = 'سلام خوشششش اومدید';
    sendMessage($from_id, $msg3);
    die;
}



if (preg_match('/\/start/', $text, $match)) {
    if (! isset($user)) {
        // ADD NEW USER TO DB
        $random_str = generateRandomString();
        $sql = "INSERT INTO `omidreza_zirmajmue`.`users` (`user_id`, `referal_code`) VALUES (?,?)";
        $prepare = $db->prepare($sql);
        $prepare->bind_param('is', $from_id, $random_str);
        $prepare->execute();
        $prepare->close();
        $msg = 'سلام خوش اومدی ! 🌚';
        sendMessage($from_id, $msg, reply_markup: $keyboard_home);
        die;
    }

    //send message to new (NOT INVITED) user
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
debug('end');
