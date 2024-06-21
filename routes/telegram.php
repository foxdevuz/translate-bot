<?php
/** @var SergiX44\Nutgram\Nutgram $bot */

use App\Telegram\Commands\StartCommand;
use App\Telegram\Conversations\ChangeLanguage;
use App\Telegram\Conversations\TranslateConversation;
use App\Telegram\Conversations\UzbekToRussianTranslate;

/*
|--------------------------------------------------------------------------
| Nutgram Handlers
|--------------------------------------------------------------------------
|
| Here is where you can register telegram handlers for Nutgram. These
| handlers are loaded by the NutgramServiceProvider. Enjoy!
|
*/

$bot->onCommand('start', StartCommand::class);
$bot->onCallbackQuery(TranslateConversation::class);
$bot->onCallbackQueryData('change-lang', ChangeLanguage::class);
