<?php

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