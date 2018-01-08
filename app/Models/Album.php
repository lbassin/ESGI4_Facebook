<?php

declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Album
 *
 * @author Laurent Bassin <laurent@bassin.info>
 */
class Album extends Model
{
    /**
     *
     */
    const ID = 'id';
    /**
     *
     */
    const TEMPLATE_ID = 'template_id';
    /**
     *
     */
    const TITLE = 'title';
    /**
     *
     */
    const DESCRIPTION = 'description';
    /**
     *
     */
    const URL = 'url';
    /**
     *
     */
    const HIDE_NEW = 'hide_new';

    /**
     * @var string
     */
    protected $table = 'album';

    /**
     * @var array
     */
    protected $fillable = [
        self::ID,
        self::TEMPLATE_ID,
        self::TITLE,
        self::DESCRIPTION,
        self::URL,
        self::HIDE_NEW
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->{self::ID};
    }
}