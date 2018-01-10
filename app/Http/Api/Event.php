<?php

namespace App\Http\Api;

use App\Http\Helpers\WebsiteHelper;
use Facebook\GraphNodes\GraphEdge;
use Facebook\GraphNodes\GraphNode;

/**
 * Class Event
 *
 * @author Laurent Bassin <laurent@bassin.info>
 */
class Event
{
    /**
     * @var GraphNode
     */
    private $graphNode;
    /**
     * @var \App\Model\Event
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
     * @return int
     */
    public function getId(): int
    {
        return $this->graphNode->getField('id');
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
    public function getStartDate(): string
    {
        /** @var \DateTime $date */
        $date = $this->graphNode->getField('start_time');

        return $date->format(WebsiteHelper::DATE_FORMAT);
    }

    /**
     * @return string
     */
    public function getEndDate(): string
    {
        /** @var \DateTime $date */
        $date = $this->graphNode->getField('end_time');

        return $date->format(WebsiteHelper::DATE_FORMAT);
    }

    /**
     * @return string
     */
    public function getPlaceName(): string
    {
        /** @var GraphEdge $place */
        $place = $this->graphNode->getField('place');

        if (empty($place)) {
            return '';
        }

        return $place->getField('name', '');
    }

    /**
     * @return string
     */
    public function getCover(): string
    {
        /** @var GraphEdge $cover */
        $cover = $this->graphNode->getField('cover');

        if (empty($cover)) {
            return '';
        }

        return $cover->getField('source', '');
    }

    /**
     * @param $databaseEvent
     */
    public function setModel($databaseEvent): void
    {
        $this->model = $databaseEvent;
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


}