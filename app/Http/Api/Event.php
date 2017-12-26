<?php

namespace App\Http\Api;

use Facebook\GraphNodes\GraphNode;

class Event
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
    public function getName(): string
    {
        return $this->graphNode->getField('name');
    }

}