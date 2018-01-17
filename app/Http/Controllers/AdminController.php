<?php

namespace App\Http\Controllers;

use App\Http\Helpers\FacebookHelper;
use Illuminate\Routing\Controller as BaseController;
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
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function indexAction(): View
    {
        $this->fbHelper->getAdminUsers();
        return view('admin.index');
    }
}