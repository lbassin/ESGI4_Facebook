<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Api\Event;
use App\Http\Api\Review;
use App\Http\Helpers\AlbumHelper;
use App\Http\Helpers\FacebookHelper;
use App\Model\Album;
use App\Model\Website;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;
use Illuminate\View\View;

/**
 * Class WebsiteController
 *
 * @author Laurent Bassin <laurent@bassin.info>
 */
class WebsiteController extends BaseController
{
    /**
     * @var AlbumHelper
     */
    private $albumHelper;
    /**
     * @var FacebookHelper
     */
    private $fbHelper;

    /**
     * WebsiteController constructor.
     * @param AlbumHelper $albumHelper
     * @param FacebookHelper $fbHelper
     */
    public function __construct(
        AlbumHelper $albumHelper,
        FacebookHelper $fbHelper
    )
    {
        $this->albumHelper = $albumHelper;
        $this->fbHelper = $fbHelper;
    }

    /**
     * @param string $subdomain
     * @return View
     */
    public function indexAction(string $subdomain): View
    {
        /** @var Website $website */
        $website = Website::where(Website::SUBDOMAIN, $subdomain)->first();

        return view('website.index', [
            'blocks' => $website->getHomeBlocks()
        ]);
    }

    /**
     * @param Request $request
     * @param string $subdomain
     * @return View
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function albumsAction(Request $request, string $subdomain): View
    {
        /** @var Website $website */
        $website = Website::where(Website::SUBDOMAIN, $subdomain)->first();
        /** @var array $albums */
        $albums = $this->fbHelper->getAlbums($website->getSourceId());

        $albums = array_filter($albums, function ($album) {
            /** @var \App\Http\Api\Album $album */
            return $album->isVisible();
        });

        return view('website.albums', ['albums' => $albums]);
    }

    /**
     * @return View
     */
    public function articlesAction(): View
    {
        return view('website.articles');
    }

    /**
     * @return View
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function eventsAction(Request $request, string $subdomain): View
    {
        /** @var Website $website */
        $website = Website::where(Website::SUBDOMAIN, $subdomain)->first();
        /** @var array $events */
        $events = $this->fbHelper->getEvents((string)$website->getSourceId());

        $events = array_filter($events, function ($event) {
            /** @var Event $event */
            return $event->isVisible();
        });


        return view('website.events', ['events' => $events]);
    }

    /**
     * @return View
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function reviewsAction(Request $request, string $subdomain): View
    {
        /** @var Website $website */
        $website = Website::where(Website::SUBDOMAIN, $subdomain)->first();
        /** @var array $reviews */
        $reviews = $this->fbHelper->getReviews($website);

        $reviews = array_filter($reviews, function ($review) {
            /** @var Review $review */
            return $review->isVisible();
        });

        return view('website.reviews', ['reviews' => $reviews]);
    }

    /**
     * @param Request $request
     * @param string $subdomain
     * @param string $url
     * @return View
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function viewAction(Request $request, string $subdomain, string $url): View
    {
        /** @var Album $album */
        $album = Album::where(Album::URL, $url)->first();
        if (!empty($album)) {
            return $this->viewAlbum($album);
        }

        abort(404);
        return null;
    }

    /**
     * @param Album $album
     * @return View
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    private function viewAlbum(Album $album): View
    {
        /** @var array $photos */
        $photos = $this->albumHelper->getVisiblePhotos($album);

        return view('website.albums.view', ['album' => $album, 'photos' => $photos]);
    }
}
