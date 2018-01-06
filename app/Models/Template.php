<?php

declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

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
     * @var string
     */
    protected $table = 'template';

    /**
     * @var array
     */
    protected $fillable = [self::ID, self::DESKTOP_PREVIEW, self::MOBILE_PREVIEW];

    public function getDesktopPreview(): string
    {
        return $this->{self::DESKTOP_PREVIEW};
    }

    public function getId(): int
    {
        return $this->{self::ID};
    }
}