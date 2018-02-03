<?php

declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class HomeBlock
 *
 * @author Laurent Bassin <laurent@bassin.info>
 */
class HomeBlock extends Model
{
    /**
     *
     */
    const ID = 'id';
    /**
     *
     */
    const LABEL = 'label';
    /**
     *
     */
    const PREVIEW = 'preview';
    /**
     *
     */
    const CONFIG_FILE = 'config_file';
    /**
     *
     */
    const VIEW_FILE = 'view_file';
    /**
     *
     */
    const SVG_PREVIEW = 'svg_preview';

    /**
     * @var string
     */
    protected $table = 'home_block';

    /**
     * @var array
     */
    protected $fillable = [self::ID, self::LABEL, self::PREVIEW, self::CONFIG_FILE, self::VIEW_FILE];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->{self::ID};
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->{self::LABEL};
    }

    /**
     * @return string
     */
    public function getPreview(): string
    {
        return $this->{self::PREVIEW};
    }

    /**
     * @return string
     */
    public function getConfigPath(): string
    {
        return $this->{self::CONFIG_FILE};
    }

    /**
     * @return string
     */
    public function getSvgPreview(): string
    {
        return $this->{self::SVG_PREVIEW};
    }

    /**
     * @return string
     */
    public function getViewPath(): string
    {
        return $this->{self::VIEW_FILE};
    }
}