<?php

namespace App\Http\Controllers;

use App\Http\Helpers\FacebookHelper;
use App\Http\Helpers\UserHelper;
use App\Http\Helpers\WebsiteHelper;
use App\Model\Menu;
use App\Model\Template;
use App\Model\Website;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\View\View;
use Psr\Log\LoggerInterface;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class DashboardController
 * @package App\Http\Controllers
 */
class DashboardController extends BaseController
{
    /**
     * @var LaravelFacebookSdk
     */
    private $fb;
    /**
     * @var FacebookHelper
     */
    private $fbHelper;
    /**
     * @var UserHelper
     */
    private $userHelper;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var WebsiteHelper
     */
    private $websiteHelper;
    /**
     * @var Store
     */
    private $session;

    /**
     * DashboardController constructor.
     * @param LaravelFacebookSdk $fb
     * @param FacebookHelper $fbHelper
     * @param UserHelper $userHelper
     * @param LoggerInterface $logger
     * @param WebsiteHelper $websiteHelper
     * @param Store $session
     */
    public function __construct(
        LaravelFacebookSdk $fb,
        FacebookHelper $fbHelper,
        UserHelper $userHelper,
        LoggerInterface $logger,
        WebsiteHelper $websiteHelper,
        Store $session
    )
    {
        $this->fb = $fb;
        $this->fbHelper = $fbHelper;
        $this->userHelper = $userHelper;
        $this->logger = $logger;
        $this->websiteHelper = $websiteHelper;
        $this->session = $session;
    }

    /**
     * @return View
     * @throws FacebookSDKException
     */
    public function indexAction(): View
    {
        return view('dashboard.index', [
            'pages' => $this->userHelper->getAvailablePages(),
            'websites' => $this->userHelper->getWebsites(),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws FacebookSDKException
     */
    public function suggestUrlAction(Request $request): JsonResponse
    {
        /** @var string $id */
        $id = $request->post('id');
        /** @var string $url */
        $url = $this->websiteHelper->generateSubdomain($id);

        return response()->json(['url' => $url]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws FacebookSDKException
     */
    public function newAction(Request $request): JsonResponse
    {
        /** @var string $id */
        $id = $request->post('id');
        /** @var string $url */
        $url = $request->post('url') ?: '';

        if (!$this->canUsePage($id)) {
            $this->logger->alert(__FILE__ . ':' . __LINE__ . ' - User is not allowed to use this page');

            /** @var array $response */
            $response = [
                'error' => true,
                'message' => 'You are not allowed to use this page'
            ];

            return response()->json($response);
        }

        if (!$this->websiteHelper->isValidUrl($url)) {
            /** @var array $response */
            $response = [
                'error' => true,
                'message' => 'Url is not valid'
            ];

            return response()->json($response);
        }

        $website = new Website();

        $website->{Website::USER_ID} = $this->fbHelper->getUserId();
        $website->{Website::SOURCE_ID} = $id;
        $website->{Website::SUBDOMAIN} = $url;

        /** @var array $error */
        $error = null;
        try {
            /** @var array|bool $result */
            $result = $website->save();

            if ($result !== true) {
                $error = $result;
            }
        } catch (QueryException $ex) {
            $this->logger->error(__FILE__ . ':' . __LINE__ . ' - ' . $ex->getMessage());

            $error = [
                'error' => true,
                'message' => 'Url is already taken'
            ];
        }

        if (!empty($error)) {
            return response()->json($error);
        }

        /** @var Template $template */
        $template = Template::where(Template::TYPE, Template::TYPE_MENU)->where(Template::DEFAULT, true)->first();
        /** @var Menu $menu */
        $menu = new Menu([
            Menu::TEMPLATE_ID => $template->getId(),
            Menu::WEBSITE_ID => $website->getId(),
            Menu::NAME => $website->getName()
        ]);

        $menu->save();


        /** @var array $response */
        $response = [
            'success' => true,
            'url' => route('dashboard.website', ['subdomain' => $website->getSubDomain()]),
        ];

        return response()->json($response);
    }

    /**
     * @param $id
     * @return bool
     * @throws FacebookSDKException
     */
    private function canUsePage($id): bool
    {
        /** @var array $pages */
        $pages = $this->fbHelper->getPages();

        /** @var array $page */
        foreach ($pages as $page) {
            if ($page['id'] == $id) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function permissionsAction(): View
    {
        /** @var string $redirectTo */
        $redirectTo = $this->session->get('redirectTo');
        if (!empty($redirectTo)) {
            $redirectTo = url($redirectTo);
        } else {
            $redirectTo = url(route('dashboard'));
        }

        $this->session->forget(FacebookHelper::FB_TOKEN_KEY);

        return view('dashboard.permissions', [
            'redirectTo' => $redirectTo
        ]);
    }

    /**
     * @return RedirectResponse
     */
    public function logoutAction(): RedirectResponse
    {
        $this->session->flush();

        return response()->redirectToRoute('home');
    }
}
