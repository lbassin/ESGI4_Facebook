<?php

namespace App\Http\Controllers\Dashboard;

use App\Model\HomeCategory;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class HomeController extends BaseController
{
    public function categoriesAction(): View
    {
        /** @var Collection $categories */
        $categories = HomeCategory::all();

        return view('dashboard.website.home.elements.categories-grid', [
            'categories' => $categories
        ]);
    }
}