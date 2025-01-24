<?php
// Database connection
error_reporting(E_ALL);
date_default_timezone_set("Asia/Tehran");





// Configuration
$bot_admins = ['207850708'];
$bot_username = '';
$bot_channels_id = [
    'request' => -0,
];
$support_bot = ['@Omid_rzy'];
$wallet_add = 0.2;

const DATABASE_HOSTNAME = 'localhost';
const DATABASE_USERNAME = 'omidreza_omidreza';
const DATABASE_PASSWORD = 'o=-dzqoJLBI2';
const DATABASE_NAME = 'omidreza_zirmajmue';
$db = new mysqli(DATABASE_HOSTNAME, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
$db->query("SET NAMES 'utf8mb4'");    
$db->set_charset("utf8mb4");







// $bot_name = 'زیر مجموعه گیری';
// $photo_banner = $db->query("SELECT `config_value` FROM `config` WHERE `config_name` = 'photo_banner'")->fetch_column();
// $wallet_add = $db->query("SELECT `config_value` FROM `config` WHERE `config_name` = 'wallet_add'")->fetch_column();
// $wallet_type = $db->query("SELECT `config_value` FROM `config` WHERE `config_name` = 'wallet_type'")->fetch_column();
// $support_bot = $db->query("SELECT `config_value` FROM `config` WHERE `config_name` = 'support_bot'")->fetch_column();
