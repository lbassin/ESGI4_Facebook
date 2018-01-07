<?php

declare(strict_types=1);

namespace App\Http\Helpers;

use App\Model\Template;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Class AlbumHelper
 *
 * @author Laurent Bassin <laurent@bassin.info>
 */
class AlbumHelper
{

    /**
     * @return Collection
     */
    public function getTemplates(): Collection
    {
        /** @var Collection $templates */
        $templates = Template::all();

        return $templates;
    }

    public function getTemplatesByPage($page)
    {
        /** @var Collection $templates */
        $templates = Template::all();

        return $templates->forPage($page, 9)->all();
    }
}