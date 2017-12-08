<?php

namespace App\Http\Controllers;

use App\Http\Helpers\FacebookHelper;
use App\Http\Middleware\CheckAuthFb;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\FacebookResponse;
use Illuminate\Http\Request;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

/**
 * Class DashboardController
 * @package App\Http\Controllers
 */
class DashboardController extends Controller
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
     * DashboardController constructor.
     * @param LaravelFacebookSdk $fb
     * @param FacebookHelper $fbHelper
     */
    public function __construct(LaravelFacebookSdk $fb, FacebookHelper $fbHelper)
    {
        $this->fb = $fb;
        $this->fbHelper = $fbHelper;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws FacebookSDKException
     */
    public function index()
    {
        /** @var string $fbToken */
        $fbToken = $this->fbHelper->getToken();

        $this->fb->setDefaultAccessToken($fbToken);

        try {
            /** @var FacebookResponse $response */
            $response = $this->fb->get('/me?fields=id,name,email');
        } catch (FacebookSDKException $e) {
            dd($e->getMessage());
        }

        $dataUser = $response->getGraphUser();

        return view('dashboard', [
            'data' => $dataUser
        ]);
    }
}
