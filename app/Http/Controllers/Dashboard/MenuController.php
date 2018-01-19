<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Helpers\HomeHelper;
use App\Model\Template;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

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

    public function __construct(HomeHelper $homeHelper)
    {
        $this->homeHelper = $homeHelper;
    }

    /**
     * @param Request $request
     * @return View
     */
    public function templatesGridAction(Request $request): View
    {
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
            // TODO
        }

        return view('dashboard.website.album.templates.preview-grid', [
            'templates' => $templates,
            'selectedTemplate' => $templateId,
            'hideControls' => $hideControls,
            'nextDisabled' => $nextDisabled
        ]);
    }
}