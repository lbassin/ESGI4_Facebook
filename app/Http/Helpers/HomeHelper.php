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
 * Class HomeHelper
 *
 * @author Laurent Bassin <laurent@bassin.info>
 */
class HomeHelper
{

    /**
     * @return Collection
     */
    public function getTemplates(): Collection
    {
        /** @var Collection $templates */
        $templates = Template::where(Template::TYPE, Template::TYPE_MENU)->get();

        return $templates;
    }

    /**
     * @param $page
     * @return array
     */
    public function getTemplatesByPage($page): array
    {
        /** @var Collection $templates */
        $templates = Template::where(Template::TYPE, Template::TYPE_MENU)->get();

        return $templates->forPage($page, Template::PAGINATION_SIZE)->all();
    }
}