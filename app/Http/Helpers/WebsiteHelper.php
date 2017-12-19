<?php

declare(strict_types=1);

namespace App\Http\Helpers;

use App\Model\Website;

/**
 * Class WebsiteHelper
 * @package App\Http\Helpers
 */
class WebsiteHelper
{
    /**
     * @var FacebookHelper
     */
    private $fbHelper;
    /**
     * @var AppHelper
     */
    private $appHelper;

    /**
     * WebsiteHelper constructor.
     * @param FacebookHelper $fbHelper
     * @param AppHelper $appHelper
     */
    public function __construct(FacebookHelper $fbHelper, AppHelper $appHelper)
    {
        $this->fbHelper = $fbHelper;
        $this->appHelper = $appHelper;
    }

    /**
     * @param Website $website
     * @return string
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function generateSubdomain(Website $website): string
    {
        if (empty($website->getSourceId())) {
            return '';
        }

        /** @var string $name */
        $name = $this->fbHelper->getPageName($website->getSourceId());

        $name = strtolower($name);
        $name = str_replace(' ', '-', $name);
        $name = str_replace(['é', 'è'], 'e', $name);
        $name = preg_replace('/[^a-zA-Z0-9-\]_]/', '', $name);
        $name = preg_replace('/-+/', '-', $name);

        return $name;
    }

    /**
     * @param Website $website
     * @return string
     */
    public function getWebsiteFullUrl(Website $website): string
    {
        /** @var string $url */
        $url = '//' . $website->getSubDomain() . '.' . $this->appHelper->getAppUrlWithoutHttp();

        return $url;
    }

}