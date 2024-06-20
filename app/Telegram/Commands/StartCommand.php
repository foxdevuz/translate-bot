<?php

namespace App\Telegram\Commands;

use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Handlers\Type\Command;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use function Laravel\Prompts\text;

class StartCommand extends Command
{
    protected string $command = 'command';

    protected ?string $description = 'A lovely description.';

    public function handle(Nutgram $bot): void
    {
        $bot->sendMessage(
            text:"Salom botimizga xush kelibsiz! Tarjima qilish uchun tilni tanlang",
            reply_markup: InlineKeyboardMarkup::make()
                    ->addRow(
                        InlineKeyboardButton::make(text: "🇺🇿 O'zbek — 🇷🇺 Rus", callback_data: "uz-ru"), InlineKeyboardButton::make(text: "🇷🇺 Rus — 🇺🇿 O'zbek", callback_data: "ru-uz")
                    )
                    ->addRow(
                        InlineKeyboardButton::make(text: "🇺🇿 O'zbek — 🇺🇸 Ingliz", callback_data: "uz-en"), InlineKeyboardButton::make(text: "🇺🇸 Ingliz — 🇺🇿 O'zbek", callback_data: "en-uz")
                    )
                    ->addRow(
                        InlineKeyboardButton::make(text: "🇷🇺 Rus — 🇺🇸 Ingliz", callback_data: "ru-en"), InlineKeyboardButton::make(text: "🇺🇸 Ingliz — 🇷🇺 Rus", callback_data: "en-ru")
                    )
        );
    }
}
