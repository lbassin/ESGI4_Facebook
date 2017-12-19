<?php

namespace App\Http\Controllers;

use App\Http\Helpers\FacebookHelper;
use App\Http\Helpers\UserHelper;
use App\Model\Website;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\FacebookResponse;
use Facebook\GraphNodes\GraphUser;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
     * DashboardController constructor.
     * @param LaravelFacebookSdk $fb
     * @param FacebookHelper $fbHelper
     * @param UserHelper $userHelper
     * @param LoggerInterface $logger
     */
    public function __construct(
        LaravelFacebookSdk $fb,
        FacebookHelper $fbHelper,
        UserHelper $userHelper,
        LoggerInterface $logger
    )
    {
        $this->fb = $fb;
        $this->fbHelper = $fbHelper;
        $this->userHelper = $userHelper;
        $this->logger = $logger;
    }

    /**
     * @return View
     * @throws FacebookSDKException
     */
    public function indexAction()
    {
        /** @var GraphUser $user */
        $user = $this->fbHelper->getBasicUserData();

        return view('dashboard', [
            'pages' => $this->getPages(),
            'websites' => $this->userHelper->getWebsites(),
            'userpic' => $user->getPicture()->getUrl(),
            'name' => $user->getName(),
        ]);
    }

    /**
     * @param string $id
     * @return Response
     */
    public function newAction(string $id)
    {
        $website = new Website();
        $website->{Website::USER_ID} = $this->fbHelper->getUserId();
        $website->{Website::SOURCE_ID} = $id;

        try {
            $website->save();
        } catch (QueryException $ex) {
            /** @var array $response */
            $response = [
                'error' => true,
                'message' => 'An error occurred'
            ];

            $this->logger->error(__FILE__ . ':' . __LINE__ . ' - ' . $ex->getMessage());

            return response()->json($response)->setStatusCode(403);
        }

        /** @var array $response */
        $response = [
            'success' => true
        ];

        return response()->json($response)->setStatusCode(200);
    }

    /**
     * @return array
     * @throws FacebookSDKException
     */
    private function getPages()
    {
        $pages = $this->fbHelper->getPages();

        // TODO : Remove existing website from the list

        return $pages;
    }

}
