<?php

namespace App\Traits;

use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;

trait ReplyKeyboard
{
    /**
     * @return ReplyKeyboardMarkup
     */
    protected function cancelSendAds() : ReplyKeyboardMarkup
    {
         return ReplyKeyboardMarkup::make(
             resize_keyboard: true,
         )->addRow(
             KeyboardButton::make("❌ Bekor qilish")
         );
    }

    protected function confirmSendAdsKeyboard() : ReplyKeyboardMarkup
    {
        return ReplyKeyboardMarkup::make(
            resize_keyboard: true,
        )->addRow(
            KeyboardButton::make("✅ Ha"),
            KeyboardButton::make("❌ Yo'q")
        );
    }
}
