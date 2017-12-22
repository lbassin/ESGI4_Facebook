<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Helpers\FacebookHelper;
use App\Http\Helpers\WebsiteHelper;
use App\Model\Website;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

/**
 * Class WebsiteController
 * @package App\Http\Controllers\Dashboard
 */
class AlbumController extends BaseController
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
     * AlbumController constructor.
     * @param FacebookHelper $fbHelper
     * @param WebsiteHelper $websiteHelper
     */
    public function __construct(
        FacebookHelper $fbHelper,
        WebsiteHelper $websiteHelper
    )
    {
        $this->fbHelper = $fbHelper;
        $this->websiteHelper = $websiteHelper;
    }

    /**
     * @param Request $request
     * @return View
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function createAction(Request $request): View
    {
        /** @var string $name */
        $name = $request->input('name');
        /** @var Website $website */
        $website = $this->websiteHelper->getCurrentWebsite();

        $this->fbHelper->createAlbum($name, $website->getSourceId());

        die('end');
    }
}