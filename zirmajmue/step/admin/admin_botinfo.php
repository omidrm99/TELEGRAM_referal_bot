<?php


$user_numbers = $db->query("SELECT `user_id` FROM `users`")->num_rows;
$balance_request_done = $db->query("SELECT `id` FROM `balance_request` WHERE `status` = 'done'")->num_rows;
$balance_request_registered = $db->query("SELECT `id` FROM `balance_request` WHERE `status` = 'registered'")->num_rows;

$msg = "
تعداکل کاربران : {$user_numbers}
واریز های انجام شده  : {$balance_request_done}
واریز های در دست انجام  : {$balance_request_registered}
";


sendMessage($from_id, $msg, reply_markup: $keyboard_admin);
die;
