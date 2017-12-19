<?php

use Illuminate\Support\Facades\Route;

/** @var \App\Http\Helpers\AppHelper $appHelper */
$appHelper = app()->make(\App\Http\Helpers\AppHelper::class);

// Admin dashboard
Route::domain($appHelper->getAppUrlWithoutHttp(false))->group(function () {
    Route::get('/', 'HomeController@indexAction')->name('home');

    Route::get('/dashboard/permissions', 'DashboardController@permissionsAction')->name('dashboard.permissions');
    Route::middleware(['AuthFb'])->group(function () {
        Route::get('/dashboard', 'DashboardController@indexAction')->name('dashboard');
        Route::get('/dashboard/new/{id}', 'DashboardController@newAction')->name('dashboard.new');
    });
});

// Website viewer
Route::domain('{subdomain}.' . $appHelper->getAppUrlWithoutHttp(false))->group(function () {
    Route::get('/', function ($subdomain) {
        die($subdomain);
    });
});

