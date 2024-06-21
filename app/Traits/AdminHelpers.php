<?php

namespace App\Traits;

use App\Models\Admin;
use App\Models\User;

trait AdminHelpers
{
    /**
     * @return string
     */
    protected function getStat() : string
    {
        // get the number of users
        $users = User::all()->count();

        // get last month joined users
        $last_month = User::where('created_at', '>=', now()->subMonth())->count();

        // get last week joined users
        $last_week = User::where('created_at', '>=', now()->subDays(7))->count();

        // last 24 hours joined users
        $last_24_hours = User::where('created_at', '>=', now()->subHours(24))->count();

        return "👥 **Foydalanuvchilar**:\n\n Jamiiy soni: **$users** ta\n O'tgan oyda: **$last_month** ta\n O'tgan haftada: **$last_week** ta\n O'tgan 24 soatda: **$last_24_hours** ta\n\n";
    }

    protected function updateAdminSendAds(int $admin_id, int|null $ad_message_id) : void
    {
        $admin = Admin::where('admin_id', $admin_id)->first();
        $admin->ad_message_id = $ad_message_id;
        $admin->save();
    }

    protected function setConfirmSendAds(int $admin_id, int $confirm) : void
    {
        $admin = Admin::where('admin_id', $admin_id)->first();
        $admin->confirm_send = $confirm;
        $admin->save();
    }
}
