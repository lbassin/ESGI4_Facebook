<?php

namespace App\Http\Controllers;

use App\Http\Helpers\FacebookHelper;
use Illuminate\Session\Store;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

/**
 * Class FacebookController
 * @package App\Http\Controllers
 */
class FacebookController extends Controller
{
    /**
     * @var LaravelFacebookSdk
     */
    private $fb;
    /**
     * @var Store
     */
    private $session;

    /**
     * DashboardController constructor.
     * @param LaravelFacebookSdk $fb
     * @param Store $session
     */
    public function __construct(LaravelFacebookSdk $fb, Store $session)
    {
        $this->fb = $fb;
        $this->session = $session;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function reAskPermissions()
    {
        /** @var string $redirectTo */
        $redirectTo = $this->session->get('redirectTo') ?: route('dashboard');

        $this->session->forget(FacebookHelper::FB_TOKEN_KEY);

        return view('reAskPermissions', [
            'redirectTo' => $redirectTo
        ]);
    }
}
