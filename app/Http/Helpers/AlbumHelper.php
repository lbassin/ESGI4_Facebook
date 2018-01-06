<?php

declare(strict_types=1);

namespace App\Http\Helpers;

/**
 * Class AlbumHelper
 *
 * @author Laurent Bassin <laurent@bassin.info>
 */
class AlbumHelper
{

    /**
     * @return array
     */
    public function getTemplates()
    {
        return [
            [
                'id' => 1,
                'image' => 'http://via.placeholder.com/350x150'
            ],
            [
                'id' => 2,
                'image' => 'http://via.placeholder.com/350x150'
            ],
            [
                'id' => 3,
                'image' => 'http://via.placeholder.com/350x150'
            ]
        ];
    }
}