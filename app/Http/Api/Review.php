<?php

namespace App\Http\Api;

use Facebook\GraphNodes\GraphEdge;
use Facebook\GraphNodes\GraphNode;

class Review
{
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
     * @return string
     */
    public function getText(): string
    {
        return $this->graphNode->getField('review_text');
    }

    /**
     * @return string
     */
    public function getReviewerName(): string
    {
        /** @var GraphEdge $reviewer */
        $reviewer = $this->graphNode->getField('reviewer');

        if (empty($reviewer)) {
            return '';
        }

        return $reviewer->getField('name', '');
    }

    /**
     * @return string
     */
    public function getReviewerPicture(): string
    {
        /** @var GraphEdge $reviewer */
        $reviewer = $this->graphNode->getField('reviewer');

        if (empty($reviewer)) {
            return '';
        }

        /** @var GraphEdge $picture */
        $picture = $reviewer->getField('picture');

        return $picture->getField('url', '');
    }

    /**
     * @return int
     */
    public function getRating(): int
    {
        return $this->graphNode->getField('rating', 0);
    }

    /**
     * @return string
     */
    public function getCreatedTime(): string
    {
        /** @var \DateTime $date */
        $date = $this->graphNode->getField('created_time', '');

        return $date->format('d/m/Y - H:i');
    }

}