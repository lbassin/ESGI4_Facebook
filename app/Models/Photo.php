<?php

declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Photo
 *
 * @author Laurent Bassin <laurent@bassin.info>
 */
class Photo extends Model
{
    /**
     *
     */
    const ID = 'id';
    /**
     *
     */
    const ALBUM_ID = 'album_id';
    /**
     *
     */
    const VISIBLE = 'is_visible';

    /**
     * @var string
     */
    protected $table = 'photo';

    /**
     * @var array
     */
    protected $fillable = [
        self::ID,
        self::ALBUM_ID,
        self::VISIBLE,
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->{self::ID};
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        return (bool)$this->{self::VISIBLE};
    }
}