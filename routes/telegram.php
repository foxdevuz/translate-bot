<?php
/** @var SergiX44\Nutgram\Nutgram $bot */

use App\Telegram\Commands\AdminCommand;
use App\Telegram\Commands\StartCommand;
use App\Telegram\Conversations\AdminConversation;
use App\Telegram\Conversations\AdminStatsConversation;
use App\Telegram\Conversations\ChangeLanguage;
use App\Telegram\Conversations\TranslateConversation;


$bot->onCommand('start', StartCommand::class);
$bot->onCommand('admin', AdminCommand::class);
$bot->onCallbackQueryData('change-lang', ChangeLanguage::class);
$bot->onCallbackQueryData('stats', AdminStatsConversation::class);
$bot->onCallbackQueryData("send-message", AdminConversation::class);
$bot->onCallbackQuery(TranslateConversation::class);
