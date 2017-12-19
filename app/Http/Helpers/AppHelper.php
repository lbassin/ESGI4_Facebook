<?php

namespace App\Http\Helpers;

/**
 * Class AppHelper
 * @package App\Http\Helpers
 */
class AppHelper
{
    /**
     * @return string
     */
    public function getAppUrlWithoutHttp(): string
    {
        return preg_replace('/https?:\/\//', '', env('APP_URL'));
    }
}