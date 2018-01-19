<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Helpers\HomeHelper;
use App\Http\Helpers\WebsiteHelper;
use App\Model\Menu;
use App\Model\Template;
use App\Model\Website;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;
use Psr\Log\LoggerInterface;

/**
 * Class MenuController
 *
 * @author Laurent Bassin <laurent@bassin.info>
 */
class MenuController extends BaseController
{

    /**
     * @var HomeHelper
     */
    private $homeHelper;
    /**
     * @var WebsiteHelper
     */
    private $websiteHelper;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * MenuController constructor.
     * @param HomeHelper $homeHelper
     * @param WebsiteHelper $websiteHelper
     * @param LoggerInterface $logger
     */
    public function __construct(
        HomeHelper $homeHelper,
        WebsiteHelper $websiteHelper,
        LoggerInterface $logger
    )
    {
        $this->homeHelper = $homeHelper;
        $this->websiteHelper = $websiteHelper;
        $this->logger = $logger;
    }

    public function indexAction(): View
    {
        /** @var Website $website */
        $website = $this->websiteHelper->getCurrentWebsite();
        /** @var Menu $menu */
        $menu = Menu::where(Menu::WEBSITE_ID, $website->getId())->first();

        /** @var array $config */
        $config = [];
        if (!empty($menu)) {
            $config = [
                Menu::NAME => $menu->getName(),
                Menu::ACCUEIL => $menu->isVisible(Menu::ACCUEIL),
                Menu::ALBUMS => $menu->isVisible(Menu::ALBUMS),
                Menu::ARTICLES => $menu->isVisible(Menu::ARTICLES),
                Menu::EVENTS => $menu->isVisible(Menu::EVENTS),
                Menu::REVIEWS => $menu->isVisible(Menu::REVIEWS),
            ];
        }

        return view('dashboard.website.menu.index', [
            'templateId' => $menu->getTemplateId(),
            'config' => $config
        ]);
    }

    /**
     * @param Request $request
     * @return View
     */
    public function templatesGridAction(Request $request): View
    {
        /** @var Website $website */
        $website = $this->websiteHelper->getCurrentWebsite();
        /** @var int $page */
        $page = $request->post('page');
        /** @var array $templates */
        $templates = $this->homeHelper->getTemplatesByPage($page);
        /** @var string $templateId */
        $templateId = '';
        /** @var bool $hideControls */
        $hideControls = $page == 1 && count($templates) < Template::PAGINATION_SIZE;
        /** @var bool $nextDisabled */
        $nextDisabled = count($templates) < Template::PAGINATION_SIZE;

        if ($request->post('templateId')) {
            $templateId = $request->post('templateId');
        } else {
            /** @var Menu $menu */
            $menu = Menu::where(Menu::WEBSITE_ID, $website->getId())->first();
            if (!empty($menu)) {
                $templateId = $menu->getTemplateId();
            }
        }

        return view('dashboard.website.album.templates.preview-grid', [
            'templates' => $templates,
            'selectedTemplate' => $templateId,
            'hideControls' => $hideControls,
            'nextDisabled' => $nextDisabled
        ]);
    }

    /**
     * @param Request $request
     * @param $subdomain
     * @return JsonResponse
     */
    public function saveAction(Request $request, $subdomain): JsonResponse
    {
        /** @var Website $website */
        $website = $this->websiteHelper->getCurrentWebsite();

        /** @var Menu $menu */
        $menu = Menu::where(Menu::WEBSITE_ID, $website->getId())->first();
        if (empty($menu)) {
            $menu = new Menu([Menu::WEBSITE_ID => $website->getId()]);
        }

        /** @var array $options */
        $options = [];
        foreach ($request->post('options') as $option) {
            if (empty($option['name']) || empty($option['value'])) {
                continue;
            }

            $options[$option['name']] = $option['value'];
        }

        /** @var array $menuData */
        $menuData = [
            Menu::TEMPLATE_ID => $request->post('template'),
            Menu::NAME => isset($options['name']) ? $options['name'] : '',
            Menu::ACCUEIL => !empty($options['accueil']),
            Menu::ALBUMS => !empty($options['albums']),
            Menu::ARTICLES => !empty($options['articles']),
            Menu::EVENTS => !empty($options['events']),
            Menu::REVIEWS => !empty($options['reviews']),
        ];

        $menu->fill($menuData);

        try {
            $menu->save();
        } catch (QueryException $ex) {
            $this->logger->error(__FILE__ . ':' . __LINE__ . ' - ' . $ex->getMessage());

            /** @var array $error */
            $error = [
                'error' => true,
                'message' => 'An error occurred'
            ];
            return response()->json($error);
        }

        return response()->json([
            'message' => 'Menu sauvegardé',
            'url' => route('dashboard.website', ['subdomain' => $subdomain])
        ]);
    }
}