<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Helpers\AlbumHelper;
use App\Http\Helpers\FacebookHelper;
use App\Http\Helpers\WebsiteHelper;
use App\Http\Helpers\UserHelper;
use App\Model\Menu;
use App\Model\Website;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

/**
 * Class WebsiteController
 * @package App\Http\Controllers\Dashboard
 */
class WebsiteController extends BaseController
{

    /**
     * @var FacebookHelper
     */
    private $fbHelper;
    /**
     * @var WebsiteHelper
     */
    private $websiteHelper;
    /**
     * @var AlbumHelper
     */
    private $albumHelper;
    /**
     * @var UserHelper
     */
    private $userHelper;

    /**
     * WebsiteController constructor.
     * @param FacebookHelper $fbHelper
     * @param WebsiteHelper $websiteHelper
     * @param AlbumHelper $albumHelper
     * @param UserHelper $userHelper
     */
    public function __construct(
        FacebookHelper $fbHelper,
        WebsiteHelper $websiteHelper,
        AlbumHelper $albumHelper,
        UserHelper $userHelper)
    {
        $this->fbHelper = $fbHelper;
        $this->websiteHelper = $websiteHelper;
        $this->albumHelper = $albumHelper;
        $this->userHelper = $userHelper;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function indexAction(): View
    {
        /** @var Website $website */
        $website = $this->websiteHelper->getCurrentWebsite();
        /** @var array $albums */
        $albums = $this->fbHelper->getAlbums($website->getSourceId());

        return view('dashboard.website.index', [
            'albums' => array_slice($albums, 0, 6)
        ]);
    }

    /**
     * @return View
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function albumsAction(): View
    {
        /** @var Website $website */
        $website = $this->websiteHelper->getCurrentWebsite();

        /** @var array $albums */
        $albums = $this->fbHelper->getAlbums($website->getSourceId());

        return view('dashboard.website.album.index', [
            'albums' => $albums,
        ]);
    }

    /**
     * @return View
     */
    public function articlesAction(): View
    {
        return view('dashboard.website.article.index');
    }

    /**
     * @return View
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function eventsAction(): View
    {
        /** @var Website $website */
        $website = $this->websiteHelper->getCurrentWebsite();

        /** @var array $events */
        $events = $this->fbHelper->getEvents($website->getSourceId());

        return view('dashboard.website.event.index', [
            'events' => $events
        ]);
    }

    /**
     * @return View
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function reviewsAction(): View
    {
        /** @var Website $website */
        $website = $this->websiteHelper->getCurrentWebsite();

        /** @var array $reviews */
        $reviews = $this->fbHelper->getReviews($website);

        return view('dashboard.website.review.index', [
            'reviews' => $reviews
        ]);
    }
}