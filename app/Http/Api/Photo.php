<?php

namespace App\Http\Api;

use Facebook\GraphNodes\GraphNode;

class Photo
{
    /**
     *
     */
    const SIZE_SMALL = 'small';
    /**
     *
     */
    const SIZE_MEDIUM = 'medium';
    /**
     *
     */
    const SIZE_LARGE = 'large';

    /**
     * @var GraphNode
     */
    private $graphNode;

    /**
     * Album constructor.
     * @param GraphNode $graphNode
     */
    public function __construct(GraphNode $graphNode)
    {
        $this->graphNode = $graphNode;
    }

    /**
     * @param string $size
     * @return string
     */
    public function getLink(string $size): string
    {
        /** @var GraphNode $images */
        $images = $this->graphNode->getField('images');
        if (empty($images)) {
            return '';
        }

        /** @var array $availableHeight */
        $availableHeight = [];

        /** @var GraphNode $image */
        foreach ($images->all() as $image) {
            /** @var int $height */
            $height = $image->getField('height');

            $availableHeight[$height] = $image->getField('source');
        }

        ksort($availableHeight);

        if($size == Photo::SIZE_SMALL){
            return reset($availableHeight);
        }

        if($size == Photo::SIZE_MEDIUM){
            throw new \Exception('Not implemented yet');
        }

        if($size == Photo::SIZE_LARGE){
            return end($availableHeight);
        }

        return '';
    }

}