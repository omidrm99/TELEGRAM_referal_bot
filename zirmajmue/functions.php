<?php

const API = '7536953878:AAGm-lCk8BN6Ksc8FyvjQbh9zD21LYImtdw';
require "DBconfig.php";
require "jdf.php";


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
function sendMessage(int|string $chat_id, string $text, String $parse_mode = null, int $message_thread_id = null, array $entities = null, mixed $link_preview_options = null, bool $disable_notification = null, bool $protect_content = null, bool $allow_paid_broadcast = null, string $message_effect_id = null, mixed $reply_parameters = null, mixed $reply_markup = null): bool|string
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
function convertToEnglishNumbers(string $text): string
{
    $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    $arabic = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١', '٠'];
    $english = range(0, 9);
    $converted_persian_numbers = str_replace($persian, $english, $text);
    $converted_arabic_numbers = str_replace($arabic, $english, $converted_persian_numbers);
    return $converted_arabic_numbers;
}
function convertDateToJalali(string $date, $format = "Y-m-d H:i:s"): string
{
    $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $date);
    if (!$dateTime || $dateTime->format('Y-m-d H:i:s') !== $date) {
        throw new InvalidArgumentException("Invalid date format. Expected 'Y-m-d H:i:s'.");
    }

    $timestamp = $dateTime->getTimestamp();
    return convertToEnglishNumbers(jdate(format: $format, timestamp: $timestamp, time_zone: 'Asia/Tehran'));
}
function generateRandomString(): string
{
    $min = 10;
    $max = 15;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $length = random_int($min, $max);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $index = random_int(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }
    return $randomString;
}
function sendPhoto(int $chat_id, string $photo, string $caption = null, string $parse_mode = null, array $caption_entities = null, bool $has_spoiler = null, bool $disable_notification = null, bool $protect_content = true, int $reply_to_message_id = null, bool $allow_sending_without_reply = null, $reply_markup = null): bool|string
{
    $params = [
        'chat_id' => $chat_id,
        'photo' => $photo
    ];

    foreach (
        [
            'caption' => $caption,
            'parse_mode' => $parse_mode,
            'caption_entities' => $caption_entities,
            'has_spoiler' => $has_spoiler,
            'disable_notification' => $disable_notification,
            'protect_content' => $protect_content,
            'reply_to_message_id' => $reply_to_message_id,
            'allow_sending_without_reply' => $allow_sending_without_reply,
            'reply_markup' => $reply_markup
        ] as $key => $value
    ) {
        if ($value !== null) {
            $params[$key] = $value;
        }
    }

    return bot('sendPhoto', $params);
}
function editMessageText(int|string $chat_id = null, int $message_id = null, string $inline_message_id = null, string $text = null, string $parse_mode = null, array $entities = null, bool $disable_web_page_preview = null, $reply_markup = null): void
{
    $params = [
        'chat_id' => $chat_id
    ];

    foreach (
        [
            'message_id' => $message_id,
            'inline_message_id' => $inline_message_id,
            'text' => $text,
            'parse_mode' => $parse_mode,
            'entities' => $entities,
            'disable_web_page_preview' => $disable_web_page_preview,
            'reply_markup' => $reply_markup
        ] as $key => $value
    ) {
        if ($value !== null) {
            $params[$key] = $value;
        }
    }

    bot('editMessageText', $params);
}
function editMessageCaption(int|string $chat_id = null, int $message_id = null, string $inline_message_id = null, string $caption = null, string $parse_mode = null, array $caption_entities = null, $reply_markup = null): void
{
    $params = [
        'chat_id' => $chat_id
    ];

    foreach (
        [
            'message_id' => $message_id,
            'inline_message_id' => $inline_message_id,
            'caption' => $caption,
            'parse_mode' => $parse_mode,
            'caption_entities' => $caption_entities,
            'reply_markup' => $reply_markup
        ] as $key => $value
    ) {
        if ($value !== null) {
            $params[$key] = $value;
        }
    }

    bot('editMessageCaption', $params);
}
function answerCallbackQuery(string $callback_query_id = null, string $text = null, bool $show_alert = null, string $url = null, int $cache_time = null): void
{
    $params = [
        'callback_query_id' => $callback_query_id
    ];

    foreach (
        [
            'text' => $text,
            'show_alert' => $show_alert,
            'url' => $url,
            'cache_time' => $cache_time
        ] as $key => $value
    ) {
        if ($value !== null) {
            $params[$key] = $value;
        }
    }

    bot('answerCallbackQuery', $params);
}
function setStep(string $step): bool|mysqli_result
{
    global $db;
    global $from_id;
    return $db->query("UPDATE `users` SET `step` = '$step' WHERE `user_id`= '$from_id'");
}
function getStep(int $user_id)
{
    global $db;
    return $db->query("SELECT `step` FROM `users` WHERE `user_id`= '$user_id'")->fetch_object()->step;
}

function deleteMessage($chat_id, $message_id): void
{
    $params = [
        'chat_id' => $chat_id,
        'message_id' => $message_id
    ];
    bot('deleteMessage', $params);
}
function getChatMember(int $chat_id): string|bool
{

    $data = bot(
        'getChatMember',
        [
            'chat_id' => $chat_id,
            'user_id' => $GLOBALS['from_id']
        ]
    );
    if ($data['result']['status']) {
        return $data['result']['status'];
    } else {
        return true;
    }
}
