<?php


if ($text == '🔙 بازگشت') {
    $msg = 'ادمین';
    setStep('admin');
    sendMessage($from_id, $msg, reply_markup: $keyboard_admin);
    die;
}

if ($text == 'افزودن کانال') {
    $msg = '🔻 برای افزودن کانال جدید روش زیر را دنبال کنید :

- در خط اول نام کانال
- در خط دوم آیدی عددی کانال
- در خط سوم لینک کانال

را وارد بکنید

🔶 لینک کانال حتما به صورت https ارسال شود.';
    sendMessage($from_id, $msg, parse_mode: 'Markdown', reply_markup: $keyboard_back);
    setStep('admin_channels_set');
    die;
}
