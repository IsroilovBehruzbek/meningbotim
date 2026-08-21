<?php
$token = getenv("8897219426:AAHN6MY9nu9tiesHxkZ9p0cgpqxV9QbBqqw");

$update = json_decode(file_get_contents("php://input"), true);

if (isset($update["message"])) {

    $chat_id = $update["message"]["chat"]["id"];
    $text = $update["message"]["text"] ?? "";

    if ($text == "/start") {

        $url = "https://api.telegram.org/bot" . $token . "/sendMessage";

        $data = [
            "chat_id" => $chat_id,
            "text" => "Salom! 👋 Bot ishlayapti!"
        ];

        file_get_contents($url . "?" . http_build_query($data));
    }
}
