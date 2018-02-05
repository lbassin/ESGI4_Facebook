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
 * Class HttpProtocol
 *
 * @author Laurent Bassin <laurent@bassin.info>
 */
class HttpProtocol
{

    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // This middleware fix https issues
        // It could be remove after the installation of https wildcard
        if (env('APP_ENV') != 'production') {
            return $next($request);
        }

        if ($request->secure()) {
            /** @var string $unsecureUrl */
            $unsecureUrl = url($request->path(), [], false);
            return response()->redirectTo($unsecureUrl);
        }

        return $next($request);
    }

}
