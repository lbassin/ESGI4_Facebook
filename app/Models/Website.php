<?php

declare(strict_types=1);

namespace App\Model;

use App\Http\Helpers\FacebookHelper;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Website
 * @package App\Model
 */
class Website extends Model
{
    /**
     *
     */
    const ID = 'id';
    /**
     *
     */
    const USER_ID = 'user_id';
    /**
     *
     */
    const SOURCE_ID = 'source_id';

    /**
     * @var string
     */
    protected $table = 'website';
    /**
     * @var FacebookHelper
     */
    private $fbHelper;

    /**
     * Website constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fbHelper = app()->make(FacebookHelper::class);
        parent::__construct($attributes);
    }

    /**
     * @return string
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function getName()
    {
        return $this->fbHelper->getPageName((string)$this->{self::SOURCE_ID});
    }

    /**
     * @return array
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function toArray()
    {
        /** @var array $website */
        $website = parent::toArray();

        /** @var array $fbData */
        $fbData = [
            'name' => $this->getName()
        ];

        return array_merge($website, $fbData);
    }
}