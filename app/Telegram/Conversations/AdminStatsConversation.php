<?php

namespace App\Telegram\Conversations;

use App\Traits\AdminHelpers;
use Psr\SimpleCache\InvalidArgumentException;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;

class AdminStatsConversation extends Conversation
{
    use AdminHelpers;

    /**
     * @throws InvalidArgumentException
     */
    public function start(Nutgram $bot) : void
    {
        $bot->sendMessage($this->getStat());
        $this->end();
    }
}
