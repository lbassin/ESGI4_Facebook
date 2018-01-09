<?php

namespace App\Http\Api;

use App\Http\Helpers\WebsiteHelper;
use Facebook\GraphNodes\GraphEdge;
use Facebook\GraphNodes\GraphNode;

/**
 * Class Review
 *
 * @author Laurent Bassin <laurent@bassin.info>
 */
class Review
{
    /**
     * @var GraphNode
     */
    private $graphNode;
    /**
     * @var \App\Model\Review
     */
    private $model;

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
    public function getId(): string
    {
        return $this->graphNode->getField('source_id') . '.' . $this->graphNode->getField('reviewer')->getField('id');
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

        return $date->format(WebsiteHelper::DATE_FORMAT);
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        if (!$this->model) {
            return true;
        }

        return $this->model->isVisible();
    }

    /**
     * @param $databaseReview
     */
    public function setModel(\App\Model\Review $databaseReview): void
    {
        $this->model = $databaseReview;
    }

    public function getReviewerId(): int
    {
        /** @var GraphEdge $reviewer */
        $reviewer = $this->graphNode->getField('reviewer');

        if (empty($reviewer)) {
            return '';
        }

        return $reviewer->getField('id', '');
    }

}