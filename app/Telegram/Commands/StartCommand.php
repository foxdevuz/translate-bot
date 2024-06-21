<?php

namespace App\Telegram\Commands;

use App\Models\User;
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
        // Write user to database if not exists
        $user = User::where('user_id', $bot->userId())->first();
        if (!$user) {
            User::create([
                'name'=>$bot->user()->first_name,
                'user_id'=>$bot->userId()
            ]);
        }
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
                ->addRow(
                    InlineKeyboardButton::make(text: "🚀 Avtomatik aniqlash — 🇺🇿 Uz", callback_data: 'auto-uz')
                )
                ->addRow(
                    InlineKeyboardButton::make(text: "🚀 Avtomatik aniqlash — 🇺🇸 En", callback_data: 'auto-en')
                )
                ->addRow(
                    InlineKeyboardButton::make(text: "🚀 Avtomatik aniqlash — 🇷🇺 Ru", callback_data: 'auto-ru')
                )
        );
    }
}
