<?php

namespace App\Telegram\Conversations;

use App\Models\User;
use App\Services\TranslationService;
use GuzzleHttp\Exception\GuzzleException;
use Psr\SimpleCache\InvalidArgumentException;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class TranslateConversation extends Conversation
{
    /**
     * @param Nutgram $bot
     * @return void
     * @throws InvalidArgumentException
     */
    public function start(Nutgram $bot) : void
    {
        $user = User::where('user_id', $bot->userId())->first();
        $data = $bot->callbackQuery()->data;
        $user->update([
            'language' => $data
        ]);
        $user->save();

        $bot->deleteMessage(
            chat_id: $bot->callbackQuery()->message->chat->id,
            message_id: $bot->callbackQuery()->message->message_id
        );

        $bot->sendMessage(
            text: "Tarjima qilish uchun matn yuboring!",
            reply_markup: InlineKeyboardMarkup::make()
                ->addRow(
                    InlineKeyboardButton::make(text: "🔄 Tilni almashtirish", callback_data: 'change-lang')
                )
        );
        $this->next('secondStep');
    }

    /**
     * @throws GuzzleException
     */
    public function secondStep(Nutgram $bot) : void
    {
        // get language
        $user = User::where('user_id', $bot->userId())->first();
        $extract_lang = explode('-', $user->language);
        $source = $extract_lang[0];
        $target = $extract_lang[1];

        $text = $bot->message()->text;
        $translation = TranslationService::translate(
            source: $source,
            target: $target,
            text: $text);
        if ($translation) {
            $bot->sendMessage(
                text: "Tarjima qilingan matn:\n\n" . $translation,
                reply_markup: InlineKeyboardMarkup::make()
                        ->addRow(
                            InlineKeyboardButton::make(text: "🔄 Tilni almashtirish", callback_data: 'change-lang')
                        )
            );
        } else {
            $bot->sendMessage(
                text: "Tarjima qilinmadi!, Iltimos qaytadan urunib ko'ring!",
                reply_markup: InlineKeyboardMarkup::make()
                        ->addRow(
                            InlineKeyboardButton::make(text: "🔄 Tilni almashtirish", callback_data: 'change-lang')
                        )
            );
        }
    }

}
