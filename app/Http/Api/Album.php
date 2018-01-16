<?php

namespace App\Http\Api;

use App\Http\Helpers\AlbumHelper;
use Facebook\GraphNodes\GraphEdge;
use Facebook\GraphNodes\GraphNode;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

/**
 * Class Album
 *
 * @author Laurent Bassin <laurent@bassin.info>
 */
class Album
{
    /**
     * @var GraphNode
     */
    private $graphNode;

    /**
     * @var int
     */
    private $randomPictureReturned = 3;
    /**
     * @var LaravelFacebookSdk
     */
    private $fb;
    /**
     * @var AlbumHelper
     */
    private $albumHelper;
    /**
     * @var \App\Model\Album
     */
    private $model;

    /**
     * Album constructor.
     * @param GraphNode $graphNode
     */
    public function __construct(
        GraphNode $graphNode
    )
    {
        $this->graphNode = $graphNode;
        $this->fb = App()->make(LaravelFacebookSdk::class);
        $this->albumHelper = App()->make(AlbumHelper::class);
    }

    /**
     * @return string
     */
    private function getPhotosGraphWithoutLimit(): string
    {
        /** @var string $query */
        return $this->getId() . '/photos?fields=id,name,images,album{id}';
    }

    /**
     * @return array
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function getPhotos(): array
    {
        /** @var string $query */
        $query = $this->getPhotosGraphWithoutLimit();
        /** @var GraphEdge $graph */
        $graph = $this->fb->get($query)->getGraphEdge();

        /** @var array $photos */
        $photos = [];
        foreach ($graph->all() as $photo) {
            $apiPhoto = new Photo($photo);
            $photos[] = $this->albumHelper->fillPhotoFromDatabase($apiPhoto);
        }


        return $photos;
    }

    /**
     * @param int $page
     * @return array
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function getPhotosByPage(int $page): array
    {
        /** @var string $query */
        $query = $this->getPhotosGraphWithoutLimit() . '&limit=9';
        /** @var GraphEdge $graph */
        $graph = $this->fb->get($query)->getGraphEdge();

        // Omg that's beautiful •ᴗ•
        for ($i = 1; $i < $page; $i++) {
            $graph = $this->fb->next($graph);
        }

        if (empty($graph)) {
            return [];
        }

        /** @var array $photos */
        $photos = [];
        foreach ($graph->all() as $photo) {
            $apiPhoto = new Photo($photo);
            $photos[] = $this->albumHelper->fillPhotoFromDatabase($apiPhoto);
        }

        return $photos;
    }

    /**
     * @param string $pictureSize
     * @return array
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function getPreview(string $pictureSize): array
    {
        if (empty($this->getPhotos())) {
            return [];
        }

        /** @var int $photoCount */
        $photoCount = count($this->getPhotos());
        /** @var array $photos */
        $photos = [];

        if ($photoCount <= $this->randomPictureReturned) {
            /** @var Photo $photo */
            foreach ($this->getPhotos() as $photo) {
                /** @var string $url */
                $url = $photo->getLink($pictureSize);
                if (empty($url)) {
                    continue;
                }

                $photos[] = $url;
            }

            return $photos;
        }

        /** @var array $randomIndex */
        $randomIndex = [];
        for ($i = 0; $i < $this->randomPictureReturned; $i++) {
            /** @var int $index */
            $index = rand(0, $photoCount - 1);

            if (in_array($index, $randomIndex)) {
                $index = rand(0, $photoCount - 1);
            }

            $randomIndex[] = $index;
        }

        /** @var int $index */
        foreach ($randomIndex as $index) {
            /** @var Photo $photo */
            $photo = $this->getPhotos()[$index];
            if (empty($photo->getLink($pictureSize))) {
                continue;
            }

            $photos[] = $photo->getLink($pictureSize);
        }

        return $photos;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->graphNode->getField('name');
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->graphNode->getField('description', '');
    }

    /**
     * @return string
     */
    public function getCover(): string
    {
        /** @var GraphEdge $cover */
        $cover = $this->graphNode->getField('cover_photo');

        if (empty($cover)) {
            return '';
        }

        /** @var GraphEdge $images */
        $coverImages = $cover->getField('images');

        if (empty($coverImages)) {
            return '';
        }

        /** @var array $images */
        $images = $coverImages->asArray();

        usort($images, function ($image1, $image2) {
            if (!isset($image1['width']) || !isset($image2['width'])) {
                return 0;
            }

            return $image1['width'] - $image2['width'];
        });

        $image = end($images);

        if (!isset($image['source'])) {
            return '';
        }

        return $image['source'];
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->graphNode->getField('id', '');
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        if (!$this->model) {
            return '';
        }

        return $this->model->getUrl();
    }

    /**
     * @param \App\Model\Album $album
     */
    public function setModel(\App\Model\Album $album): void
    {
        $this->model = $album;
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        if (!$this->model) {
            return false;
        }

        return $this->model->isVisible();
    }

}