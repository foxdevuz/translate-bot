<?php

namespace App\Traits;

use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

trait InlineKeyboards
{
    /**
     * @return InlineKeyboardMarkup
     */
    protected function translateLanKeyboard() : InlineKeyboardMarkup
    {
        return InlineKeyboardMarkup::make()
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
            );
    }
    /**
     * @return InlineKeyboardMarkup
     */
    protected function changeLang() : InlineKeyboardMarkup
    {
        return InlineKeyboardMarkup::make()
            ->addRow(
                InlineKeyboardButton::make(text: "🔄 Tilni almashtirish", callback_data: 'change-lang')
            );
    }

    /**
     * @return InlineKeyboardMarkup
     */
    protected function adminHomeKeyboard() : InlineKeyboardMarkup
    {
        return InlineKeyboardMarkup::make()
            ->addRow(
                InlineKeyboardButton::make(text: "📊 Statistika", callback_data: 'stats'), InlineKeyboardButton::make(text: "📝 Xabar yuborish", callback_data: 'send-message')
            );
    }
}
