<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Model\Album;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

/**
 * Class WebsiteController
 *
 * @author Laurent Bassin <laurent@bassin.info>
 */
class WebsiteController extends BaseController
{
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
    public function albumsAction(): View
    {
        return view('website.albums');
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
     */
    private function viewAlbum(Album $album): View
    {
        return view('website.albums.view', ['album' => $album]);
    }
}
