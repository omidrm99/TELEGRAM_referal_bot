<?php



// -1002332898108
$checkjoin = bot(
    'getChatMember',
    [
        'chat_id' =>   -1002332898108,
        'user_id' => $from_id
    ]
);


function decodeJson($jsonString)
{
    // Decode the JSON string
    $decodedArray = json_decode($jsonString, true);
    // Access the 'status' field
    $status = $decodedArray['result']['status'];
    return $status;
}

$checkjoin1 = decodeJson($checkjoin);

if ($checkjoin1 == 'left') {
    debug('inside if');
    die;
    $msg = 'برای ادامه در کانال های زیر عضو شوید';
    sendMessage($from_id, $msg);
}
debug('end of code');
die;
