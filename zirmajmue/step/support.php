<?php

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
آیدی عددی فرستنده : `{$from_id}`";


sendMessage($bot_admins[0], $msg_admin,parse_mode:'Markdown');
sendMessage($from_id, $msg, reply_markup: $keyboard_home);
setStep('home');
die;