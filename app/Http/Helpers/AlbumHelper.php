<?php

declare(strict_types=1);

namespace App\Http\Helpers;

use App\Http\Api\Photo;
use App\Model\Album;
use App\Model\Template;
use App\Model\Website;
use Facebook\FacebookResponse;
use Facebook\GraphNodes\GraphNode;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

/**
 * Class AlbumHelper
 *
 * @author Laurent Bassin <laurent@bassin.info>
 */
class AlbumHelper
{

    /**
     * @var LaravelFacebookSdk
     */
    private $fb;
    /**
     * @var WebsiteHelper
     */
    private $websiteHelper;
    /**
     * @var FacebookHelper
     */
    private $fbHelper;

    /**
     * AlbumHelper constructor.
     * @param LaravelFacebookSdk $fb
     * @param WebsiteHelper $websiteHelper
     * @param FacebookHelper $fbHelper
     */
    public function __construct(
        LaravelFacebookSdk $fb,
        WebsiteHelper $websiteHelper,
        FacebookHelper $fbHelper
    )
    {
        $this->fb = $fb;
        $this->websiteHelper = $websiteHelper;
        $this->fbHelper = $fbHelper;
    }

    /**
     * @return Collection
     */
    public function getTemplates(): Collection
    {
        /** @var Collection $templates */
        $templates = Template::all();

        return $templates;
    }

    /**
     * @param $page
     * @return array
     */
    public function getTemplatesByPage($page): array
    {
        /** @var Collection $templates */
        $templates = Template::all();

        return $templates->forPage($page, 9)->all();
    }

    /**
     * @param int $id
     * @return Photo
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function getPhoto(int $id): Photo
    {
        /** @var string $query */
        $query = $id . '?fields=id,name,images';
        /** @var GraphNode $graph */
        $graph = $this->fb->get($query)->getGraphNode();

        return new Photo($graph);
    }

    /**
     * @param Photo $photo
     * @return Photo
     */
    public function fillPhotoFromDatabase(Photo $photo): Photo
    {
        /** @var \App\Model\Photo $databasePhoto */
        $databasePhoto = \App\Model\Photo::where(\App\Model\Photo::ID, $photo->getId())->first();

        if (empty($databasePhoto)) {
            return $photo;
        }
        $photo->setModel($databasePhoto);

        return $photo;
    }

    /**
     * @param Photo $photo
     * @return bool
     */
    public function getDefaultVisibility(Photo $photo): bool
    {
        /** @var Album $album */
        $album = Album::where(Album::ID, $photo->getAlbumId())->first();
        if (empty($album)) {
            return true;
        }

        return $album->shouldShowNew();
    }


    /**
     * @param $albumId
     * @param $imageData
     * @return bool
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function uploadPhoto($albumId, $imageData): bool
    {
        if (empty($imageData['description']) || empty($imageData['image'])) {
            return false;
        }

        /** @var Website $website */
        $website = $this->websiteHelper->getCurrentWebsite();
        /** @var string $url */
        $url = $albumId . '/photos';
        /** @var UploadedFile $image */
        $image = $imageData['image'];
        /** @var array $fbData */
        $fbData = [
            'source' => $this->fb->fileToUpload($image->path()),
            'message' => $imageData['description']
        ];

        $this->fb->post($url, $fbData, $website->getAccessToken());

        return true;
    }


    /**
     * @param Album $album
     * @return array
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function getVisiblePhotos(Album $album): array
    {
        /** @var \App\Http\Api\Album $albumApi */
        $albumApi = $this->fbHelper->getAlbum((string)$album->getId());
        /** @var array $photos */
        $photos = $albumApi->getPhotos();

        $photos = array_filter($photos, function ($photo) {
            /** @var Photo $photo */
            return $photo->isVisible();
        });

        return $photos;
    }
}