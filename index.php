<?php

$token = getenv("BOT_TOKEN");

if (!$token) {
    http_response_code(500);
    echo "BOT_TOKEN topilmadi!";
    exit;
}

function telegram($method, $data = [])
{
    global $token;

    $url = "https://api.telegram.org/bot" . $token . "/" . $method;

    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_TIMEOUT => 20
    ]);

    $result = curl_exec($ch);
    curl_close($ch);

    return json_decode($result, true);
}

$update = json_decode(file_get_contents("php://input"), true);

if (!$update) {
    echo "Bot ishlayapti! 🤖";
    exit;
}

/* =========================
   XABAR KELGANDA
========================= */

if (isset($update["message"])) {

    $message = $update["message"];

    $chat_id = $message["chat"]["id"];
    $text = trim($message["text"] ?? "");

    /* START */

    if ($text === "/start") {

        $keyboard = [
            "inline_keyboard" => [
                [
                    [
                        "text" => "📚 Yordam",
                        "callback_data" => "help"
                    ],
                    [
                        "text" => "ℹ️ Bot haqida",
                        "callback_data" => "about"
                    ]
                ],
                [
                    [
                        "text" => "📞 Aloqa",
                        "callback_data" => "contact"
                    ]
                ]
            ]
        ];

        telegram("sendMessage", [
            "chat_id" => $chat_id,
            "text" => "Salom! 👋\n\nMen tayyor Telegram botman. 🤖\n\nQuyidagi tugmalardan birini tanlang:",
            "reply_markup" => json_encode($keyboard)
        ]);
    }

    /* HELP */

    elseif ($text === "/help") {

        telegram("sendMessage", [
            "chat_id" => $chat_id,
            "text" => "🆘 Yordam\n\n/start — Botni boshlash\n/help — Yordam\n/about — Bot haqida\n\nSavolingizni oddiy xabar sifatida yuborishingiz mumkin."
        ]);
    }

    /* ABOUT */

    elseif ($text === "/about") {

        telegram("sendMessage", [
            "chat_id" => $chat_id,
            "text" => "🤖 Bu PHP orqali yaratilgan Telegram bot.\n\n⚡ Render serverida ishlaydi."
        ]);
    }

    /* ODDIY XABAR */

    else {

        telegram("sendMessage", [
            "chat_id" => $chat_id,
            "text" => "📩 Xabaringiz qabul qilindi:\n\n" . $text
        ]);
    }
}


/* =========================
   INLINE TUGMA BOSILGANDA
========================= */

if (isset($update["callback_query"])) {

    $callback = $update["callback_query"];

    $callback_id = $callback["id"];
    $chat_id = $callback["message"]["chat"]["id"];
    $message_id = $callback["message"]["message_id"];
    $data = $callback["data"];

    // Loadingni olib tashlash
    telegram("answerCallbackQuery", [
        "callback_query_id" => $callback_id
    ]);

    /* HELP */

    if ($data === "help") {

        telegram("editMessageText", [
            "chat_id" => $chat_id,
            "message_id" => $message_id,
            "text" => "🆘 Yordam\n\n/start — Botni boshlash\n/help — Yordam\n/about — Bot haqida\n\nIstalgan xabaringizni yuborishingiz mumkin."
        ]);
    }

    /* ABOUT */

    elseif ($data === "about") {

        telegram("editMessageText", [
            "chat_id" => $chat_id,
            "message_id" => $message_id,
            "text" => "ℹ️ Bot haqida\n\nBu PHP + Telegram Bot API orqali yaratilgan bot.\n\n🚀 Server: Render"
        ]);
    }

    /* CONTACT */

    elseif ($data === "contact") {

        telegram("editMessageText", [
            "chat_id" => $chat_id,
            "message_id" => $message_id,
            "text" => "📞 Aloqa\n\nAdmin bilan bog‘lanish uchun shu yerga o‘z username'ingizni yozishingiz mumkin."
        ]);
    }
}

echo "OK";
