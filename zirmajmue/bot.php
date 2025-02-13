<?php

require "functions.php";
require "config.php";
// require "jdf.php";
require "keyboards.php";

$input = file_get_contents("php://input");
$update = json_decode($input, true);

require "inc/update.php";

if ($chat_type != 'private') {
    die;
}

$error_msg = '❌ دستور مورد نظر یافت نشد 
لطفا از کیبور استفاده بکنید';

require "view/start.php";

$step = getStep($from_id);

if ($step == 'home') {
    require "step/home.php";
}
if ($step == 'account') {
    require "step/account.php";
}
if ($step == 'support') {
    require "step/support.php";
}
if ($step == 'account_wallet') {
    if ($text == '🔙 بازگشت') {
        $msg = 'برگشتید به حساب کاربری';
        sendMessage($from_id, $msg, reply_markup: $keyboard_account);
        setStep('account');
        die;
    }
    $sql = "UPDATE `users` SET `wallet_address` = ? WHERE `user_id` = ?";
    $prepare = $db->prepare($sql);
    $prepare->bind_Param("si", $text, $from_id);
    $prepare->execute();
    $prepare->close();
    $msg = '🟢 آدرس ولت شما با موفقیت ثبت شد';
    sendMessage($from_id, $msg, reply_markup: $keyboard_account);
    setStep('account');
    die;
}
if ($step == 'account_balance') {
    if ($text == '🔙 بازگشت') {
        $msg = 'منوی اصلی';
        sendMessage($from_id, $msg, reply_markup: $keyboard_home);
        setStep('home');
        die;
    }
    if (!is_numeric($text)) {
        $msg = '🔴 مقدار وارد شده باید عدد باشد';
        sendMessage($from_id, $msg, reply_markup: $keyboard_back);
        die;
    }
    if ($text < 5.0) {
        $msg = '🔴 حداقل مقدار برداشت 5 تتر است';
        sendMessage($from_id, $msg, reply_markup: $keyboard_back);
        die;
    }
    if ($text > $user->wallet) {
        $msg = '🔴 موجودی حساب شما کافی نیست';
        sendMessage($from_id, $msg, reply_markup: $keyboard_back);
        die;
    }


    $wallet_address = $user->wallet_address;
    $status = 'pending';

    $sql = "INSERT INTO `balance_request` (`user_id`, `balance` , `status`, `wallet_address`) VALUES (?,?,?,?)";
    $prepare = $db->prepare($sql);
    $prepare->bind_Param("idss", $from_id, $text, $status, $wallet_address);
    $prepare->execute();
    $prepare->close();


    $balance_request = $db->query("SELECT * FROM `balance_request` WHERE `user_id` = $from_id AND `status` = 'pending'")->fetch_object();
    $balance = $balance_request->balance;
    $wallet_address = $balance_request->wallet_address;
    $created_at = $balance_request->created_at;

    $msg = "اطلاعات درخواست برداشت :
    

    مبلف درخواستی : {$balance} تتر
    آدرس ولت : {$wallet_address}
    زمان ثبت سفارش : {$created_at}
   🔶 آیا از برداشت وجه خود اطمینان دارید ؟ ";


    sendMessage($from_id, $msg, reply_markup: $keyboard_account_balance_confirm);


    setStep('account_balance_confirm');
    die;
}
if ($step == 'account_balance_confirm') {
    if ($text == '🔙 بازگشت') {
        $msg = 'منوی اصلی';
        sendMessage($from_id, $msg, reply_markup: $keyboard_home);
        setStep('home');
        die;
    }


    if ($text === '✅ تایید نهایی درخواست') {

        $status = 'registered';
        $sql = "UPDATE `balance_request` SET `status` = ? WHERE `user_id` = ? AND `status` = 'pending'";
        $prepare = $db->prepare($sql);
        $prepare->bind_Param("si", $status, $from_id);
        $prepare->execute();
        $prepare->close();

        $balance_request =  $db->query("SELECT * FROM `balance_request` WHERE `user_id` = $from_id AND `status` = 'registered'")->fetch_object();

        $balance = $balance_request->balance;
        $wallet_address = $balance_request->wallet_address;
        $created_at = convertDateToJalali($balance_request->created_at);
        $balance_request_id = $balance_request->id;

        $msg1 = "🔸ثبت درخواست برداشت وجه : 

♦️ آیدی عددی کاربر : {$from_id}
♦️ مبلغ درخواستی : {$balance} تتر
♦️ آدرس ولت : `{$wallet_address}` 
♦️ تاریخ ثبت درخواست : 
{$created_at}

@$bot_username";

        $keyboard_confirm_balance = json_encode(
            [
                'inline_keyboard' => [
                    [['text' => '✅ تایید واریز', 'url' => "https://t.me/{$bot_username}/?start=confirm_{$balance_request_id}"]],
                    [['text' => '❌ لغو واریز', 'url' => "https://t.me/{$bot_username}/?start=cancel_{$balance_request_id}"]],
                ],
                'resize_keyboard' => true,
            ]
        );

        sendMessage($bot_channels_id['request'], $msg1, parse_mode: 'Markdown', reply_markup: $keyboard_confirm_balance);


        $msg2 = "🟢 درخواست برداشت {$balance} تتر با موفقیت ثبت شد";
        sendMessage($from_id, $msg2, reply_markup: $keyboard_home);

        setStep('home');
        die;
    }
    sendMessage($from_id, $error_msg, reply_markup: $keyboard_account_balance_confirm);
    die;
}
if ($step == 'admin') {
    require "step/admin/admin.php";
}
if ($step == 'admin_sendmessage') {
    require "step/admin/admin_sendmessage.php";
}
if ($step == 'admin_sendmessage2') {
    require "step/admin/admin_sendmessage2.php";
}
if ($step == 'admin_userinfo') {
    require "step/admin/admin_userinfo.php";
}
if ($step == 'admin_channels') {
    require "step/admin/admin_channels.php";
}
if ($step == 'admin_channels_set') {
    require "step/admin/admin_channels_set.php";
}
if ($step == 'admin_channels_delete') {
    require "step/admin/admin_channels_delete.php";
}