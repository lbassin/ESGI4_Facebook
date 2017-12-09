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
     */
    public function tokenIsValid(string $token): bool
    {
        /** @var string $fbAppId */
        $fbAppId = env('FACEBOOK_APP_ID');
        /** @var string $fbAppSecret */
        $fbAppSecret = env('FACEBOOK_APP_SECRET');

        $this->fb->setDefaultAccessToken($fbAppId . '|' . $fbAppSecret);

        try {
            /** @var FacebookResponse $response */
            $response = $this->fb->get('debug_token?input_token=' . $token);
        } catch (FacebookSDKException $ex) {
            // TODO
        }

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

}