<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Api\Album as AlbumApi;
use App\Http\Api\Photo as PhotoApi;
use App\Http\Helpers\AlbumHelper;
use App\Http\Helpers\FacebookHelper;
use App\Http\Helpers\WebsiteHelper;
use App\Model\Album;
use App\Model\Photo;
use App\Model\Template;
use App\Model\Website;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
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

        if (empty($name)) {
            $response = [
                'error' => true,
                'message' => 'Le nom de l\'album ne doit pas être vide'
            ];

            return response()->json($response);
        }

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

        return response()->json([
                'message' => 'Album créé',
                'url' => route('dashboard.website.albums.edit', $routeParams)]
        );
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
        /** @var string $templateId */
        $templateId = '';
        /** @var array $configuration */
        $configuration = [];
        /** @var Album $albumModel */
        $albumModel = Album::where(Album::ID, $id)->first();
        if (!empty($albumModel)) {
            $templateId = (string)$albumModel->getTemplateId();

            $configuration = [
                Album::TITLE => $albumModel->getTitle(),
                Album::DESCRIPTION => $albumModel->getDescription(),
                Album::URL => $albumModel->getUrl(),
                Album::HIDE_NEW => $albumModel->getHideNew(),
                Album::VISIBLE => $albumModel->isVisible()
            ];
        }

        return view('dashboard.website.album.edit', [
            'templates' => $templates,
            'album' => $album,
            'templateId' => $templateId,
            'config' => $configuration
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
     * @param $subdomain
     * @param $id
     * @return View
     */
    public function templatesGridAction(Request $request, $subdomain, $id): View
    {
        /** @var int $page */
        $page = $request->post('page');
        /** @var array $templates */
        $templates = $this->albumHelper->getTemplatesByPage($page);
        /** @var string $templateId */
        $templateId = '';
        /** @var bool $hideControls */
        $hideControls = $page == 1 && count($templates) < Album::PAGINATION_SIZE;
        /** @var bool $nextDisabled */
        $nextDisabled = count($templates) < Album::PAGINATION_SIZE;

        if ($request->post('templateId')) {
            $templateId = $request->post('templateId');
        } else {
            /** @var Album $album */
            $album = Album::where(Album::ID, $id)->first();
            if (!empty($album)) {
                $templateId = (string)$album->getTemplateId();
            }
        }

        return view('dashboard.website.album.templates.preview-grid', [
            'templates' => $templates,
            'selectedTemplate' => $templateId,
            'hideControls' => $hideControls,
            'nextDisabled' => $nextDisabled
        ]);
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
        /** @var array $photos */
        $photos = $album->getPhotosByPage($page);
        /** @var bool $hideControls */
        $hideControls = $page == 1 && count($photos) < Album::PAGINATION_SIZE;
        /** @var bool $nextDisabled */
        $nextDisabled = count($photos) < Album::PAGINATION_SIZE;

        return view('dashboard.website.album.images.image-grid', [
            'photos' => $photos,
            'hideControls' => $hideControls,
            'nextDisabled' => $nextDisabled
        ]);
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
        /** @var PhotoApi $photo */
        $photo = $this->albumHelper->getPhoto($id);

        return view('dashboard.website.album.images.image-modal', ['photo' => $photo]);
    }

    /**
     * @param Request $request
     * @param string $subdomain
     * @param int $id
     * @return JsonResponse
     */
    public function saveAction(Request $request, string $subdomain, int $id): JsonResponse
    {
        /** @var Website $website */
        $website = $this->websiteHelper->getCurrentWebsite();

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

        /** @var array $albumData */
        $albumData = [
            Album::TEMPLATE_ID => $request->post('template'),
            Album::TITLE => isset($options['title']) ? $options['title'] : '',
            Album::DESCRIPTION => isset($options['description']) ? $options['description'] : '',
            Album::URL => isset($options['url']) ? $options['url'] : '',
            Album::HIDE_NEW => !empty($options['hide_new']),
            Album::WEBSITE_ID => $website->getId(),
            Album::VISIBLE => !empty($options['visible'])
        ];

        $album->fill($albumData);
        $album->save();

        /** @var array $images */
        $images = $request->post('images') ?: [];

        foreach ($images as $photoId => $photoData) {
            /** @var Photo $photo */
            $photo = Photo::where(Photo::ID, $photoId)->first();
            if (empty($photo)) {
                $photo = new Photo([Photo::ID => $photoId]);
            }

            $photoData[Photo::VISIBLE] = !empty($photoData['visible']) && $photoData['visible'] == 'true';
            $photoData[Photo::ALBUM_ID] = $id;

            $photo->fill($photoData);

            $photo->save();
        }

        return response()->json([
            'message' => 'Album sauvegardé',
            'url' => route('dashboard.website.albums', ['subdomain' => $subdomain])
        ]);
    }

    /**
     * @param Request $request
     * @param string $subdomain
     * @param int $albumId
     * @return JsonResponse
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function uploadAction(Request $request, string $subdomain, int $albumId): JsonResponse
    {
        /** @var array $formData */
        $description = $request->post('upload-description');
        /** @var ? $image */
        $image = $request->file('upload-image');
        /** @var bool $uploaded */
        $uploaded = $this->albumHelper->uploadPhoto($albumId, ['description' => $description, 'image' => $image]);

        if (!$uploaded) {
            return response()->json([
                'error' => true,
                'message' => 'An error occurred'
            ]);
        }

        return response()->json([
            'message' => 'Image ajoutée à l\'album',
        ]);
    }
}