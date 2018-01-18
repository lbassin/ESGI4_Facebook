<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Helpers\FacebookHelper;
use App\Http\Helpers\WebsiteHelper;
use App\Model\Website;
use Closure;
use Illuminate\Http\Request;
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
     * @var WebsiteHelper
     */
    private $websiteHelper;

    /**
     * AddWebsiteDataToView constructor.
     * @param LaravelFacebookSdk $fb
     * @param FacebookHelper $fbHelper
     * @param WebsiteHelper $websiteHelper
     */
    public function __construct(
        LaravelFacebookSdk $fb,
        FacebookHelper $fbHelper,
        WebsiteHelper $websiteHelper
    )
    {
        $this->fb = $fb;
        $this->fbHelper = $fbHelper;
        $this->websiteHelper = $websiteHelper;
    }

    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var string $subdomain */
        $subdomain = $request->route('subdomain');
        view()->share('subdomain', $subdomain);

        /** @var Website $website */
        $website = Website::where(Website::SUBDOMAIN, $subdomain)->first();
        $this->fb->setDefaultAccessToken($website->getAccessToken());

        return $next($request);
    }
}
