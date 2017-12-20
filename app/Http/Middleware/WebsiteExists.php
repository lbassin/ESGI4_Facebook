<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Model\Website;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class WebsiteExists
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
        /** @var bool $websites */
        $websiteExists = (bool)Website::where(Website::SUBDOMAIN, $subdomain)->count();

        if (!$websiteExists) {
            abort(404);
        }

        return $next($request);
    }

}
