<?php

declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Menu
 *
 * @author Laurent Bassin <laurent@bassin.info>
 */
class Menu extends Model
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
    const TEMPLATE_ID = 'template_id';
    /**
     *
     */
    const NAME = 'name';
    /**
     *
     */
    const ACCUEIL = 'accueil';
    /**
     *
     */
    const ALBUMS = 'albums';
    /**
     *
     */
    const ARTICLES = 'articles';
    /**
     *
     */
    const EVENTS = 'events';
    /**
     *
     */
    const REVIEWS = 'reviews';
    /**
     * @var string
     */
    protected $table = 'menu';

    /**
     * @var array
     */
    protected $fillable = [
        self::ID,
        self::WEBSITE_ID,
        self::TEMPLATE_ID,
        self::NAME,
        self::ACCUEIL,
        self::ALBUMS,
        self::ARTICLES,
        self::EVENTS,
        self::REVIEWS
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->{self::ID};
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
    public function getName(): string
    {
        return $this->{self::NAME};
    }

    /**
     * @param $link
     * @return bool
     */
    public function isVisible($link): bool
    {
        return (bool)$this->{$link};
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            self::ACCUEIL => $this->isVisible(self::ACCUEIL),
            self::ALBUMS => $this->isVisible(self::ALBUMS),
            self::ARTICLES => $this->isVisible(self::ARTICLES),
            self::EVENTS => $this->isVisible(self::EVENTS),
            self::REVIEWS => $this->isVisible(self::REVIEWS),
        ];
    }
}