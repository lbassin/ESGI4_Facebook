<?php

declare(strict_types=1);

namespace App\Http\Helpers;

use App\Model\Website;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\FacebookResponse;
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
     *
     */
    const DATE_FORMAT = 'd/m/Y - H:i';
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
     * @param string $sourceId
     * @return string
     * @throws FacebookSDKException
     */
    public function generateSubdomain(string $sourceId): string
    {
        /** @var string $name */
        $name = $this->fbHelper->getPageName($sourceId);

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
     * @throws FacebookSDKException
     */
    public function refreshToken(Website $website): void
    {
        $website->setAccessToken($this->getAccessToken($website));
        $website->save();
    }

    /**
     * @param Website $website
     * @return string
     * @throws FacebookSDKException
     */
    public function getAccessToken(Website $website): string
    {
        /** @var FacebookResponse $response */
        $response = $this->fb->get('/me/accounts');

        if (empty($response->getDecodedBody()['data'])) {
            return '';
        }

        /** @var array $account */
        foreach ($response->getDecodedBody()['data'] as $account) {
            if (empty($account['id']) || empty($account['access_token'])) {
                continue;
            }

            if ($account['id'] == $website->getSourceId()) {
                return $account['access_token'];
            }
        }

        return '';
    }

    /**
     * @param string $url
     * @return bool
     */
    public function isValidUrl(string $url): bool
    {
        return empty($url) || !preg_match('/[^a-zA-Z0-9\-_]/', $url);
    }

    /**
     * @param string $sourceId
     * @return bool
     */
    public function isCreated(string $sourceId): bool
    {
        /** @var int $websiteCount */
        $websiteCount = Website::where(Website::SOURCE_ID, $sourceId)->count();

        return $websiteCount != 0;
    }

}