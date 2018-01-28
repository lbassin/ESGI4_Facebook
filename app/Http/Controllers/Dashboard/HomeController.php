<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

class HomeController extends BaseController
{
    public function elementsAction(): View
    {
        die('ok');
        return view();
    }
}