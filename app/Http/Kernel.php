<?php

namespace App\Http;

use App\Http\Middleware\AddDashboardDataToView;
use App\Http\Middleware\AddWebsiteDataToView;
use App\Http\Middleware\AuthAdmin;
use App\Http\Middleware\AuthFb;
use App\Http\Middleware\CanDisplayWebsite;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\HttpProtocol;
use App\Http\Middleware\HttpsProtocol;
use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\TrustProxies;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Middleware\WebsiteExists;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Spatie\CookieConsent\CookieConsentMiddleware;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        CheckForMaintenanceMode::class,
        ValidatePostSize::class,
        TrimStrings::class,
        ConvertEmptyStringsToNull::class,
        TrustProxies::class
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
        ],

        'api' => [
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'AuthFb' => AuthFb::class,
        'WebsiteExists' => WebsiteExists::class,
        'AddDashboardDataToView' => AddDashboardDataToView::class,
        'AddWebsiteDataToView' => AddWebsiteDataToView::class,
        'AuthAdmin' => AuthAdmin::class,
        'CanDisplayWebsite' => CanDisplayWebsite::class,
        'HttpsProtocol' => HttpsProtocol::class,
        'CookieConsent' => CookieConsentMiddleware::class,
    ];
}
