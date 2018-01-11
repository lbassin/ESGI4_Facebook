<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Helpers\AlbumHelper;
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
     * WebsiteController constructor.
     * @param AlbumHelper $albumHelper
     */
    public function __construct(AlbumHelper $albumHelper)
    {

        $this->albumHelper = $albumHelper;
    }

    /**
     * @return View
     */
    public function indexAction(): View
    {
        return view('website.index');
    }

    /**
     * @return View
     */
    public function albumsAction(Request $request, $subdomain): View
    {
        /** @var Website $website */
        $website = Website::where(Website::SUBDOMAIN, $subdomain)->first();
        /** @var Collection $albums */
        $albums = Album::where(Album::WEBSITE_ID, $website->getId())->get();

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
     */
    public function eventsAction(): View
    {
        return view('website.events');
    }

    /**
     * @return View
     */
    public function reviewsAction(): View
    {
        return view('website.reviews');
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
