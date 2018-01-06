<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Api\Album;
use App\Http\Helpers\FacebookHelper;
use App\Http\Helpers\WebsiteHelper;
use App\Model\Website;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

/**
 * Class WebsiteController
 * @package App\Http\Controllers\Dashboard
 */
class AlbumController extends BaseController
{
    /**
     * @var FacebookHelper
     */
    private $fbHelper;
    /**
     * @var WebsiteHelper
     */
    private $websiteHelper;

    /**
     * AlbumController constructor.
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
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request): JsonResponse
    {
        /** @var string $name */
        $name = $request->input('name');
        /** @var Website $website */
        $website = $this->websiteHelper->getCurrentWebsite();
        /** @var Album $album */
        $album = null;

        try {
            $album = $this->fbHelper->createAlbum($name, $website);
        } catch (\Exception $e) {
            $response = [
                'error' => true,
                'message' => 'An error occured'
            ];

            return response()->json($response);
        }

        /** @var array $routeParams */
        $routeParams = [
            'subdomain' => $website->getSubDomain(),
            'id' => $album->getId()
        ];

        return response()->json(['url' => route('dashboard.website.albums.edit', $routeParams)]);
    }

    /**
     * @param string $subdomain
     * @param string $id
     * @return View
     */
    public function editAction(string $subdomain, string $id): View
    {
        // Todo : Check if album exists and if user is allows to access it

        print_r([
            $subdomain,
            $id
        ]);

        die('Gestion album');
    }
}