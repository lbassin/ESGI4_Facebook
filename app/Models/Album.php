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
     *
     */
    const WEBSITE_ID = 'website_id';
    /**
     *
     */
    const VISIBLE = 'is_visible';
    /**
     *
     */
    const PAGINATION_SIZE = 9;

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
        self::HIDE_NEW,
        self::WEBSITE_ID,
        self::VISIBLE
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
    public function shouldShowNew(): bool
    {
        return !$this->{self::HIDE_NEW};
    }

    /**
     * @return int
     */
    public function getTemplateId(): int
    {
        return $this->{self::TEMPLATE_ID};
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->{self::TITLE};
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->{self::DESCRIPTION};
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->{self::URL};
    }

    /**
     * @return bool
     */
    public function getHideNew(): bool
    {
        return (bool)$this->{self::HIDE_NEW};
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        return (bool)$this->{self::VISIBLE};
    }
}