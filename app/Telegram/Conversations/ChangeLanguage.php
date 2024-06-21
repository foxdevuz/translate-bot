<?php

namespace App\Telegram\Conversations;

use App\Models\User;
use App\Traits\InlineKeyboards;
use Psr\SimpleCache\InvalidArgumentException;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class ChangeLanguage extends Conversation
{
    use InlineKeyboards;
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
            reply_markup: $this->translateLanKeyboard()
        );
        $this->end();
    }
}
