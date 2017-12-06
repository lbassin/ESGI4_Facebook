<?php

namespace App\Http\Controllers;

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
     * DashboardController constructor.
     * @param LaravelFacebookSdk $fb
     */
    public function __construct(LaravelFacebookSdk $fb)
    {
        $this->fb = $fb;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws FacebookSDKException
     */
    public function index(Request $request)
    {
        /** @var \Illuminate\Session\Store $session */
        $session = $request->session();
        /** @var string $fbToken */
        $fbToken = $session->get(CheckAuthFb::FB_TOKEN_KEY);

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
