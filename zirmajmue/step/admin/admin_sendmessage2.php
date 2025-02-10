<?php



if ($text == '🔙 بازگشت') {
    $msg = 'منوی اصلی';
    setStep('admin');
    sendMessage($from_id, $msg, reply_markup: $keyboard_admin);
    die;
}



$send_user_id = $db->query("SELECT * FROM `send` WHERE `admin_id` = $from_id AND `status` = 0")->fetch_object()->user_id;

sendMessage($send_user_id, $text);

$status = 1;
$sql = "UPDATE `send` SET `text` = ? WHERE `admin_id` = ? AND `status` = 0";
$prepare = $db->prepare($sql);
$prepare->bind_Param("si", $text, $from_id);
$prepare->execute();
$prepare->close();

$sql = "UPDATE `send` SET `status` = ? WHERE `admin_id` = ? AND `status` = 0 AND `text` = ?";
$prepare = $db->prepare($sql);
$prepare->bind_Param("iis", $status, $from_id, $text);
$prepare->execute();
$prepare->close();

$msg = 'پیام شما با موفعیت ارسال شد.';
sendMessage($from_id, $msg,reply_markup:$keyboard_admin);
setStep('admin');

die;
// $sql = "UPDATE `send` SET `text` = ?, `status` = ? WHERE `admin_id` = ? AND `status` = 0";
// $prepare = $db->prepare($sql);
// $prepare->bind_Param("sii", $text, $status, $from_id);
// $prepare->execute();
// $prepare->close();
