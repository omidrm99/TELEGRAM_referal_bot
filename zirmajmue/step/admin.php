<?php
if ($text == '🔙 بازگشت') {
    $msg = 'منوی اصلی';
    setStep('home');
    sendMessage($from_id, $msg, reply_markup: $keyboard_home);
    die;
}