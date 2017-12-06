<?php

namespace App\Http\Middleware;

use Closure;
use Facebook\Authentication\AccessToken;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class CheckAuthFb
{
    /**
     *
     */
    const FB_TOKEN_KEY = 'fb_token';

    /**
     * @var LaravelFacebookSdk
     */
    private $fb;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * CheckAuthFb constructor.
     * @param LaravelFacebookSdk $fb
     */
    public function __construct(LaravelFacebookSdk $fb, LoggerInterface $logger)
    {
        $this->fb = $fb;
        $this->logger = $logger;
    }

    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var \Illuminate\Session\Store $session */
        $session = $request->session();
        /** @var string $fbToken */
        $fbToken = $session->get(self::FB_TOKEN_KEY);
        /** @var string $fbSdkRequest */
        $fbSdkRequest = $this->fb->getJavaScriptHelper()->getRawSignedRequest();

        if (empty($fbSdkRequest)) {
            return $next($request);
        }

        if (!empty($fbToken)) {
            $this->fb->setDefaultAccessToken($fbToken);

            return $next($request);
        }

        try {
            /** @var AccessToken $token */
            $authToken = $this->fb->getJavaScriptHelper()->getAccessToken();

            /** @var AccessToken $longLife */
            $longLifeToken = $this->fb->getOAuth2Client()->getLongLivedAccessToken($authToken);

            $session->put(self::FB_TOKEN_KEY, $longLifeToken->getValue());
        } catch (FacebookSDKException $ex) {
            $this->logger->error('[FACEBOOK] CheckAuthFb : ' . $ex->getMessage());
            abort(503);
        }

        return $next($request);
    }
}
