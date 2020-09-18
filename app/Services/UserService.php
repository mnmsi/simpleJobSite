<?php

namespace App\Services;

use Session;

class UserService
{
    public static function getUserId()
    {
        $loginData = Session::get('loginData');

        return $loginData['userId'];
    }

    public static function getUserManus()
    {
        $loginData = Session::get('loginData');

        return $loginData['userMenus'];
    }
}