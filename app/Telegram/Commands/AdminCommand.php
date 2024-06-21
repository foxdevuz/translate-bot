<?php

namespace App\Telegram\Commands;

use App\Services\HelperFunc;
use App\Traits\InlineKeyboards;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Handlers\Type\Command;

class AdminCommand extends Command
{
    use InlineKeyboards;
    protected string $command = 'command';

    protected ?string $description = 'A lovely description.';

    public function handle(Nutgram $bot): void
    {
        if (HelperFunc::isAdmin($bot->userId())){
            $bot->sendMessage(
                text: "Salom Admin! 👋",
                reply_markup: $this->adminHomeKeyboard()
            );
        } else {
            $bot->sendMessage('Siz admin emassiz!');
        }
    }
}
