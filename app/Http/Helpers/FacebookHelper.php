<?php

declare(strict_types=1);

namespace App\Http\Helpers;

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
    const FB_SCOPES = 'public_profile,email';

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
     *
     */
    public function getToken(): string
    {
        /** @var string $fbToken */
        return $this->session->get(FacebookHelper::FB_TOKEN_KEY);
    }

    /**
     * @param string $token
     * @return bool
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function tokenIsValid(string $token): bool
    {
        $fbAppId = env('FACEBOOK_APP_ID');
        $fbAppSecret = env('FACEBOOK_APP_SECRET');

        $this->fb->setDefaultAccessToken($fbAppId . '|' . $fbAppSecret);

        /** @var FacebookResponse $response */
        $response = $this->fb->get('debug_token?input_token=' . $token);

        if (!isset($response->getDecodedBody()['is_valid'])) {
            return false;
        }

        return $response->getDecodedBody()['is_valid'] !== 'false';
    }

}