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
class AddDashboardDataToView
{
    /**
     * @var UserHelper
     */
    private $userHelper;
    /**
     * @var WebsiteHelper
     */
    private $websiteHelper;

    /**
     * AuthFb constructor.
     * @param UserHelper $userHelper
     * @param WebsiteHelper $websiteHelper
     */
    public function __construct(
        UserHelper $userHelper,
        WebsiteHelper $websiteHelper
    )
    {
        $this->userHelper = $userHelper;
        $this->websiteHelper = $websiteHelper;
    }

    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        view()->share('userHelper', $this->userHelper);
        view()->share('subdomain', $this->websiteHelper->getCurrentWebsite()->getSubDomain());

        return $next($request);
    }
}
