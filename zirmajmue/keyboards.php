<?php

$keyboard_home = json_encode(
    [
        'keyboard' => [
            [['text' => '⭐️ زیرمجموعه گیری']],
            [['text' => '💬 پشتیبانی'], ['text' => '🔐 حساب کاربری']],
            [['text' => '💡 راهنمای ربات'], ['text' => '⁉️ سوالات متداول']]
        ],
        'resize_keyboard' => true,
        'is_persistent' => true
    ]
);


$keyboard_account = json_encode(
    [
        'keyboard' => [
            [['text' => '💳 برداشت وجه'], ['text' => '💷 ثبت آدرس ولت']],
            [['text' => '🔙 بازگشت']],
        ],
        'resize_keyboard' => true,
        'is_persistent' => true
    ]
);
$keyboard_account_wallet = json_encode(
    [
        'keyboard' => [
            [['text' => '💳 برداشت وجه'], ['text' => '💷 تغییر آدرس ولت']],
            [['text' => '🔙 بازگشت']],
        ],
        'resize_keyboard' => true,
        'is_persistent' => true
    ]
);
$keyboard_account_balance_confirm = json_encode(
    [
        'keyboard' => [
            [['text' => '✅ تایید نهایی درخواست']],
            [['text' => '🔙 بازگشت']],
        ],
        'resize_keyboard' => true,
        'is_persistent' => true
    ]
);

$keyboard_back = json_encode(
    [
        'keyboard' => [
            [['text' => '🔙 بازگشت']],
        ],
        'resize_keyboard' => true,
        'is_persistent' => true
    ]
);
$keyboard_admin = json_encode(
    [
        'keyboard' => [
            [['text' => 'رفع مسدود'], ['text' => 'مسدود کردن']],
            [['text' => 'ارسال همگانی'], ['text' => 'ارسال پیام']],
            [['text' => 'آمار ربات'], ['text' => 'اطلاعات کاربر']],
            [['text' => 'تنظیم کانال ها']],
            [['text' => '🔙 بازگشت']],
        ],
        'resize_keyboard' => true,
        'is_persistent' => true
    ]
);
$keyboard_admin_channel = json_encode(
    [
        'keyboard' => [
            [['text' => 'حذف کانال'], ['text' => 'افزودن کانال']],
            [['text' => 'لیست کانال ها']],
            [['text' => '🔙 بازگشت']],
        ],
        'resize_keyboard' => true,
        'is_persistent' => true
    ]
);

