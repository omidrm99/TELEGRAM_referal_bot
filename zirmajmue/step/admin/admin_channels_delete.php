<?php


if ($text == '🔙 بازگشت') {
    $msg = 'ادمین';
    setStep('admin');
    sendMessage($from_id, $msg, reply_markup: $keyboard_admin);
    die;
}


if (!is_numeric($text)) {
    $msg = 'آیدی عددی باید عدد باشد';
    sendMessage($from_id, $msg);
    die;
}
$db->query("DELETE FROM `channel` WHERE `chanel_id` = $text");

$msg = 'کانال حذف شد';
sendMessage($from_id, $msg);

die;
