<?php

namespace App\Http\Controllers\Dashboard;

use App\Model\HomeCategory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;
use Illuminate\View\View;

/**
 * Class HomeController
 *
 * @author Laurent Bassin <laurent@bassin.info>
 */
class HomeController extends BaseController
{
    /**
     * @return View
     */
    public function categoriesAction(): View
    {
        /** @var Collection $categories */
        $categories = HomeCategory::all();

        return view('dashboard.website.home.elements.categories-grid', [
            'categories' => $categories
        ]);
    }

    /**
     * @param Request $request
     * @return View
     */
    public function blocksAction(Request $request): View
    {
        /** @var int $categoryId */
        $categoryId = $request->input('category');
        /** @var HomeCategory $category */
        $category = HomeCategory::where(HomeCategory::ID, $categoryId)->first();
        /** @var Collection $blocks */
        $blocks = $category->getBlocks();

        return view('dashboard.website.home.elements.blocks-grid', [
            'blocks' => $blocks
        ]);
    }
}