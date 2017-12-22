<?php

namespace App\Http\Api;

use Facebook\GraphNodes\GraphNode;

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
     * Album constructor.
     * @param GraphNode $graphNode
     */
    public function __construct(GraphNode $graphNode)
    {
        $this->graphNode = $graphNode;
    }

    /**
     * @return array
     */
    public function getPhotos()
    {
        /** @var  $photos */
        $photosApi = $this->graphNode->getField('photos');

        $photos = [];
        foreach ($photosApi as $photo){
            $photos[] = new Photo($photo);
        }

        return $photos;
    }

    /**
     * @param string $pictureSize
     * @return array
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

}