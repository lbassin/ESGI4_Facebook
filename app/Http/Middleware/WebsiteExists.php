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
 * Class WebsiteExists
 * @package App\Http\Middleware
 */
class WebsiteExists
{
    /**
     * @var WebsiteHelper
     */
    private $websiteHelper;
    /**
     * @var FacebookHelper
     */
    private $fbHelper;
    /**
     * @var LaravelFacebookSdk
     */
    private $fb;

    /**
     * WebsiteExists constructor.
     * @param WebsiteHelper $websiteHelper
     * @param FacebookHelper $fbHelper
     * @param LaravelFacebookSdk $fb
     */
    public function __construct(
        WebsiteHelper $websiteHelper,
        FacebookHelper $fbHelper,
        LaravelFacebookSdk $fb
    )
    {
        $this->websiteHelper = $websiteHelper;
        $this->fbHelper = $fbHelper;
        $this->fb = $fb;
    }

    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var string $subdomain */
        $subdomain = $request->route()->parameter('subdomain');
        /** @var Website $website */
        $website = Website::where(Website::SUBDOMAIN, $subdomain)->first();

        if (empty($website)) {
            abort(404);
        }

        if (!$website->getAccessToken() || !$this->fbHelper->tokenIsValid($website->getAccessToken())) {
            $this->websiteHelper->refreshToken($website);
        }

        $this->websiteHelper->setCurrentWebsite($website);

        // TODO : Change with our app token
        $this->fb->setDefaultAccessToken($website->getAccessToken());

        return $next($request);
    }

}
