<?php

const API = '7536953878:AAGm-lCk8BN6Ksc8FyvjQbh9zD21LYImtdw';
include("DBconfig.php");
include("jdf.php");


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
    die;
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
    $min = 6;
    $max = 10;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $length = random_int($min, $max);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $index = random_int(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }
    return $randomString;
}