<?php

namespace App\Http\Controllers;

use App\Http\Helpers\FacebookHelper;
use App\Http\Helpers\UserHelper;
use App\Http\Helpers\WebsiteHelper;
use App\Model\Website;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\GraphNodes\GraphUser;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
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
            'pages' => $this->getPages(),
            'websites' => $this->userHelper->getWebsites(),
        ]);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws FacebookSDKException
     */
    public function newAction(string $id): JsonResponse
    {
        if (!$this->canUsePage($id)) {
            $this->logger->alert(__FILE__ . ':' . __LINE__ . ' - User is not allowed to use this page');

            /** @var array $response */
            $response = [
                'error' => true,
                'message' => 'You are not allowed to use this page'
            ];

            return response()->json($response)->setStatusCode(403);
        }

        $website = new Website();

        $website->{Website::USER_ID} = $this->fbHelper->getUserId();
        $website->{Website::SOURCE_ID} = $id;
        $website->{Website::SUBDOMAIN} = $this->websiteHelper->generateSubdomain($website);

        /** @var array $errors */
        $errors = [];
        try {
            /** @var array|bool $result */
            $result = $website->save();

            if ($result !== true) {
                $errors[] = $result;
            }
        } catch (QueryException $ex) {
            $this->logger->error(__FILE__ . ':' . __LINE__ . ' - ' . $ex->getMessage());

            $errors[] = [
                'error' => true,
                'message' => 'An error occurred'
            ];
        }

        if (count($errors) > 0) {
            return response()->json($errors)->setStatusCode(403);
        }

        /** @var array $response */
        $response = [
            'success' => true,
            'url' => $this->websiteHelper->getWebsiteFullUrl($website),
        ];

        return response()->json($response)->setStatusCode(200);
    }

    /**
     * @return array
     * @throws FacebookSDKException
     */
    private function getPages(): array
    {
        $pages = $this->fbHelper->getPages();

        // TODO : Remove existing website from the list

        return $pages;
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
        $redirectTo = $this->session->get('redirectTo') ?: route('dashboard');

        $this->session->forget(FacebookHelper::FB_TOKEN_KEY);

        return view('dashboard.permissions', [
            'redirectTo' => $redirectTo
        ]);
    }

}
