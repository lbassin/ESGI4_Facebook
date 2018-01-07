<?php

declare(strict_types=1);

namespace App\Http\Helpers;

use App\Http\Api\Photo;
use App\Model\Template;
use Facebook\GraphNodes\GraphEdge;
use Facebook\GraphNodes\GraphNode;
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

    public function __construct(LaravelFacebookSdk $fb)
    {
        $this->fb = $fb;
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
}