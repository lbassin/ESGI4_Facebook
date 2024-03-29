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
use Facebook\GraphNodes\GraphNode;
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
    const FB_USER_ID_KEY = 'fb_user_id';
    /**
     *
     */
    const FB_NAME_KEY = 'fb_name';
    /**
     *
     */
    const FB_PICTURE_KEY = 'fb_picture';
    /**
     *
     */
    const FB_SCOPES = 'public_profile,email,manage_pages,publish_pages';

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
        $appToken = $this->getAppToken();

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
     * @throws FacebookSDKException
     */
    public function getUserId(): string
    {
        if (!$this->session->has(self::FB_USER_ID_KEY)) {
            /** @var FacebookResponse $response */
            $response = $this->fb->get('me?fields=id')->getDecodedBody();

            if (isset($response['id'])) {
                $this->session->put(self::FB_USER_ID_KEY, $response['id']);
            }
        }

        return $this->session->get(self::FB_USER_ID_KEY);
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
        if (!$this->session->has(self::FB_PICTURE_KEY)) {
            /** @var FacebookResponse $response */
            $response = $this->fb->get('/me/picture?fields=url&redirect=false')->getDecodedBody();

            if (!isset($response['data']['url'])) {
                return '';
            }

            $this->session->put(self::FB_PICTURE_KEY, $response['data']['url']);
        }

        return $this->session->get(self::FB_PICTURE_KEY);
    }

    /**
     * @return string
     * @throws FacebookSDKException
     */
    public function getUserName(): string
    {
        if (!$this->session->has(self::FB_NAME_KEY)) {
            /** @var FacebookResponse $response */
            $response = $this->fb->get('/me?fields=name')->getDecodedBody();

            if (!isset($response['name'])) {
                return '';
            }

            $this->session->put(self::FB_NAME_KEY, $response['name']);
        }

        return $this->session->get(self::FB_NAME_KEY);
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
        $albumQuery = $id . '/albums?fields=id,name,description,updated_time,cover_photo{images}';
        /** @var GraphEdge $albums */
        $response = $this->fb->get($albumQuery)->getGraphEdge();
        /** @var array $albums */
        $albums = [];

        /** @var GraphAlbum $graph */
        foreach ($response->all() as $graph) {

            /** @var Album $album */
            $album = new Album($graph);

            /** @var \App\Model\Album $albumModel */
            $albumModel = \App\Model\Album::where(\App\Model\Album::ID, $album->getId())->first();
            if (!empty($albumModel)) {
                $album->setModel($albumModel);
            }

            $albums[] = $album;
        }

        return $albums;
    }

    /**
     * @param string $id
     * @return Album
     * @throws FacebookSDKException
     */
    public function getAlbum(string $id): Album
    {
        /** @var string $query */
        $query = $id . '?fields=id,name,description,updated_time,cover_photo{images},photos{id,name,images}';

        /** @var GraphAlbum $response */
        $response = $this->fb->get($query)->getGraphAlbum();

        $album = new Album($response);

        /** @var \App\Model\Album $albumModel */
        $albumModel = \App\Model\Album::where(\App\Model\Album::ID, $album->getId())->first();
        if (!empty($albumModel)) {
            $album->setModel($albumModel);
        }

        return $album;
    }

    /**
     * @param string $name
     * @param Website $website
     * @return Album
     * @throws FacebookSDKException
     * @throws \Exception
     */
    public function createAlbum(string $name, Website $website): Album
    {
        /** @var array $albumData */
        $albumData = [
            'name' => $name
        ];

        /** @var FacebookRequest $request */
        $request = $this->fb->request('post', 'me/albums', $albumData, $website->getAccessToken());

        /** @var FacebookResponse $response */
        $response = $this->fb->getClient()->sendRequest($request)->getDecodedBody();

        if (empty($response['id'])) {
            throw new \Exception('An error occured');
        }

        /** @var string $albumId */
        $albumId = $response['id'];

        return $this->getAlbum($albumId);
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
        /** @var GraphEvent $graph */
        foreach ($response->all() as $graph) {
            $event = new Event($graph);

            /** @var \App\Model\Event $databaseEvent */
            $databaseEvent = \App\Model\Event::where(\App\Model\Event::ID, $event->getId())->first();

            if (!empty($databaseEvent)) {
                $event->setModel($databaseEvent);
            }

            $events[] = $event;
        }

        return $events;
    }

    /**
     * @param string $id
     * @return Event
     * @throws FacebookSDKException
     */
    public function getEvent(string $id): Event
    {
        /** @var string $query */
        $query = $id . '?fields=cover{source},start_time,end_time,name,place{name}';
        /** @var GraphEvent $graph */
        $graph = $this->fb->get($query)->getGraphEvent();
        /** @var Event $event */
        $event = new Event($graph);
        /** @var \App\Model\Event $databaseEvent */
        $databaseEvent = \App\Model\Event::where(\App\Model\Event::ID, $event->getId())->first();

        if (!empty($databaseEvent)) {
            $event->setModel($databaseEvent);
        }

        return $event;
    }

    /**
     * @param Website $website
     * @return array
     * @throws FacebookSDKException
     */
    public function getReviews(Website $website): array
    {
        /** @var string $reviewQuery */
        $reviewQuery = $website->getSourceId() . '/ratings?fields=id,review_text,reviewer{name, picture{url}},rating,created_time';
        /** @var GraphEdge $response */
        $response = $this->fb->get($reviewQuery, $website->getAccessToken())->getGraphEdge();
        /** @var array $reviews */
        $reviews = [];
        /** @var GraphNode $graph */
        foreach ($response->all() as $graph) {
            $graph['source_id'] = $website->getSourceId();

            /** @var Review $review */
            $review = new Review($graph);
            /** @var int $reviewerId */
            $reviewerId = $review->getReviewerId();
            /** @var \App\Model\Review $databaseReview */
            $databaseReview = \App\Model\Review::where(\App\Model\Review::SOURCE_ID, $website->getSourceId())
                ->where(\App\Model\Review::REVIEWER_ID, $reviewerId)->first();

            if (!empty($databaseReview)) {
                $review->setModel($databaseReview);
            }

            $reviews[] = $review;
        }

        return $reviews;
    }

    /**
     * @param Website $website
     * @param string $reviewerId
     * @return Review
     * @throws FacebookSDKException
     */
    public function getReview(Website $website, string $reviewerId): Review
    {
        /** @var string $query */
        $query = $website->getSourceId() . '/ratings?fields=id,review_text,reviewer{name, picture{url}},rating,created_time';
        /** @var GraphEdge $response */
        $response = $this->fb->get($query, $website->getAccessToken())->getGraphEdge();

        /** @var GraphNode $graph */
        foreach ($response->all() as $graph) {
            /** @var GraphNode $reviewer */
            $reviewer = $graph->getField('reviewer');
            if (empty($reviewer)) {
                continue;
            }

            if ($reviewer->getField('id') == $reviewerId) {
                $graph['source_id'] = $website->getSourceId();

                /** @var Review $review */
                $review = new Review($graph);

                /** @var \App\Model\Review $databaseReview */
                $databaseReview = \App\Model\Review::where(\App\Model\Review::SOURCE_ID, $website->getSourceId())
                    ->where(\App\Model\Review::REVIEWER_ID, $reviewerId)->first();

                if (!empty($databaseReview)) {
                    $review->setModel($databaseReview);
                }

                return $review;
            }
        }

        die('Review not found'); // TODO
    }

    /**
     * @return array
     * @throws FacebookSDKException
     */
    public function getAdminUsers(): array
    {
        /** @var array $users */
        $users = [];
        /** @var string $appToken */
        $appToken = $this->getAppToken();
        /** @var string $query */
        $query = '/' . env('FACEBOOK_APP_ID') . '/roles?fields=user,role';

        /** @var GraphEdge $response */
        $response = $this->fb->get($query, $appToken)->getGraphEdge();
        /** @var array $allowedGroups */
        $allowedGroups = ['developers', 'administrators'];

        /** @var GraphNode $user */
        foreach ($response->all() as $user) {
            if (!in_array($user->getField('role'), $allowedGroups)) {
                continue;
            }

            $users[] = $user->getField('user');
        }

        return $users;
    }

    /**
     * @return string
     */
    public function getAppToken(): string
    {
        return env('FACEBOOK_APP_ID') . '|' . env('FACEBOOK_APP_SECRET');
    }
}