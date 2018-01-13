<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

/**
 * Class DashboardController
 * @package App\Http\Controllers
 */
class HomeController extends BaseController
{
    /**
     * @return View
     */
    public function indexAction(): View
    {
        return view('home');
    }

    public function policyAction(): View
    {
        return view('policy');
    }
}
