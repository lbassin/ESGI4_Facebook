<?php

namespace App\Http\Helpers;

use Illuminate\Session\Store;

class FacebookHelper
{
    /**
     *
     */
    const FB_TOKEN_KEY = 'fb_token';

    /**
     *
     */
    const FB_SCOPES = 'public_profile,email';

    /**
     * @var Store
     */
    private $session;

    /**
     * FacebookHelper constructor.
     * @param Store $session
     */
    function __construct(Store $session)
    {
        $this->session = $session;
    }

    /**
     * @return string
     */
    public function getScopes()
    {
        return FacebookHelper::FB_SCOPES;
    }

    /**
     *
     */
    public function getToken()
    {
        /** @var string $fbToken */
        return $this->session->get(FacebookHelper::FB_TOKEN_KEY);

    }

}