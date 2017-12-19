<?php

declare(strict_types=1);

namespace App\Http\Helpers;

use App\Model\Website;
use Illuminate\Support\Collection;

/**
 * Class UserHelper
 * @package App\Http\Helpers
 */
class UserHelper
{
    /**
     * @var FacebookHelper
     */
    private $fbHelper;

    /**
     * UserHelper constructor.
     * @param FacebookHelper $fbHelper
     */
    public function __construct(FacebookHelper $fbHelper)
    {
        $this->fbHelper = $fbHelper;
    }

    /**
     * @return array
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function getWebsites(): array
    {
        /** @var Collection $websites */
        $websites = Website::where(Website::USER_ID, $this->fbHelper->getUserId())->get();
        /** @var array $output */
        $output = [];

        /** @var Website $website */
        foreach ($websites->all() as $website) {
            $output[] = $website->toArray();
        }

        return $output;
    }

}