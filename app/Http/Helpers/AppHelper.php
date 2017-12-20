<?php

namespace App\Http\Helpers;

/**
 * Class AppHelper
 * @package App\Http\Helpers
 */
class AppHelper
{
    /**
     * @param bool $withPort
     * @return string
     */
    public function getAppUrlWithoutHttp($withPort = true): string
    {
        /** @var string $url */
        $url = preg_replace('/https?:\/\//', '', env('APP_URL'));

        if(!$withPort){
            /** @var array $explodedUrl */
            $explodedUrl = explode(':', $url);
            array_pop($explodedUrl);
            return implode($explodedUrl, ',');
        }

        return $url;
    }
}