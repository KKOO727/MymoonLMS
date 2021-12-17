<?php

namespace App\Handlers;

use Illuminate\Support\Str;

class LfmConfigHandler extends \UniSharp\LaravelFilemanager\Handlers\ConfigHandler
{
    public function userField()
    {
        $user = auth()->user();
        /*if ($user->isAdmin()) {
            return false;
        }*/

        return $user->username;
    }
}

