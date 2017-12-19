<?php

use Illuminate\Support\Facades\Route;

/** @var \App\Http\Helpers\AppHelper $appHelper */
$appHelper = app()->make(\App\Http\Helpers\AppHelper::class);

// Admin dashboard
Route::domain($appHelper->getAppUrlWithoutHttp(false))->group(function () {
    Route::get('/', function () {
        return view('home');
    })->name('home');

    Route::get('/fbAskPermission', 'FacebookController@reAskPermissions')->name('fbReAskPermissions');

    Route::middleware(['AuthFb'])->group(function () {
        Route::get('/dashboard', 'DashboardController@indexAction')->name('dashboard');
        Route::get('/dashboard/new/{id}', 'DashboardController@newAction')->name('dashboard.new');
        Route::get('/dashboard/home', 'DashboardController@home');

    });

});

Route::domain('{subdomain}.' . $appHelper->getAppUrlWithoutHttp(false))->group(function () {
    Route::get('/', function ($subdomain) {
        die($subdomain);
    });
});