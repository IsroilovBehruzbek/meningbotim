<?php

$token = getenv("BOT_TOKEN");

if (!$token) {
    die("BOT_TOKEN TOPILMADI");
}

$url = "https://api.telegram.org/bot" . $token . "/getMe";

$result = file_get_contents($url);

header("Content-Type: application/json");
echo $result;
