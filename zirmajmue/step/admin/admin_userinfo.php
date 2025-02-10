<?php

if ($text == '🔙 بازگشت') {
    $msg = 'بخش مدیریت';
    setStep('admin');
    sendMessage($from_id, $msg, reply_markup: $keyboard_admin);
    die;
}

if (!is_numeric($text)) {
    $msg = '🔴 مقدار وارد شده باید عدد باشد';
    sendMessage($from_id, $msg, reply_markup: $keyboard_back);
    die;
}


$sql = $db->prepare("SELECT * FROM `users` WHERE `user_id` = ?");
$sql->bind_param("s", $text);
$sql->execute();
$user_info = $sql->get_result()->fetch_object();
$sql->close();


if (!isset($user_info)) {
    $msg = 'کاربر یافت نشد';
    sendMessage($from_id, $msg, reply_markup: $keyboard_back);
    die;
}

$created_at = convertDateToJalali($user_info->created_at, 'Y-m-d');



$msg = "
آیدی عددی کاربر : {$user_info->user_id}
تعداد زیرمحموعه دعوت کرده : {$user_info->referals}
لینک زیرمحموعه گیری : https://t.me/{$bot_username}/?start=inv_{$user_info->referal_code}
مقدار موجودی : {$user_info->wallet}$
آدرس ولت : {$user_info->wallet_address}
step : {$user_info->step}
تاریخ عضویت : {$created_at}
";

sendMessage($from_id, $msg, reply_markup: $keyboard_admin);
setStep('admin');
die();
