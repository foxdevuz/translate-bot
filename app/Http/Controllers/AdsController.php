<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use App\Traits\AdminHelpers;
use SergiX44\Nutgram\Nutgram;
class AdsController extends Controller
{
    use AdminHelpers;
    public function sendAds(Nutgram $bot)
    {
        $users = User::all();
        $admin = Admin::where('confirm_send', 1)->first();
        if ($admin) {
            $admin_chat_id = $admin->admin_id;
            $ads_msg_id = $admin->ad_message_id;

            foreach ($users as $user) {
                $bot->copyMessages(
                    chat_id: $user->user_id,
                    from_chat_id: $admin_chat_id,
                    message_ids: [$ads_msg_id]
                );
                sleep(0.032);
            }

            $this->updateAdminSendAds($admin_chat_id, null);
            $this->setConfirmSendAds($admin_chat_id, 0);
            $bot->sendMessage(
                text: "Xabar barcha foydalanuvchilarga yuborildi!",
                chat_id: $admin_chat_id
            );
            return response()->json(
                data: [
                    'message'=>"Xabar barcha foydalanuvchilarga yuborildi!"
                ]
            );
        } else {
            return response()->json(
                data: [
                    'message'=>"You! Shut UP! I am not gonna send any ads to anyone!"
                ],
                status: 406
            );
        }
    }
}
