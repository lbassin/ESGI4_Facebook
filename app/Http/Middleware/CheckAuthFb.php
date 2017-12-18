<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Helpers\FacebookHelper;
use Closure;
use Facebook\Authentication\AccessToken;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\FacebookResponse;
use Illuminate\Http\Request;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

/**
 * Class CheckAuthFb
 * @package App\Http\Middleware
 */
class CheckAuthFb
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
     * CheckAuthFb constructor.
     * @param LaravelFacebookSdk $fb
     * @param FacebookHelper $fbHelper
     */
    public function __construct(LaravelFacebookSdk $fb, FacebookHelper $fbHelper)
    {
        $this->fb = $fb;
        $this->fbHelper = $fbHelper;
    }

    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     * @throws FacebookSDKException
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var string $fbToken */
        $fbToken = null;
        try{
            $fbToken = $this->getToken($request);
        }catch (FacebookSDKException $exception){
            $request->session()->flash('redirectTo', $request->path());
            return redirect()->route('fbReAskPermissions');
        }

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

        $request->session()->put(FacebookHelper::FB_TOKEN_KEY, $fbToken);

        return $next($request);
    }

    /**
     * @return bool
     * @throws FacebookSDKException
     */
    private function checkScope(): bool
    {
        /** @var FacebookResponse $response */
        $response = $this->fb->get('/me/permissions');

        if (empty($response->getDecodedBody()['data'])) {
            return false;
        }
        /** @var array $response */
        $permissions = $response->getDecodedBody()['data'];

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
     * @throws FacebookSDKException
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

        /** @var AccessToken $token */
        $authToken = $this->fb->getJavaScriptHelper()->getAccessToken();

        /** @var AccessToken $longLife */
        $longLifeToken = $this->fb->getOAuth2Client()->getLongLivedAccessToken($authToken);
        $fbToken = $longLifeToken->getValue();

        return $fbToken;
    }
}
