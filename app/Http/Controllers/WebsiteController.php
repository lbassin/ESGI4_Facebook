<?php

declare(strict_types=1);

namespace App\Http\Controllers;

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
     * @return View
     */
    public function viewAction(): View
    {
        die('Yep');
    }
}
