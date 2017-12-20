<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Helpers\AlbumHelper;
use App\Http\Helpers\FacebookHelper;
use App\Http\Helpers\WebsiteHelper;
use App\Model\Website;
use Facebook\GraphNodes\GraphAlbum;
use Facebook\GraphNodes\GraphNode;
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
     * WebsiteController constructor.
     * @param FacebookHelper $fbHelper
     * @param WebsiteHelper $websiteHelper
     * @param AlbumHelper $albumHelper
     */
    public function __construct(
        FacebookHelper $fbHelper,
        WebsiteHelper $websiteHelper,
        AlbumHelper $albumHelper
    )
    {
        $this->fbHelper = $fbHelper;
        $this->websiteHelper = $websiteHelper;
        $this->albumHelper = $albumHelper;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function indexAction(): View
    {
        /** @var Website $website */
        $website = $this->websiteHelper->getCurrentWebsite();
        /** @var GraphAlbum $albums */
        $albums = $this->fbHelper->getAlbums($website->getSourceId());

        return view('dashboard.website.index', [
            'subdomain' => $website->getSubDomain(),
            'albums' => $albums
        ]);
    }

    /**
     * @return View
     */
    public function homeAction(): View
    {
        return view('dashboard.website.home');
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

        /** @var GraphNode $album */
        foreach ($albums as $album) {
            $album['preview'] = $this->albumHelper->getRandomPicturesOfAlbum($album);
        }

        return view('dashboard.website.albums', [
            'albums' => $albums
        ]);
    }

    /**
     * @return View
     */
    public function articlesAction(): View
    {
        return view('dashboard.website.articles');
    }

    /**
     * @return View
     */
    public function eventsAction(): View
    {
        return view('dashboard.website.events');
    }

    /**
     * @return View
     */
    public function reviewsAction(): View
    {
        return view('dashboard.website.reviews');
    }
}