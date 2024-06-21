<?php

namespace App\Telegram\Conversations;

use App\Models\User;
use Psr\SimpleCache\InvalidArgumentException;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class ChangeLanguage extends Conversation
{
    /**
     * @throws InvalidArgumentException
     */
    public function start(Nutgram $bot) : void
    {
        // set user's language to null
        $user = User::where('user_id', $bot->userId())->first();
        $user->update([
            'language' => null
        ]);
        $user->save();
        // let the user choose the language
        $bot->sendMessage(
            text:"Tarjima qilish uchun tilni tanlang",
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
        $this->end();
    }
}
