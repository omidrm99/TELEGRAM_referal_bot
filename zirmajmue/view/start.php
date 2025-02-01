<?php

if (preg_match('/^(\/start) confirm_(.*)/', $text, $match)) {
    if (!in_array($from_id, $bot_admins)) {
        sendMessage($from_id, $error_msg);
        setStep('home');
        die;
    }


    $balance_request_id = $match[2];
    $v = $db->query("SELECT * FROM `balance_request` WHERE  `id` =$balance_request_id")->fetch_object();

    if ($v->status == 'done') {
        $msg = "درخواست واریزی {$balance_request_id} قبلا تایید شده";
        sendMessage($from_id, $msg);
        die;
    }

    $status = 'done';
    $balance_request_user_id = $v->user_id;
    $balance_request_balance = $v->balance;

    $sql = "UPDATE `users` SET `wallet` = `wallet` - $balance_request_balance WHERE `user_id` =$balance_request_user_id";
    $db->query($sql);





    $sql = "UPDATE `balance_request` SET `status` = ? WHERE `id` = ? ";
    $prepare = $db->prepare($sql);
    $prepare->bind_Param("si", $status, $balance_request_id);
    $prepare->execute();
    $prepare->close();

    $msg = 'واریز تایید شد';
    sendMessage($from_id, $msg, reply_markup: $keyboard_home);

    setStep('home');
    $msg = 'واریز موفق بود';

    sendMessage($balance_request_user_id, $msg);
    $msg = "ذرخواست واریز شناسه {$balance_request_id} تایید شد !";

    sendMessage($bot_channels_id['request'], $msg);

    die;
}
if (preg_match('/^(\/start) inv_(.*)/', $text, $match)) {
    if (!isset($user)) {
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
        $sql2 = "UPDATE `users` SET `referals` = `referals` + 1, `wallet` = `wallet` + {$wallet_add}, `last_referal_id` = {$from_id} WHERE `referal_code` = '{$user_referal_code}'";
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
    if (!isset($user)) {
        // ADD NEW USER TO DB
        $random_str = generateRandomString();
        $sql = "INSERT INTO `omidreza_zirmajmue`.`users` (`user_id`, `referal_code`) VALUES (?,?)";
        $prepare = $db->prepare($sql);
        $prepare->bind_param('is', $from_id, $random_str);
        $prepare->execute();
        $prepare->close();
        $msg = 'سلام خوش اومدی ! 🌚';
        sendMessage($from_id, $msg, reply_markup: $keyboard_home);
        setStep('home');
        die;
    }

    //send message to new (NOT INVITED) user
    setStep('home');
    $msg = 'به ربات رفرال خوش برگشتی 🌟';
    sendMessage($from_id, $msg, reply_markup: $keyboard_home);
    die;
}
