<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Helpers\FacebookHelper;
use App\Http\Middleware\AuthFb;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Class DashboardController
 * @package App\Http\Controllers
 */
class HomeController extends BaseController
{
    /**
     * @var Store
     */
    private $session;

    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    /**
     * @return View
     */
    public function indexAction(): View
    {
        return view('home.index');
    }

    /**
     * @return View
     */
    public function policyAction(): View
    {
        return view('home.policy');
    }

    /**
     * @return View
     */
    public function supportAction(): View
    {
        return view('home.support');
    }

    public function demoAction(): RedirectResponse
    {
        $this->session->flush();
        $this->session->put(FacebookHelper::FB_TOKEN_KEY, env('DEMO_TOKEN'));

        return response()->redirectToRoute('dashboard');
    }

    public function loginAction(): RedirectResponse
    {
        $this->session->flush();

        return response()->redirectToRoute('dashboard');
    }

    /**
     * @return RedirectResponse
     */
    public function documentationAction(): RedirectResponse
    {
        return response()->redirectTo('https://lbassin.gitbooks.io/wawat/content/');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function supportSubmitAction(Request $request): JsonResponse
    {
        Log::alert(print_r($request->all(), true));

        return response()->json([
            'message' => 'Votre demande va être étudiée par nos équipes dans les plus brefs délais'
        ]);
    }
}
