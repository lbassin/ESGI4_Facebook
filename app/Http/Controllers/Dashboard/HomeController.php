<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Helpers\WebsiteHelper;
use App\Model\HomeBlock;
use App\Model\HomeCategory;
use App\Model\Website;
use App\Model\WebsiteHomeBlock;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
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
     * @var WebsiteHelper
     */
    private $websiteHelper;

    /**
     * HomeController constructor.
     * @param WebsiteHelper $websiteHelper
     */
    public function __construct(WebsiteHelper $websiteHelper)
    {
        $this->websiteHelper = $websiteHelper;
    }

    /**
     * @return View
     */
    public function indexAction(): View
    {
        /** @var Website $website */
        $website = $this->websiteHelper->getCurrentWebsite();
        /** @var array $blocks */
        $blocks = $website->getHomeBlocks();
        /** @var array $config */
        $config = [];

        /** @var WebsiteHomeBlock $block */
        foreach ($blocks as $block) {

            /** @var array $blockConfig */
            $blockConfig = [];

            foreach ($block->getConfig() as $name => $value) {
                $blockConfig[] = [
                    'name' => $name,
                    'value' => $value
                ];
            }

            $blockConfig[] = [
                'name' => 'block_id',
                'value' => $block->getBlockId()
            ];

            $config[] = [
                'config' => $blockConfig,
                'preview' => base64_encode($block->getSvgPreview())
            ];
        }

        return view('dashboard.website.home.index', [
            'config' => json_encode($config)
        ]);
    }

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
        /** @var int $order */
        $order = 0;

        WebsiteHomeBlock::where(WebsiteHomeBlock::WEBSITE_ID, $website->getId())->delete();

        if (empty($blocks)) {
            $blocks = [];
        }

        try {
            /** @var array $block */
            foreach ($blocks as $blockData) {
                /** @var array $config */
                $config = [];

                foreach ($blockData as $data) {
                    if (!isset($data['name']) || !isset($data['value'])) {
                        continue;
                    }

                    $config[$data['name']] = $data['value'];
                }

                /** @var int $blockId */
                $blockId = $config['block_id'] ?? -1;
                unset($config['block_id']);

                /** @var WebsiteHomeBlock $block */
                $block = new WebsiteHomeBlock([
                    WebsiteHomeBlock::WEBSITE_ID => $website->getId(),
                    WebsiteHomeBlock::ORDER => $order,
                    WebsiteHomeBlock::CONFIG => json_encode($config),
                    WebsiteHomeBlock::BLOCK_ID => $blockId
                ]);

                $block->save();
                $order += 1;
            }
        } catch (QueryException $exception) {
            die('ERROR');
            // TODO
            // Cancel saved blocks
            // Save $oldBlocks
        }

        return response()->json([
            'message' => 'Page sauvegardée',
            'url' => route('dashboard.website', ['subdomain' => $subdomain])
        ]);
    }
}