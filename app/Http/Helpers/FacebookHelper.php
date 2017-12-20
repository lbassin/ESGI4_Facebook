<?php

declare(strict_types=1);

namespace App\Http\Helpers;

use Facebook\Exceptions\FacebookSDKException;
use Facebook\FacebookResponse;
use Facebook\GraphNodes\GraphAlbum;
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
    const FB_SCOPES = 'public_profile,email,manage_pages,user_photos';

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

    /**
     * @return string
     * @throws FacebookSDKException
     */
    public function getUserPhoto()
    {
        /** @var FacebookResponse $response */
        $response = $this->fb->get('/me/picture?fields=url&redirect=false')->getDecodedBody();

        if (!isset($response['data']['url'])) {
            return '';
        }

        return $response['data']['url'];
    }

    /**
     * @return \Facebook\GraphNodes\GraphUser
     * @throws FacebookSDKException
     */
    public function getBasicUserData()
    {
        /** @var FacebookResponse $response */
        $response = $this->fb->get('/me?fields=id,name,email,picture{url}');

        return $response->getGraphUser();
    }

    /**
     * @param string $id
     * @return array
     * @throws FacebookSDKException
     */
    public function getAlbums($id = 'me')
    {
        /** @var array $albums */
        $albums = [];
        /** @var string $query */
        $albumQuery = $id . '?fields=albums{id,name,updated_time,cover_photo{picture},photos{id,name,picture}}';
        /** @var array $albums */
        $response = $this->fb->get($albumQuery)->getDecodedBody();

        /** @var array $albumData */
        foreach ($response['albums']['data'] as $albumData) {
            $albums[] = new GraphAlbum($albumData);
        }

        return $albums;
    }
}