<?php

namespace App\Http\Controllers;

use App\Http\Helpers\FacebookHelper;
use App\Http\Helpers\UserHelper;
use App\Model\Website;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\FacebookResponse;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class DashboardController
 * @package App\Http\Controllers
 */
class DashboardController extends BaseController
{
    /**
     * @var LaravelFacebookSdk
     */
    private $fb;
    /**
     * @var FacebookHelper
     */
    private $fbHelper;
    /**
     * @var UserHelper
     */
    private $userHelper;

    /**
     * DashboardController constructor.
     * @param LaravelFacebookSdk $fb
     * @param FacebookHelper $fbHelper
     * @param UserHelper $userHelper
     */
    public function __construct(
        LaravelFacebookSdk $fb,
        FacebookHelper $fbHelper,
        UserHelper $userHelper
    )
    {
        $this->fb = $fb;
        $this->fbHelper = $fbHelper;
        $this->userHelper = $userHelper;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws FacebookSDKException
     */
    public function index()
    {
        /** @var FacebookResponse $response */
        $response = $this->fb->get('/me?fields=id,name,email');

        $dataUser = $response->getGraphUser();

        return view('dashboard', [
            'data' => $dataUser,
            'pages' => $this->getPages(),
            'websites' => $this->getWebsites()
        ]);
    }

    /**
     * @return array
     * @throws FacebookSDKException
     */
    private function getPages(){
        $pages = $this->fbHelper->getPages();

        return $pages;
    }

    /**
     * @return array
     */
    private function getWebsites(){
        $websites = $this->userHelper->getWebsites();

        return $websites;
    }
}
