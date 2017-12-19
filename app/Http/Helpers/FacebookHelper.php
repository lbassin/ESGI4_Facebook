<?php

declare(strict_types=1);

namespace App\Http\Helpers;

use Facebook\Exceptions\FacebookSDKException;
use Facebook\FacebookResponse;
use Illuminate\Session\Store;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

/**
 * Class FacebookHelper
 * @package App\Http\Helpers
 */
class FacebookHelper
{
    /**
     *
     */
    const FB_TOKEN_KEY = 'fb_token';
    /**
     *
     */
    const FB_USER_ID = 'fb_user_id';
    /**
     *
     */
    const FB_SCOPES = 'public_profile,email,manage_pages';

    /**
     * @var Store
     */
    private $session;

    /**
     * @var LaravelFacebookSdk
     */
    private $fb;

    /**
     * FacebookHelper constructor.
     * @param Store $session
     * @param LaravelFacebookSdk $fb
     */
    function __construct(Store $session, LaravelFacebookSdk $fb)
    {
        $this->session = $session;
        $this->fb = $fb;
    }

    /**
     * @return string
     */
    public function getScopes(): string
    {
        return FacebookHelper::FB_SCOPES;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->session->get(FacebookHelper::FB_TOKEN_KEY) ?: '';
    }

    /**
     * @param string $token
     * @return bool
     * @throws FacebookSDKException
     */
    public function tokenIsValid(string $token): bool
    {
        /** @var string $fbAppId */
        $fbAppId = env('FACEBOOK_APP_ID');
        /** @var string $fbAppSecret */
        $fbAppSecret = env('FACEBOOK_APP_SECRET');

        $this->fb->setDefaultAccessToken($fbAppId . '|' . $fbAppSecret);

        /** @var FacebookResponse $response */
        $response = $this->fb->get('debug_token?input_token=' . $token);

        if (!isset($response->getDecodedBody()['data']['is_valid'])) {
            return false;
        }

        /** @var bool $isValid */
        $isValid = $response->getDecodedBody()['data']['is_valid'] !== false;

        if (!$isValid) {
            $this->session->forget(FacebookHelper::FB_TOKEN_KEY);
        }

        return $isValid;
    }

    /**
     * @return array
     * @throws FacebookSDKException
     */
    public function getPages(): array
    {
        /** @var string $query */
        $query = 'me/?fields=accounts{name}';

        /** @var FacebookResponse $response */
        $response = $this->fb->get($query, $this->getToken());

        if (!isset($response->getDecodedBody()['accounts']['data'])) {
            return [];
        }

        /** @var array $pages */
        $pages = $response->getDecodedBody()['accounts']['data'];

        return $pages;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->session->get(FacebookHelper::FB_USER_ID) ?: '';
    }

    /**
     * @param string $id
     * @return string
     * @throws FacebookSDKException
     */
    public function getPageName(string $id): string
    {
        /** @var FacebookResponse $response */
        $response = $this->fb->get($id . '?fields=name')->getDecodedBody();

        if (!isset($response['name'])) {
            return '';
        }

        return $response['name'];
    }
}