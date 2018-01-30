<?php

declare(strict_types=1);

namespace App\Model;

use App\Http\Helpers\FacebookHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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
     *
     */
    const ACCESS_TOKEN = 'access_token';

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
     * @return int
     */
    public function getId(): int
    {
        return $this->{self::ID};
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

        $this->{self::SUBDOMAIN} = strtolower($this->getSubDomain());

        return parent::save($options);
    }

    /**
     * @return mixed
     */
    public function getSourceId(): int
    {
        return $this->{Website::SOURCE_ID};
    }

    /**
     * @return mixed
     */
    public function getUserId(): int
    {
        return $this->{Website::USER_ID};
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        /** @var Carbon $date */
        $date = $this->{$this->getUpdatedAtColumn()};

        return $date->format('d/m/Y H:i');
    }

    /**
     * @return mixed
     */
    public function getSubDomain()
    {
        return $this->{self::SUBDOMAIN};
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->{self::ACCESS_TOKEN};
    }

    /**
     * @param $accessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->{self::ACCESS_TOKEN} = $accessToken;
    }

    /**
     * @return Menu
     */
    public function getMenu(): Menu
    {
        return $this->hasOne(Menu::class, Menu::WEBSITE_ID)->get()->first();
    }

    public function getHomeBlocks(): Collection
    {
        return $this->hasMany(WebsiteHomeBlock::class, WebsiteHomeBlock::WEBSITE_ID, 'id')->get();
    }

}