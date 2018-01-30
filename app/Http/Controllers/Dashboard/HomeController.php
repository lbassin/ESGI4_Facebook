<?php

namespace App\Http\Controllers\Dashboard;

use App\Model\HomeBlock;
use App\Model\HomeCategory;
use App\Model\Website;
use App\Model\WebsiteHomeBlock;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
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

    /**
     * @param Request $request
     * @return View
     */
    public function blockConfigAction(Request $request): View
    {
        /** @var string $blockId */
        $blockId = $request->input('block');
        /** @var HomeBlock $block */
        $block = HomeBlock::where(HomeBlock::ID, $blockId)->first();

        return view('dashboard.website.home.elements.config-modal', [
            'block' => $block
        ]);
    }

    /**
     * @param Request $request
     * @param string $subdomain
     * @return JsonResponse
     */
    public function saveAction(Request $request, string $subdomain): JsonResponse
    {
        /** @var array $blocks */
        $blocks = $request->input('blocks');
        /** @var Website $website */
        $website = Website::where(Website::SUBDOMAIN, $subdomain)->first();
        /** @var Collection $oldBlocks */
        $oldBlocks = $website->getHomeBlocks();
        /** @var array $config */
        $config = [];
        /** @var int $order */
        $order = 0;

        /** @var array $block */
        foreach ($blocks as $blockData) {
            foreach ($blockData as $data) {
                if (!isset($data['name']) || !isset($data['value'])) {
                    continue;
                }

                $config[$data['name']] = $data['value'];
            }
            $order += 1;
        }

        /** @var int $blockId */
        $blockId = $config['block_id'] ?? -1;
        unset($config['block_id']);

        WebsiteHomeBlock::where(WebsiteHomeBlock::WEBSITE_ID, $website->getId())->delete();

        /** @var WebsiteHomeBlock $block */
        $block = new WebsiteHomeBlock([
            WebsiteHomeBlock::WEBSITE_ID => $website->getId(),
            WebsiteHomeBlock::ORDER => $order,
            WebsiteHomeBlock::CONFIG => json_encode($config),
            WebsiteHomeBlock::BLOCK_ID => $blockId
        ]);

        try{
            $block->save();
        }catch (QueryException $exception){
            // TODO
            // Save $oldBlocks
        }

        die('ok');
    }
}