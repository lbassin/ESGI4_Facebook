<?php

declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Event
 *
 * @author Laurent Bassin <laurent@bassin.info>
 */
class Event extends Model
{
    /**
     *
     */
    const ID = 'id';
    /**
     *
     */
    const VISIBLE = 'is_visible';

    /**
     * @var string
     */
    protected $table = 'event';

    /**
     * @var array
     */
    protected $fillable = [
        self::ID,
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