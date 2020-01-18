<?php

namespace App;

use Illuminate\Support\Facades\Cache;

class AppSetting
{
    public static function get(string $key)
    {
        $settings = Cache::rememberForever('appSettings', function () {
            return json_decode(file_get_contents(config('nova-settings-tool.path')), true);
        });

        if (isset($settings[$key])) return $settings[$key];

        return null;
    }
}