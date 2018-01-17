<?php

namespace App\Http\Controllers;

use App\Http\Helpers\FacebookHelper;
use App\Model\Website;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;
use Illuminate\View\View;

/**
 * Class AdminController
 *
 * @author Laurent Bassin <laurent@bassin.info>
 */
class AdminController extends BaseController
{
    /**
     * @var FacebookHelper
     */
    private $fbHelper;

    /**
     * AdminController constructor.
     * @param FacebookHelper $fbHelper
     */
    public function __construct(
        FacebookHelper $fbHelper
    )
    {
        $this->fbHelper = $fbHelper;
    }

    /**
     * @return View
     */
    public function indexAction(): View
    {
        /** @var Collection $websites */
        $websites = Website::all();

        return view('admin.index', [
            'websites' => $websites
        ]);
    }
}