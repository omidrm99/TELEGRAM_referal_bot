<?php


if ($text == '🔙 بازگشت') {
    $msg = 'منوی اصلی';
    setStep('home');
    sendMessage($from_id, $msg, reply_markup: $keyboard_home);
    die;
}

if ($text == 'ارسال پیام') {

    $sql = "DELETE FROM `send` WHERE `admin_id` = ? AND `status` = 0";
    $prepare = $db->prepare($sql);
    $prepare->bind_Param("i", $from_id);
    $prepare->execute();
    $prepare->close();

    $msg = 'آیدی عددی کاربر مورد نظر رو وارد بکنید';
    sendMessage($from_id, $msg, reply_markup: $keyboard_back);
    setStep('admin_sendmessage');
    die;
}



if ($text == 'اطلاعات کاربر') {
    $msg = 'آیدی عددی کاربر مورد نظر رو وارد بکنید';
    sendMessage($from_id, $msg, reply_markup: $keyboard_back);
    setStep('admin_userinfo');
    die;
}


if ($text == 'آمار ربات') {
    require "admin_botinfo.php";
    die;
}

if ($text == 'تنظیم کانال ها') {
    $msg = 'بخش مدیریت کانال ها';
    sendMessage($from_id, $msg, reply_markup: $keyboard_admin_channel);
    setStep('admin_channels');
    die;
}

sendMessage($from_id, $error_msg);
die;
