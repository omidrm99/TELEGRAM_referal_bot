<?php


if ($text == '🔙 بازگشت') {
    $msg = 'منوی اصلی';
    setStep('admin');
    sendMessage($from_id, $msg, reply_markup: $keyboard_admin);
    die;
}

if (!is_numeric($text)) {
    $msg = '🔴 مقدار وارد شده باید عدد باشد';
    sendMessage($from_id, $msg, reply_markup: $keyboard_back);
    die;
}
$status = 0;
$sql = "INSERT INTO `omidreza_zirmajmue`.`send` (`admin_id`, `user_id` , `status`) VALUES (?,?,?)";
$prepare = $db->prepare($sql);
$prepare->bind_param('iii', $from_id, $text, $status);
$prepare->execute();
$prepare->close();


$msg = 'متن پیام خود را ارسال بکنید';
sendMessage($from_id, $msg, reply_markup: $keyboard_back);
setStep('admin_sendmessage2');
die;


// $admin_data = explode("\n", $text);
// sendMessage($admin_data[0], $admin_data[1]);
// $msg = 'پیام با شمل ارسال شد';
// sendMessage($from_id, $msg, reply_markup: $keyboard_admin);
// die;