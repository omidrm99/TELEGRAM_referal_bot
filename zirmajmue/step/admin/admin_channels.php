<?php


if ($text == '🔙 بازگشت') {
    $msg = 'ادمین';
    setStep('admin');
    sendMessage($from_id, $msg, reply_markup: $keyboard_admin);
    die;
}

if ($text == 'افزودن کانال') {
    $msg = '🔻 برای افزودن کانال جدید روش زیر را دنبال کنید :
- در خط اول عبارت افزودن کانال جدید
- در خط دوم نام کانال
- در خط سوم آیدی عددی کانال
- در خط چهارم لینک کانال

را وارد بکنید

🔶 لینک کانال حتما به صورت https ارسال شود.';
    sendMessage($from_id, $msg,reply_markup:$keyboard_back);
    die;
} 