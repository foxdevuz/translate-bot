<?php
    require './Telegram/TelegramBot.php';
    require './helpers/functions.php';
    require './db_config/db_config.php';

    use TelegramBot as Bot;

    $token = getEnvVariable('BOT_TOKEN');
    $dataSet = ['botToken' => $token];
    $bot = new Bot($dataSet);

    require_once './helpers/variables.php';

    try {
        $db = new DB_CONFIG(
            getEnvVariable('HOSTNAME'),
            getEnvVariable('USERNAME'),
            getEnvVariable('PASSWORD'),
            getEnvVariable('DATABASE_NAME')
        );
    } catch (Exception $e) {
        $bot->sendMessage($e->getMessage(), getEnvVariable('ADMIN_ID'));
    }

    include './helpers/sendMessageToUsers.php';
    include './helpers/activeUsers.php';
    include './helpers/keyboards/inline_keyboards.php';
    include './helpers/keyboards/resize_keyboards.php';

    if ($update && isset($update->message)) {
        if ($type == 'private') {
            if (removeBotUserName($text) == "/start") {
                $myUser = myUser(['fromid', 'name', 'user', 'chat_type', 'lang', 'del'], [$fromid, $full_name, $user ?? null, 'private', '', 0]);
            }
            if (channel($fromid)) {
                $user = mysqli_fetch_assoc($db->selectWhere('users', [['fromid' => $fromid, 'cn' => '=']]));

                if (removeBotUserName($text) == "/start") {
                    $message = "Hello there! We are optimizing our bot. Please try again later. Thank you! For updates check: @webfoxuz";
                    $bot->sendMessage($message, $chat_id);
                    exit();
                }
            }
        } else {
            if (removeBotUserName($text) == "/start") {
                $myUser = myUser(['fromid', 'name', 'user', 'chat_type', 'lang', 'del'], [$fromid, $full_name, $user, 'group', '', 0]);
                $message = "Hello there! We are optimizing our bot. Please try again later. Thank you! For updates check: @webfoxuz";
                $bot->sendChatAction('typing', $chat_id)->sendMessage($message);
                exit();
            }
        }
    } elseif (isset($update->callback_query)) {
        if (channel($callback_from_id)) {
            $user = mysqli_fetch_assoc($db->selectWhere('users', [['fromid' => $callback_from_id, 'cn' => '=']]));

        }
    }

    include 'helpers/admin/admin.php';
