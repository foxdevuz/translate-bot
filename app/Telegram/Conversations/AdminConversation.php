<?php

namespace App\Telegram\Conversations;

use App\Traits\AdminHelpers;
use App\Traits\InlineKeyboards;
use App\Traits\ReplyKeyboard;
use Psr\SimpleCache\InvalidArgumentException;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;

class AdminConversation extends Conversation
{
    use ReplyKeyboard, AdminHelpers, InlineKeyboards;
    /**
     * @throws InvalidArgumentException
     */
    public function start(Nutgram $bot) : void
    {
        $bot->sendMessage("Salom Admin siz foydalanuvchilarga qanday xabar yuborishni hohlaysiz?\n\nIltimos hamma matn, rasm, video, va h.klarni bitta xabar sifatida yuboring!");
        $this->next('secondStep');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function secondStep(Nutgram $bot) : void
    {
        $text = $bot->message()->text;
        if ($text === '❌ Bekor qilish') {
            $bot->sendMessage(
                text: "Xabar yuborish bekor qilindi!",
            );
            $bot->sendMessage(
                text: "Admin panel",
                reply_markup: $this->adminHomeKeyboard()
            );
            $this->end();
        }
        $this->updateAdminSendAds($bot->message()->from->id, $bot->message()->message_id);
        $bot->copyMessages(
            chat_id: $bot->userId(),
            from_chat_id: $bot->message()->chat->id,
            message_ids: [$bot->messageId()]
        );

        $bot->sendMessage(
            text: "Xabar tayyor, yuborishni tasdiqlaysizmi?",
            reply_markup: $this->confirmSendAdsKeyboard()
        );
        $this->next("confirmSendAds");
    }

    /**
     * @throws InvalidArgumentException
     */
    public function confirmSendAds(Nutgram $bot) : void
    {
        $text = $bot->message()->text;
        if ($text == "❌ Yo'q"){
            $bot->sendMessage(
                text: "Xabar yuborish bekor qilindi!",
            );
            $bot->sendMessage(
                text: "Admin panel",
                reply_markup: $this->adminHomeKeyboard()
            );
            $this->end();
        } else if ($text == "✅ Ha") {
            $this->setConfirmSendAds($bot->userId(), 1);
            $bot->sendMessage(
                text: "Xabar yuborish boshlandi!, Amaliyot tugagandan so'ng sizga bildiramiz!",
            );
            $bot->sendMessage(
                text: "Admin panel",
                reply_markup: $this->adminHomeKeyboard()
            );
            $this->end();
        }
    }
}
