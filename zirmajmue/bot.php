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
$error_msg = '❌ دستور مورد نظر یافت نشد 
لطفا از کیبور استفاده بکنید';
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


$step = getStep($from_id);

if ($step == 'home') {
    if ($text == '💡 راهنمای ربات') {
        $msg = 'محل قرار گیری راهنمای ربات';
        sendMessage($from_id, $msg, reply_markup: $keyboard_home);
        die;
    }
    if ($text == '⁉️ سوالات متداول') {
        $msg = 'محل قرار گیری سوالات متداول ربات';
        sendMessage($from_id, $msg, reply_markup: $keyboard_home);
        die;
    }
    if ($text == '🔐 حساب کاربری') {
        $wallet = $user->wallet;
        $wallet_address = $user->wallet_address;
        $referals = $user->referals;
        setStep('account');


        if (isset($wallet_address)) {
            $msg = "🔐 اطلاعات حساب شما : 

    🔶 تعداد زیر مجموعه : {$referals}
    🔶 موجودی حساب : {$wallet}
    🔶 آدرس ولت شما : {$wallet_address}
    🔱 با استفاده از دکمه ( برداشت وجه ) میتوانید موجودی حساب خود را برداشت بکنید.
    
    @zirmajmuebot";
            sendMessage($from_id, $msg, reply_markup: $keyboard_account_wallet);
            die;
        } else {
            $msg = "🔐 اطلاعات حساب شما : 

    🔶 تعداد زیر مجموعه : {$referals}
    🔶 موجودی حساب : {$wallet}
     
    
    🔱 با استفاده از دکمه ( برداشت وجه ) میتوانید موجودی حساب خود را برداشت بکنید.
    
    @zirmajmuebot";
            sendMessage($from_id, $msg, reply_markup: $keyboard_account);
            die;
        }
    }
    if ($text == '💬 پشتیبانی') {
        setStep('support');
        $msg = "🔶 پیام خود را وارد کنید : 

🟥 پیام های شما تا 24 ساعت جواب داده میشوند
- پیام خود را بنویسید و ارسال بکنید

⚠️ پشتیبانی مستقیم :
{$support_bot[0]}";
        sendMessage($from_id, $msg, reply_markup: $keyboard_back);
        die;
    }
    if ($text == '⭐️ زیرمجموعه گیری') {
        $referal_code = $user->referal_code;
        $msg_caption = "لینک زیر مجموعه گیر شما
        https://t.me/{$bot_username}/?start=inv_{$referal_code}";
        sendPhoto($from_id, $photo_banner, caption: $msg_caption, protect_content: false);
        die;
    }

    sendMessage($from_id, $error_msg, reply_markup: $keyboard_home);
    die;
}
if ($step == 'account') {
    if ($text == '🔙 بازگشت') {
        $msg = 'منوی اصلی';
        setStep('home');
        sendMessage($from_id, $msg, reply_markup: $keyboard_home);
        die;
    }
    if ($text == '💳 برداشت وجه') {
        if (!($user->wallet_address)) {
            $msg = '🟥 آدرس ولت خود را ثبت بکنید';
            sendMessage($from_id, $msg, reply_markup: $keyboard_account);
            die;
        }
        if ($user->wallet < 5.0) {
            $msg = '🔴 موجودی حساب شما کمتر از 5 تتر است';
            sendMessage($from_id, $msg, reply_markup: $keyboard_account);
            die;
        }

        $balance_request = $db->query("SELECT * FROM `balance_request` WHERE `user_id` = $from_id AND `status` != 'done'")->fetch_object();
        if ($balance_request->status == 'registered') {
            $msg = '🔴 شما قبلا درخواست برداشت ثبت کرده اید';
            sendMessage($from_id, $msg, reply_markup: $keyboard_account);
            die;
        }
        if ($balance_request->status == 'pending') {
            $db->query("DELETE FROM `balance_request` WHERE `user_id` = $from_id AND `status` = 'pending'");
        }
        $msg = "🔶 موجودی حساب شما : {$user->wallet} تتر
        قصد دارید چه مقدار برداشت بکنید ";
        sendMessage($from_id, $msg, reply_markup: $keyboard_back);
        setStep('account_balance');
        die;
    }
    if ($text == '💷 ثبت آدرس ولت') {
        $msg = '🔻 آدرس ولت خودتون رو وارد بکنید 

⚠️ آدرس ولت وارد شده باید برای USDT و بر بستر trc20 باشد';
        sendMessage($from_id, $msg, reply_markup: $keyboard_account);
        setStep('account_wallet');
        die;
    }
    if ($text == '💷 تغییر آدرس ولت') {
        $msg = '🔻 آدرس ولت (جدید) خودتون رو وارد بکنید 

⚠️ آدرس ولت وارد شده باید برای USDT و بر بستر trc20 باشد';

        sendMessage($from_id, $msg, reply_markup: $keyboard_account);
        setStep('account_wallet');
        die;
    }
    sendMessage($from_id, $error_msg, reply_markup: $keyboard_account);
    die;
}
if ($step == 'support') {
    if ($text == '🔙 بازگشت') {
        $msg = 'منوی اصلی';
        sendMessage($from_id, $msg, reply_markup: $keyboard_home);
        setStep('home');
        die;
    }

    $msg = 'پیام شما ارسال شد
تا 24 ساعت بهتون جواب میدیم';
    $msg_admin = "یک پیام پشتیبانی ارسال شده
متن پیام : {$text}
آیدی عددی فرستنده : {$from_id}";


    sendMessage($bot_admins[0], $msg_admin);
    sendMessage($from_id, $msg, reply_markup: $keyboard_home);
    setStep('home');
    die;
}
if ($step == 'account_wallet') {
    if ($text == '🔙 بازگشت') {
        $msg = 'برگشتید به حساب کاربری';
        sendMessage($from_id, $msg, reply_markup: $keyboard_account);
        setStep('account');
        die;
    }
    $sql = "UPDATE `users` SET `wallet_address` = ? WHERE `user_id` = ?";
    $prepare = $db->prepare($sql);
    $prepare->bind_Param("si", $text, $from_id);
    $prepare->execute();
    $prepare->close();
    $msg = '🟢 آدرس ولت شما با موفقیت ثبت شد';
    sendMessage($from_id, $msg, reply_markup: $keyboard_account);
    setStep('account');
    die;
}
if ($step == 'account_balance') {
    if ($text == '🔙 بازگشت') {
        $msg = 'منوی اصلی';
        sendMessage($from_id, $msg, reply_markup: $keyboard_home);
        setStep('home');
        die;
    }
    if (!is_numeric($text)) {
        $msg = '🔴 مقدار وارد شده باید عدد باشد';
        sendMessage($from_id, $msg, reply_markup: $keyboard_back);
        die;
    }
    if ($text < 5.0) {
        $msg = '🔴 حداقل مقدار برداشت 5 تتر است';
        sendMessage($from_id, $msg, reply_markup: $keyboard_back);
        die;
    }
    if ($text > $user->wallet) {
        $msg = '🔴 موجودی حساب شما کافی نیست';
        sendMessage($from_id, $msg, reply_markup: $keyboard_back);
        die;
    }


    $wallet_address = $user->wallet_address;
    $status = 'pending';

    $sql = "INSERT INTO `balance_request` (`user_id`, `balance` , `status`, `wallet_address`) VALUES (?,?,?,?)";
    $prepare = $db->prepare($sql);
    $prepare->bind_Param("idss", $from_id, $text, $status, $wallet_address);
    $prepare->execute();
    $prepare->close();


    $balance_request = $db->query("SELECT * FROM `balance_request` WHERE `user_id` = $from_id AND `status` = 'pending'")->fetch_object();
    $balance = $balance_request->balance;
    $wallet_address = $balance_request->wallet_address;
    $created_at = $balance_request->created_at;

    $msg = "اطلاعات درخواست برداشت :
    

    مبلف درخواستی : {$balance} تتر
    آدرس ولت : {$wallet_address}
    زمان ثبت سفارش : {$created_at}
   🔶 آیا از برداشت وجه خود اطمینان دارید ؟ ";


    sendMessage($from_id, $msg, reply_markup: $keyboard_account_balance_confirm);


    setStep('account_balance_confirm');
    die;
}
if ($step == 'account_balance_confirm') {
    if ($text == '🔙 بازگشت') {
        $msg = 'منوی اصلی';
        sendMessage($from_id, $msg, reply_markup: $keyboard_home);
        setStep('home');
        die;
    }


    if ($text === '✅ تایید نهایی درخواست') {

        $status = 'registered';
        $sql = "UPDATE `balance_request` SET `status` = ? WHERE `user_id` = ? AND `status` = 'pending'";
        $prepare = $db->prepare($sql);
        $prepare->bind_Param("si", $status, $from_id);
        $prepare->execute();
        $prepare->close();

        $balance_request =  $db->query("SELECT * FROM `balance_request` WHERE `user_id` = $from_id AND `status` = 'registered'")->fetch_object();

        $balance = $balance_request->balance;
        $wallet_address = $balance_request->wallet_address;
        $created_at = convertDateToJalali($balance_request->created_at);
        $balance_request_id = $balance_request->id;

        $msg1 = "🔸ثبت درخواست برداشت وجه : 

♦️ آیدی عددی کاربر : {$from_id}
♦️ مبلغ درخواستی : {$balance} تتر
♦️ آدرس ولت : `{$wallet_address}` 
♦️ تاریخ ثبت درخواست : 
{$created_at}

@$bot_username";

        $keyboard_confirm_balance = json_encode(
            [
                'inline_keyboard' => [
                    [['text' => '✅ تایید واریز', 'url' => "https://t.me/{$bot_username}/?start=confirm_{$balance_request_id}"]],
                    [['text' => '❌ لغو واریز', 'url' => "https://t.me/{$bot_username}/?start=cancel_{$balance_request_id}"]],
                ],
                'resize_keyboard' => true,
            ]
        );

        sendMessage($bot_channels_id['request'], $msg1, parse_mode: 'Markdown', reply_markup: $keyboard_confirm_balance);


        $msg2 = "🟢 درخواست برداشت {$balance} تتر با موفقیت ثبت شد";
        sendMessage($from_id, $msg2, reply_markup: $keyboard_home);

        setStep('home');
        die;
    }
    sendMessage($from_id, $error_msg, reply_markup: $keyboard_account_balance_confirm);
    die;
}
