<?php


if ($text == '🔙 بازگشت') {
    $msg = 'ادمین';
    setStep('admin');
    sendMessage($from_id, $msg, reply_markup: $keyboard_admin);
    die;
}

$data = explode("\n",$text);
debug($data);
die;