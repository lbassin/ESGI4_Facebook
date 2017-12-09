<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Helpers\FacebookHelper;
use Closure;
use Facebook\Authentication\AccessToken;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\FacebookResponse;
use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class CheckAuthFb
{
    /**
     * @var LaravelFacebookSdk
     */
    private $fb;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var FacebookHelper
     */
    private $fbHelper;


    /**
     * CheckAuthFb constructor.
     * @param LaravelFacebookSdk $fb
     * @param LoggerInterface $logger
     * @param FacebookHelper $fbHelper
     */
    public function __construct(LaravelFacebookSdk $fb, LoggerInterface $logger, FacebookHelper $fbHelper)
    {
        $this->fb = $fb;
        $this->logger = $logger;
        $this->fbHelper = $fbHelper;
    }

    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var string $fbToken */
        $fbToken = $this->getToken($request);

        if (empty($fbToken)) {
            return redirect()->route('home');
        }

        if (!$this->fbHelper->tokenIsValid($fbToken)) {
            $request->session()->flash('redirectTo', $request->path());
            return redirect()->route('fbReAskPermissions');
        }

        $this->fb->setDefaultAccessToken($fbToken);

        if (!$this->checkScope()) {
            $request->session()->flash('redirectTo', $request->path());
            return redirect()->route('fbReAskPermissions');
        }

        return $next($request);
    }

    /**
     * @return bool
     */
    private function checkScope(): bool
    {
        /** @var array $response */
        $permissions = [];

        try {
            /** @var FacebookResponse $response */
            $response = $this->fb->get('/me/permissions');

            if (empty($response->getDecodedBody()['data'])) {
                return false;
            }
            $permissions = $response->getDecodedBody()['data'];
        } catch (FacebookSDKException $ex) {
            $this->logger->error('[FACEBOOK] CheckAuthFb : ' . $ex->getMessage());
            abort(503);
        }

        $fbPermission = [];
        foreach ($permissions as $permission) {
            if (!isset($permission['permission']) || !isset($permission['status'])) {
                continue;
            }

            $fbPermission[$permission['permission']] = $permission['status'];
        }

        foreach (explode(',', $this->fbHelper->getScopes()) as $scope) {
            if (!isset($fbPermission[$scope]) || $fbPermission[$scope] !== 'granted') {
                return false;
            }
        }

        return true;
    }

    /**
     * @param Request $request
     * @return string
     */
    private function getToken(Request $request): string
    {
        /** @var \Illuminate\Session\Store $session */
        $session = $request->session();
        /** @var string $fbToken */
        $fbToken = $session->get(FacebookHelper::FB_TOKEN_KEY);
        /** @var string $fbSdkRequest */
        $fbSdkRequest = $this->fb->getJavaScriptHelper()->getRawSignedRequest();

        if (empty($fbSdkRequest) && empty($fbToken)) {
            return '';
        }

        if (!empty($fbToken)) {
            return $fbToken;
        }

        try {
            /** @var AccessToken $token */
            $authToken = $this->fb->getJavaScriptHelper()->getAccessToken();

            /** @var AccessToken $longLife */
            $longLifeToken = $this->fb->getOAuth2Client()->getLongLivedAccessToken($authToken);
            $fbToken = $longLifeToken->getValue();

            $session->put(FacebookHelper::FB_TOKEN_KEY, $fbToken);
        } catch (FacebookSDKException $ex) {
            $this->logger->error('[FACEBOOK] CheckAuthFb : ' . $ex->getMessage());
            abort(503);
        }

        return $fbToken;
    }
}
