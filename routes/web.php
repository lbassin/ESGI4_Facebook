<?php

use Illuminate\Support\Facades\Route;

/** @var \App\Http\Helpers\AppHelper $appHelper */
$appHelper = app()->make(\App\Http\Helpers\AppHelper::class);

// Admin dashboard
Route::domain($appHelper->getAppUrlWithoutHttp(false))
    ->group(function () {
        Route::get('/', 'HomeController@indexAction')->name('home');

        Route::get('/dashboard/permissions', 'DashboardController@permissionsAction')->name('dashboard.permissions');
        Route::middleware(['AuthFb'])->group(function () {
            Route::get('/dashboard', 'DashboardController@indexAction')->name('dashboard');
            Route::get('/dashboard/new/{id}', 'DashboardController@newAction')->name('dashboard.new');
        });

        Route::get('/dashboard/website/{subdomain}', 'Dashboard\WebsiteController@indexAction')
            ->name('dashboard.website')
            ->middleware(['WebsiteExists']);
    });

// Website viewer
Route::domain('{subdomain}.' . $appHelper->getAppUrlWithoutHttp(false))
    ->middleware(['WebsiteExists'])
    ->group(function () {
        Route::get('/', 'WebsiteController@indexAction');
    });

