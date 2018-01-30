<?php

declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Class HomeCategory
 *
 * @author Laurent Bassin <laurent@bassin.info>
 */
class HomeCategory extends Model
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
     * @var string
     */
    protected $table = 'home_category';

    /**
     * @var array
     */
    protected $fillable = [self::ID, self::LABEL, self::PREVIEW];

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
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getBlocks(): Collection
    {
        return $this->belongsToMany(HomeBlock::class, 'home_category_block', 'category_id', 'block_id')->get();
    }
}