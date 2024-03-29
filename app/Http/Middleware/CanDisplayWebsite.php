<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Model\Website;
use Closure;
use Illuminate\Http\Request;

/**
 * Class WebsiteExists
 * @package App\Http\Middleware
 */
class CanDisplayWebsite
{

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

        return $next($request);
    }

}
