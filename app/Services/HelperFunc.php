<?php

namespace App\Services;

class HelperFunc
{
    /**
     * Create a new class instance.
     */
    public static function isAdmin(int $id) : bool {
        return env(key: 'ADMIN_ID') == $id;
    }
}
