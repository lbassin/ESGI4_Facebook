<?php

declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

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
     * @var string
     */
    protected $table = 'home_block';

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
}