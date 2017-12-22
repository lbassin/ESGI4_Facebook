<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Helpers\FacebookHelper;
use App\Http\Helpers\WebsiteHelper;
use App\Model\Website;
use Closure;
use Illuminate\Http\Request;

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
     * WebsiteExists constructor.
     * @param WebsiteHelper $websiteHelper
     * @param FacebookHelper $fbHelper
     */
    public function __construct(
        WebsiteHelper $websiteHelper,
        FacebookHelper $fbHelper
    )
    {
        $this->websiteHelper = $websiteHelper;
        $this->fbHelper = $fbHelper;
    }

    /**
     * @param $request
     * @param Closure $next
     * @return mixed
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

//        if (!$this->fbHelper->tokenIsValid($website->getAccessToken())) {
//            $this->websiteHelper->refreshToken($website);
//        }

        $this->websiteHelper->setCurrentWebsite($website);

        return $next($request);
    }

}
