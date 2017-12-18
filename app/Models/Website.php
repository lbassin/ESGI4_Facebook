<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Website
 * @package App\Model
 */
class Website extends Model
{
    const ID = 'id';
    const USER_ID = 'user_id';
    const SOURCE_ID = 'source_id';

    /**
     * @var string
     */
    protected $table = 'website';
}