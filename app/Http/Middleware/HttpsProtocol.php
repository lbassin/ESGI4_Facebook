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
 * Class HttpsProtocol
 *
 * @author Laurent Bassin <laurent@bassin.info>
 */
class HttpsProtocol
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
        if (env('APP_ENV') != 'prod') {
            return $next($request);
        }

        /** @var string $protocol */
        $protocol = $request->getScheme();
        if (!$request->secure()) {
            /** @var string $secureUrl */
            $secureUrl = url($request->path(), [], true);
            return response()->redirectTo($secureUrl);
        }

        return $next($request);
    }

}
