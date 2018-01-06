<?php

declare(strict_types=1);

namespace App\Http\Helpers;

use App\Model\Template;
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
}