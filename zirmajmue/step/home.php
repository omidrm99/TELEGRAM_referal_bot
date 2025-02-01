<?php

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