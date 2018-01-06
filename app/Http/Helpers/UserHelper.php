<?php

declare(strict_types=1);

namespace App\Http\Helpers;

use App\Model\Website;
use Facebook\Exceptions\FacebookSDKException;
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
    private $websiteHelper;

    /**
     * UserHelper constructor.
     * @param FacebookHelper $fbHelper
     * @param WebsiteHelper $websiteHelper
     */
    public function __construct(
        FacebookHelper $fbHelper,
        WebsiteHelper $websiteHelper
    )
    {
        $this->fbHelper = $fbHelper;
        $this->websiteHelper = $websiteHelper;
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

    /**
     * @return string
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function getPicture(): string
    {
        return $this->fbHelper->getUserPhoto();
    }

    /**
     * @return string
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function getName(): string
    {
        return $this->fbHelper->getUserName();
    }

    /**
     * @return array
     * @throws FacebookSDKException
     */
    public function getAvailablePages(): array
    {
        $pages = $this->fbHelper->getPages();

        $availablePages = [];
        foreach ($pages as $page) {
            if(!isset($page['id'])){
                continue;
            }

            if(!$this->websiteHelper->isCreated($page['id'])){
                $availablePages[] = $page;
            }
        }

        return $availablePages;
    }


}