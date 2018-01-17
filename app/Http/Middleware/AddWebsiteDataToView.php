<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Helpers\FacebookHelper;
use App\Http\Helpers\UserHelper;
use App\Http\Helpers\WebsiteHelper;
use Closure;
use Facebook\Authentication\AccessToken;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\FacebookResponse;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

/**
 * Class CheckAuthFb
 * @package App\Http\Middleware
 */
class AddWebsiteDataToView
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
     * AddWebsiteDataToView constructor.
     * @param LaravelFacebookSdk $fb
     * @param FacebookHelper $fbHelper
     */
    public function __construct(
        LaravelFacebookSdk $fb,
        FacebookHelper $fbHelper
    )
    {
        $this->fb = $fb;
        $this->fbHelper = $fbHelper;
    }

    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        view()->share('subdomain', $request->route('subdomain'));

        $this->fb->setDefaultAccessToken($this->fbHelper->getAppToken());

        return $next($request);
    }
}
