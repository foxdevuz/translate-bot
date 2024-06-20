<?php

namespace App\Telegram\Conversations;

use App\Services\TranslationService;
use GuzzleHttp\Exception\GuzzleException;
use Psr\SimpleCache\InvalidArgumentException;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;

class UzbekToRussianTranslate extends Conversation
{
    /**
     * @throws InvalidArgumentException
     */
    public function start(Nutgram $bot) : void
    {
        $bot->sendMessage("O'zbek tilidan Rus tiliga tarjima qilish uchun matn kiriting:");
        $this->next('secondStep');
    }

    /**
     * @throws InvalidArgumentException
     * @throws GuzzleException
     */
    public function secondStep(Nutgram $bot) : void
    {
        $text = $bot->message()->text;
        $translation = TranslationService::translate(
            source: 'ru',
            target: 'uz',
            text: $text);
        if ($translation) {
            $bot->sendMessage($translation);
        } else {
            $bot->sendMessage("Tarjima qilinmadi!, Iltimos qaytadan urunib ko'ring!");
        }
        $this->end();
    }
}
