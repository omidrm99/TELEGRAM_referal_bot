<?php

$chat_id = -1002332898108;

$checkjoin = bot(
    'getChatMember',
[
    'chat_id' => $chat_id,
    'user_id' => $from_id
    ]
);

$status = json_decode($checkjoin, true)['result']['status'];

if ($status == 'left') {
    $msg = 'برای ادامه در کانال های زیر عضو شوید';
    sendMessage($from_id, $msg);
}

debug('end of code');
die;
