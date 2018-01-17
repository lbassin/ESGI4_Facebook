<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Helpers\FacebookHelper;
use Closure;
use Facebook\Authentication\AccessToken;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\FacebookResponse;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

/**
 * Class AuthAdmin
 *
 * @author Laurent Bassin <laurent@bassin.info>
 */
class AuthAdmin
{

    /**
     * @var Store
     */
    private $session;

    /**
     * AuthAdmin constructor.
     * @param Store $session
     */
    public function __construct(
        Store $session
    )
    {
        $this->session = $session;
    }

    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var string $fbToken */
        $fbToken = $this->session->get(FacebookHelper::FB_TOKEN_KEY);

        if (empty($fbToken)) {
            abort(404);
        }

        return $next($request);
    }
}
