<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckAuthFb;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\FacebookResponse;
use Illuminate\Http\Request;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class FacebookController extends Controller
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

    public function reAskPermissions(){
        return view('reAskPermissions');
    }
}
