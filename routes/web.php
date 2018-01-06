<?php

use Illuminate\Support\Facades\Route;

/** @var \App\Http\Helpers\AppHelper $appHelper */
$appHelper = app()->make(\App\Http\Helpers\AppHelper::class);

// Admin dashboard
Route::domain($appHelper->getAppUrlWithoutHttp(false))->group(function () {
    Route::get('/', 'HomeController@indexAction')
        ->name('home');

    Route::get('/dashboard/permissions', 'DashboardController@permissionsAction')
        ->name('dashboard.permissions');

    Route::middleware(['AuthFb', 'AddViewData'])->group(function () {
        Route::get('/dashboard', 'DashboardController@indexAction')
            ->name('dashboard');

        Route::post('/dashboard/new', 'DashboardController@newAction')
            ->name('dashboard.new');

        Route::post('/dashboard/suggest/url', 'DashboardController@suggestUrlAction')
            ->name('dashboard.suggest.url');
    });

    Route::middleware(['AuthFb', 'WebsiteExists', 'AddViewData'])->group(function () {
        Route::get('/dashboard/website/{subdomain}', 'Dashboard\WebsiteController@indexAction')
            ->name('dashboard.website');

        Route::get('/dashboard/website/{subdomain}/home', 'Dashboard\WebsiteController@homeAction')
            ->name('dashboard.website.home');

        Route::get('/dashboard/website/{subdomain}/albums', 'Dashboard\WebsiteController@albumsAction')
            ->name('dashboard.website.albums');

        Route::post('/dashboard/website/{subdomain}/albums/new', 'Dashboard\AlbumController@createAction')
            ->name('dashboard.website.albums.create');

        Route::get('/dashboard/website/{subdomain}/albums/{id}', 'Dashboard\AlbumController@editAction')
            ->name('dashboard.website.albums.edit');

        Route::post('/dashboard/website/{subdomain}/albums/template', 'Dashboard\AlbumController@templateAction')
            ->name('dashboard.website.albums.template');

        Route::get('/dashboard/website/{subdomain}/articles', 'Dashboard\WebsiteController@articlesAction')
            ->name('dashboard.website.articles');

        Route::get('/dashboard/website/{subdomain}/events', 'Dashboard\WebsiteController@eventsAction')
            ->name('dashboard.website.events');

        Route::get('/dashboard/website/{subdomain}/reviews', 'Dashboard\WebsiteController@reviewsAction')
            ->name('dashboard.website.reviews');
    });
});

// Website viewer
Route::domain('{subdomain}.' . $appHelper->getAppUrlWithoutHttp(false))
    ->middleware(['WebsiteExists'])
    ->group(function () {
        Route::get('/', 'WebsiteController@indexAction');
    });


