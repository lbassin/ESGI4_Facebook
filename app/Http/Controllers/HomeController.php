<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Class DashboardController
 * @package App\Http\Controllers
 */
class HomeController extends BaseController
{
    /**
     * @return View
     */
    public function indexAction(): View
    {
        return view('home');
    }

    /**
     * @return View
     */
    public function policyAction(): View
    {
        return view('policy');
    }

    /**
     * @return View
     */
    public function supportAction(): View
    {
        return view('support');
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
