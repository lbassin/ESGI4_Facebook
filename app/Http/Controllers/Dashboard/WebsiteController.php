<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Helpers\FacebookHelper;
use App\Http\Helpers\WebsiteHelper;
use App\Model\Website;
use Facebook\GraphNodes\GraphAlbum;
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
     * WebsiteController constructor.
     * @param FacebookHelper $fbHelper
     * @param WebsiteHelper $websiteHelper
     */
    public function __construct(FacebookHelper $fbHelper, WebsiteHelper $websiteHelper)
    {
        $this->fbHelper = $fbHelper;
        $this->websiteHelper = $websiteHelper;
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
            'albums' => $albums
        ]);
    }

    public function homeAction(): View
    {
        return view('dashboard.website.home');
    }

    public function albumsAction(): View
    {
        return view('dashboard.website.albums');
    }

    public function articlesAction(): View
    {
        return view('dashboard.website.articles');
    }

    public function eventsAction(): View
    {
        return view('dashboard.website.events');
    }

    public function reviewsAction(): View
    {
        return view('dashboard.website.reviews');
    }
}