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
     *
     */
    const SUBDOMAIN = 'subdomain';

    /**
     * @var string
     */
    protected $table = 'website';

    /**
     * @var FacebookHelper
     */
    private $fbHelper;

    /**
     * @var array
     */
    protected $fillable = [self::ID, self::SOURCE_ID, self::USER_ID, self::SUBDOMAIN];

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

    /**
     * @param array $options
     * @return array|bool
     */
    public function save(array $options = [])
    {
        /** @var int $subdomainSize */
        $subdomainSize = strlen($this->{self::SUBDOMAIN});

        if ($subdomainSize >= 63 || $subdomainSize <= 0) {
            return [
                'error' => true,
                'message' => 'The subdomain is not valid'
            ];
        }

        return parent::save($options);
    }

    /**
     * @return mixed
     */
    public function getSourceId()
    {
        return $this->{Website::SOURCE_ID};
    }

    /**
     * @return mixed
     */
    public function getSubDomain()
    {
        return $this->{self::SUBDOMAIN};
    }

}