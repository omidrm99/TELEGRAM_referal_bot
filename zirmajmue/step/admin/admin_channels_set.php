<?php


if ($text == '🔙 بازگشت') {
    $msg = 'ادمین';
    setStep('admin');
    sendMessage($from_id, $msg, reply_markup: $keyboard_admin);
    die;
}

$data = explode("\n",$text);

$db->query("INSERT INTO `channel` (`channel_name`,`channel_id`,`channel_link`) VALUES ('{$data[0]}','{$data[1]}','{$data[2]}')");

$msg = 'کانال با موفقعیت اضاقه شد';
sendMessage($from_id,$msg);
die;