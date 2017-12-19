<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

/**
 * Class DashboardController
 * @package App\Http\Controllers
 */
class WebsiteController extends BaseController
{
    /**
     * @return View
     */
    public function indexAction(): View
    {
        die('website');
    }
}
