<?php

declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Template
 *
 * @author Laurent Bassin <laurent@bassin.info>
 */
class Template extends Model
{
    /**
     *
     */
    const ID = 'id';
    /**
     *
     */
    const DESKTOP_PREVIEW = 'desktop_preview';
    /**
     *
     */
    const MOBILE_PREVIEW = 'mobile_preview';
    /**
     *
     */
    const PAGINATION_SIZE = 9;

    /**
     * @var string
     */
    protected $table = 'template';

    /**
     * @var array
     */
    protected $fillable = [self::ID, self::DESKTOP_PREVIEW, self::MOBILE_PREVIEW];

    /**
     * @return string
     */
    public function getDesktopPreview(): string
    {
        return $this->{self::DESKTOP_PREVIEW};
    }

    /**
     * @return string
     */
    public function getMobilePreview(): string
    {
        return $this->{self::MOBILE_PREVIEW};
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->{self::ID};
    }
}