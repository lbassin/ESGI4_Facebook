<?php

declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WebsiteHomeBlock
 *
 * @author Laurent Bassin <laurent@bassin.info>
 */
class WebsiteHomeBlock extends Model
{
    /**
     *
     */
    const ID = 'id';
    /**
     *
     */
    const WEBSITE_ID = 'website_id';
    /**
     *
     */
    const BLOCK_ID = 'block_id';
    /**
     *
     */
    const CONFIG = 'config';
    /**
     *
     */
    const ORDER = 'order';

    /**
     * @var string
     */
    protected $table = 'website_home_block';

    /**
     * @var array
     */
    protected $fillable = [self::ID, self::WEBSITE_ID, self::BLOCK_ID, self::CONFIG, self::ORDER];

    /**
     * @return string
     */
    public function getConfig(): string
    {
        return $this->{self::CONFIG};
    }

    /**
     * @return string
     */
    public function getSvgPreview(): string
    {
        return $this->hasOne(HomeBlock::class, HomeBlock::ID, WebsiteHomeBlock::BLOCK_ID)
            ->get()->first()->getSvgPreview();
    }

    /**
     * @return int
     */
    public function getBlockId(): int
    {
        return $this->{self::BLOCK_ID};
    }
}