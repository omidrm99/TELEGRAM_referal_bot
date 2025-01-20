<?php

const API = '7536953878:AAGm-lCk8BN6Ksc8FyvjQbh9zD21LYImtdw';
include("DBconfig.php");


function bot(string $method, array $params): bool|string
{
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://api.telegram.org/bot' . API . '/' . $method,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $params
    ]);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
function sendMessage(int|string $chat_id, string $text, String $parse_mode = null, int $message_thread_id = null, array $entities = null, mixed $link_preview_options = null, bool $disable_notification = null, bool $protect_content = null, bool $allow_paid_broadcast = null, string $message_effect_id = null, mixed $reply_parameters = null, mixed $reply_markup = null)
{
    $params = [
        'chat_id' => $chat_id,
        'text' => $text,
    ];

    foreach (
        [
            'parse_mode' => $parse_mode,
            'message_thread_id' => $message_thread_id,
            'entities' => $entities,
            'link_preview_options' => $link_preview_options,
            'disable_notification' => $disable_notification,
            'protect_content' => $protect_content,
            'allow_paid_broadcast' => $allow_paid_broadcast,
            'message_effect_id' => $message_effect_id,
            'reply_markup' => $reply_markup
        ] as $key => $value
    ) {
        if ($value !== null) {
            $params[$key] = $value;
        }
    }

    return bot('sendMessage', $params);
}
function debug(mixed $data): void
{
    $admin = 207850708;
    $printData = print_r($data, true);
    sendMessage($admin, $printData);
}