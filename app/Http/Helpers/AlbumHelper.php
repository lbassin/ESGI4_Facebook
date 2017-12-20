<?php

declare(strict_types=1);

namespace App\Http\Helpers;

use Facebook\GraphNodes\GraphNode;

/**
 * Class AlbumHelper
 * @package App\Http\Helpers
 */
class AlbumHelper
{
    /** @var int $randomPictureReturned */
    private $randomPictureReturned = 3;

    /**
     * @param GraphNode $album
     * @return array
     */
    public function getRandomPicturesOfAlbum(GraphNode $album)
    {
        if (empty($album->getField('photos'))) {
            return [];
        }

        /** @var int $photoCount */
        $photoCount = count($album->getField('photos'));
        /** @var array $photos */
        $photos = [];

        if ($photoCount <= $this->randomPictureReturned) {
            /** @var GraphNode $photo */
            foreach ($album->getField('photos')->all() as $photo) {
                /** @var string $url */
                $url = $photo->getField('picture');
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
            if (empty($album->getField('photos')[$index]['picture'])) {
                continue;
            }

            $photos[] = $album->getField('photos')[$index]['picture'];
        }

        return $photos;
    }
}