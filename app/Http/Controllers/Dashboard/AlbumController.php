<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Api\Album;
use App\Http\Api\Photo;
use App\Http\Helpers\AlbumHelper;
use App\Http\Helpers\FacebookHelper;
use App\Http\Helpers\WebsiteHelper;
use App\Model\Template;
use App\Model\Website;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;
use Illuminate\View\View;

/**
 * Class AlbumController
 *
 * @author Laurent Bassin <laurent@bassin.info>
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
     * @var AlbumHelper
     */
    private $albumHelper;

    /**
     * AlbumController constructor.
     * @param FacebookHelper $fbHelper
     * @param WebsiteHelper $websiteHelper
     * @param AlbumHelper $albumHelper
     */
    public function __construct(
        FacebookHelper $fbHelper,
        WebsiteHelper $websiteHelper,
        AlbumHelper $albumHelper
    )
    {
        $this->fbHelper = $fbHelper;
        $this->websiteHelper = $websiteHelper;
        $this->albumHelper = $albumHelper;
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
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function editAction(string $subdomain, string $id): View
    {
        // Todo : Check if album exists and if user is allows to access it

        /** @var Collection $templates */
        $templates = $this->albumHelper->getTemplatesByPage(1);

        /** @var Album $album */
        $album = $this->fbHelper->getAlbum($id);

        return view('dashboard.website.album.edit', [
            'templates' => $templates,
            'album' => $album
        ]);
    }

    /**
     * @param Request $request
     * @return View
     */
    public function templatePreviewAction(Request $request): View
    {
        /** @var string $id */
        $id = $request->post('id');
        /** @var Template $template */
        $template = Template::where(Template::ID, $id)->select(Template::DESKTOP_PREVIEW, Template::MOBILE_PREVIEW)->first();

        return view('dashboard.website.album.templates.preview-modal', ['template' => $template]);
    }

    /**
     * @param Request $request
     * @return View
     */
    public function templatesGridAction(Request $request): View
    {
        /** @var int $page */
        $page = $request->post('page');
        /** @var Collection $templates */
        $templates = $this->albumHelper->getTemplatesByPage($page);

        return view('dashboard.website.album.templates.preview-grid', ['templates' => $templates]);
    }

    /**
     * @param Request $request
     * @param string $subdomain
     * @param int $id
     * @return View
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function imagesGridAction(Request $request, string $subdomain, int $id): View
    {
        /** @var int $page */
        $page = $request->post('page');
        /** @var Album $album */
        $album = $this->fbHelper->getAlbum($id);
        /** @var array $phots */
        $photos = $album->getPhotosByPage($page);

        return view('dashboard.website.album.images.image-grid', ['photos' => $photos]);
    }

    /**
     * @param Request $request
     * @return View
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function imagePreviewAction(Request $request): View
    {
        /** @var string $id */
        $id = $request->post('id');
        /** @var Photo $photo */
        $photo = $this->albumHelper->getPhoto($id);

        return view('dashboard.website.album.images.image-modal', ['photo' => $photo]);
    }

    public function saveAction(Request $request): JsonResponse
    {
        print_r($request->all());

        return response()->json([]);
    }
}