<?php

declare(strict_types=1);

namespace App\Http\Helpers;

use App\Model\Website;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Session\Store;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

/**
 * Class WebsiteHelper
 * @package App\Http\Helpers
 */
class WebsiteHelper
{
    /**
     *
     */
    const WEBSITE_KEY = 'website';
    /**
     * @var FacebookHelper
     */
    private $fbHelper;
    /**
     * @var AppHelper
     */
    private $appHelper;
    /**
     * @var Store
     */
    private $session;
    /**
     * @var LaravelFacebookSdk
     */
    private $fb;

    /**
     * WebsiteHelper constructor.
     * @param FacebookHelper $fbHelper
     * @param AppHelper $appHelper
     * @param Store $session
     * @param LaravelFacebookSdk $fb
     */
    public function __construct(
        FacebookHelper $fbHelper,
        AppHelper $appHelper,
        Store $session,
        LaravelFacebookSdk $fb
    )
    {
        $this->fbHelper = $fbHelper;
        $this->appHelper = $appHelper;
        $this->session = $session;
        $this->fb = $fb;
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
        $name = trim($name, '-');

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

    /**
     * @return Website
     */
    public function getCurrentWebsite()
    {
        return $this->session->get(self::WEBSITE_KEY) ?: new Website();
    }

    /**
     * @param Website $website
     */
    public function setCurrentWebsite(Website $website)
    {
        $this->session->put(self::WEBSITE_KEY, $website);
    }

    /**
     * @param Website $website
     */
    public function refreshToken(Website $website): void
    {

    }

}