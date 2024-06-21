<?php

namespace App\Telegram\Commands;

use App\Models\User;
use App\Traits\InlineKeyboards;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Handlers\Type\Command;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use function Laravel\Prompts\text;

class StartCommand extends Command
{
    use InlineKeyboards;
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
            reply_markup: $this->translateLanKeyboard()
        );
    }
}
