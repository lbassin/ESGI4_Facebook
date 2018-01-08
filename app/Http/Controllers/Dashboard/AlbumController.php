<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Api\Album as AlbumApi;
use App\Http\Api\Photo;
use App\Http\Helpers\AlbumHelper;
use App\Http\Helpers\FacebookHelper;
use App\Http\Helpers\WebsiteHelper;
use App\Model\Album;
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
        /** @var AlbumApi $album */
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

        /** @var AlbumApi $album */
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
        /** @var AlbumApi $album */
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

    public function saveAction(Request $request, string $subdomain, int $id): JsonResponse
    {
        /** @var Album $album */
        $album = Album::where(Album::ID, $id)->first();
        if (empty($album)) {
            $album = new Album([Album::ID => $id]);
        }

        /** @var array $options */
        $options = [];
        foreach ($request->post('options') as $option) {
            if (empty($option['name']) || empty($option['value'])) {
                continue;
            }

            $options[$option['name']] = $option['value'];
        }

        /** @var array $data */
        $data = [
            Album::TEMPLATE_ID => $request->post('template'),
            Album::TITLE => isset($options['title']) ? $options['title'] : '',
            Album::DESCRIPTION => isset($options['description']) ? $options['description'] : '',
            Album::URL => isset($options['url']) ? $options['url'] : '',
            Album::HIDE_NEW => !empty($options['hide_new'])
        ];

        $album->fill($data);
        $album->save();

        return response()->json([]);
    }
}