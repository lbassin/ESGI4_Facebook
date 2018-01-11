<?php

declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Review
 *
 * @author Laurent Bassin <laurent@bassin.info>
 */
class Review extends Model
{
    /**
     *
     */
    const SOURCE_ID = 'source_id';
    /**
     *
     */
    const REVIEWER_ID = 'reviewer_id';
    /**
     *
     */
    const VISIBLE = 'is_visible';

    /**
     * @var string
     */
    protected $table = 'review';

    /**
     * @var array
     */
    protected $fillable = [
        self::SOURCE_ID,
        self::REVIEWER_ID,
        self::VISIBLE,
    ];

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->getSourceId() . '.' . $this->getReviewerId();
    }

    /**
     * @return int
     */
    public function getSourceId(): int
    {
        return $this->{self::SOURCE_ID};
    }

    /**
     * @return int
     */
    public function getReviewerId(): int
    {
        return $this->{self::REVIEWER_ID};
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        return (bool)$this->{self::VISIBLE};
    }
}