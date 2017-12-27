<?php

declare(strict_types=1);

namespace App\Http\Helpers;

use App\Http\Api\Album;
use App\Http\Api\Event;
use App\Http\Api\Review;
use App\Model\Website;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\GraphNodes\GraphAlbum;
use Facebook\GraphNodes\GraphEdge;
use Facebook\GraphNodes\GraphEvent;
use Facebook\GraphNodes\GraphNodeFactory;
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
    const FB_SCOPES = 'public_profile,email,manage_pages,user_photos,publish_pages';

    /**
     * @var Store
     */
    private $session;

    /**
     * @var LaravelFacebookSdk
     */
    private $fb;
    /**
     * @var GraphNodeFactory
     */
    private $nodeFactory;

    /**
     * FacebookHelper constructor.
     * @param Store $session
     * @param LaravelFacebookSdk $fb
     * @param GraphNodeFactory $nodeFactory
     */
    function __construct(
        Store $session,
        LaravelFacebookSdk $fb,
        GraphNodeFactory $nodeFactory
    )
    {
        $this->session = $session;
        $this->fb = $fb;
        $this->nodeFactory = $nodeFactory;
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
        /** @var string $appToken */
        $appToken = env('FACEBOOK_APP_ID') . '|' . env('FACEBOOK_APP_SECRET');

        /** @var FacebookResponse $response */
        $response = $this->fb->get('debug_token?input_token=' . $token, $appToken);

        if (!isset($response->getDecodedBody()['data']['is_valid'])) {
            return false;
        }

        return $response->getDecodedBody()['data']['is_valid'] !== false;
    }

    /**
     * @return array
     * @throws FacebookSDKException
     */
    public function getPages(): array
    {
        /** @var string $query */
        $query = 'me/?fields=accounts{name,picture{url}}';

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
    public function getUserPhoto(): string
    {
        /** @var FacebookResponse $response */
        $response = $this->fb->get('/me/picture?fields=url&redirect=false')->getDecodedBody();

        if (!isset($response['data']['url'])) {
            return '';
        }

        return $response['data']['url'];
    }

    /**
     * @return string
     * @throws FacebookSDKException
     */
    public function getUserName(): string
    {
        /** @var FacebookResponse $response */
        $response = $this->fb->get('/me?fields=name')->getDecodedBody();

        if (!isset($response['name'])) {
            return '';
        }

        return $response['name'];
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
    public function getAlbums($id = 'me'): array
    {
        /** @var string $albumQuery */
        $albumQuery = $id . '/albums?fields=id,name,updated_time,cover_photo{picture},photos{id,name,images}';
        /** @var GraphEdge $albums */
        $response = $this->fb->get($albumQuery)->getGraphEdge();

        /** @var array $albums */
        $albums = [];
        /** @var GraphAlbum $album */
        foreach ($response->all() as $album) {
            $albums[] = new Album($album);
        }

        return $albums;
    }

    /**
     * @param string $name
     * @param Website $website
     * @throws FacebookSDKException
     */
    public function createAlbum(string $name, Website $website): void
    {
        /** @var array $albumData */
        $albumData = [
            'name' => $name
        ];

        /** @var FacebookRequest $request */
        $request = $this->fb->request('post', 'me/albums', $albumData, $website->getAccessToken());

        $this->fb->getClient()->sendRequest($request);

        dd($request);
    }

    /**
     * @param string $id
     * @return array
     * @throws FacebookSDKException
     */
    public function getEvents(string $id = 'me'): array
    {
        /** @var string $eventQuery */
        $eventQuery = $id . '/events?fields=cover{source},start_time,end_time,name,place{name}';
        /** @var GraphEdge $albums */
        $response = $this->fb->get($eventQuery)->getGraphEdge();

        /** @var array $events */
        $events = [];
        /** @var GraphEvent $album */
        foreach ($response->all() as $event) {
            $events[] = new Event($event);
        }

        return $events;
    }

    /**
     * @param Website $website
     * @return array
     * @throws FacebookSDKException
     */
    public function getReviews(Website $website): array
    {
        /** @var string $reviewQuery */
        $reviewQuery = $website->getSourceId() . '/ratings?fields=review_text,reviewer{name, picture{url}},rating,created_time';
        /** @var GraphEdge $response */
        $response = $this->fb->get($reviewQuery, $website->getAccessToken())->getGraphEdge();

        $reviews = [];
        foreach ($response->all() as $review) {
            $reviews[] = new Review($review);
        }

        return $reviews;
    }
}