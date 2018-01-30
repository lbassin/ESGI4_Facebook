<?php

use Illuminate\Support\Facades\Route;

/** @var \App\Http\Helpers\AppHelper $appHelper */
$appHelper = app()->make(\App\Http\Helpers\AppHelper::class);

// Admin dashboard
Route::domain($appHelper->getAppUrlWithoutHttp(false))->middleware(['HttpsProtocol'])->group(function () {
    Route::get('/', 'HomeController@indexAction')
        ->name('home');

    Route::get('/policy', 'HomeController@policyAction')
        ->name('policy');

    Route::get('/support', 'HomeController@supportAction')
        ->name('support');

    Route::get('/documentation', 'HomeController@documentationAction')
        ->name('docs');

    Route::post('/support', 'HomeController@supportSubmitAction')
        ->name('support.submit');

    Route::get('/dashboard/logout', 'DashboardController@logoutAction')
        ->name('dashboard.logout');

    Route::get('/dashboard/permissions', 'DashboardController@permissionsAction')
        ->name('dashboard.permissions');

    Route::middleware(['AuthFb', 'AddDashboardDataToView'])->group(function () {
        Route::get('/dashboard', 'DashboardController@indexAction')
            ->name('dashboard');

        Route::post('/dashboard/new', 'DashboardController@newAction')
            ->name('dashboard.new');

        Route::post('/dashboard/suggest/url', 'DashboardController@suggestUrlAction')
            ->name('dashboard.suggest.url');
    });

    Route::middleware(['AuthFb', 'WebsiteExists', 'AddDashboardDataToView'])->group(function () {
        Route::get('/dashboard/website/{subdomain}', 'Dashboard\WebsiteController@indexAction')
            ->name('dashboard.website');

        Route::get('/dashboard/website/{subdomain}/home', 'Dashboard\HomeController@indexAction')
            ->name('dashboard.website.home');

        Route::post('/dashboard/website/{subdomain}/home/categories', 'Dashboard\HomeController@categoriesAction')
            ->name('dashboard.website.home.categories');

        Route::post('/dashboard/website/{subdomain}/home/blocks', 'Dashboard\HomeController@blocksAction')
            ->name('dashboard.website.home.blocks');

        Route::post('/dashboard/website/{subdomain}/home/block/config', 'Dashboard\HomeController@blockConfigAction')
            ->name('dashboard.website.home.block.config');

        Route::post('/dashboard/website/{subdomain}/home/save', 'Dashboard\HomeController@saveAction')
            ->name('dashboard.website.home.save');

        Route::get('/dashboard/website/{subdomain}/menu', 'Dashboard\MenuController@indexAction')
            ->name('dashboard.website.menu');

        Route::post('/dashboard/website/{subdomain}/menu/templates/grid', 'Dashboard\MenuController@templatesGridAction')
            ->name('dashboard.website.menu.templates.grid');

        Route::post('/dashboard/website/{subdomain}/menu/save', 'Dashboard\MenuController@saveAction')
            ->name('dashboard.website.menu.save');

        Route::get('/dashboard/website/{subdomain}/albums', 'Dashboard\WebsiteController@albumsAction')
            ->name('dashboard.website.albums');

        Route::post('/dashboard/website/{subdomain}/albums/new', 'Dashboard\AlbumController@createAction')
            ->name('dashboard.website.albums.create');

        Route::get('/dashboard/website/{subdomain}/albums/{id}', 'Dashboard\AlbumController@editAction')
            ->name('dashboard.website.albums.edit');

        Route::post('/dashboard/website/{subdomain}/albums/{id}/save', 'Dashboard\AlbumController@saveAction')
            ->name('dashboard.website.albums.save');

        Route::post('/dashboard/website/{subdomain}/albums/{id}/upload', 'Dashboard\AlbumController@uploadAction')
            ->name('dashboard.website.albums.upload');

        Route::post('/dashboard/website/{subdomain}/albums/templates/preview', 'Dashboard\AlbumController@templatePreviewAction')
            ->name('dashboard.website.albums.templates.preview');

        Route::post('/dashboard/website/{subdomain}/albums/{id}/templates/grid', 'Dashboard\AlbumController@templatesGridAction')
            ->name('dashboard.website.albums.templates.grid');

        Route::post('/dashboard/website/{subdomain}/albums/{id}/images/grid', 'Dashboard\AlbumController@imagesGridAction')
            ->name('dashboard.website.albums.images.grid');

        Route::post('/dashboard/website/{subdomain}/albums/{id}/images/preview', 'Dashboard\AlbumController@imagePreviewAction')
            ->name('dashboard.website.albums.images.preview');

        Route::get('/dashboard/website/{subdomain}/articles', 'Dashboard\WebsiteController@articlesAction')
            ->name('dashboard.website.articles');

        Route::get('/dashboard/website/{subdomain}/events', 'Dashboard\WebsiteController@eventsAction')
            ->name('dashboard.website.events');

        Route::post('/dashboard/website/{subdomain}/events/save', 'Dashboard\EventController@saveAction')
            ->name('dashboard.website.events.save');

        Route::post('/dashboard/website/{subdomain}/events/details', 'Dashboard\EventController@detailsAction')
            ->name('dashboard.website.events.details');

        Route::get('/dashboard/website/{subdomain}/reviews', 'Dashboard\WebsiteController@reviewsAction')
            ->name('dashboard.website.reviews');

        Route::post('/dashboard/website/{subdomain}/reviews/save', 'Dashboard\ReviewController@saveAction')
            ->name('dashboard.website.reviews.save');

        Route::post('/dashboard/website/{subdomain}/reviews/details', 'Dashboard\ReviewController@detailsAction')
            ->name('dashboard.website.reviews.details');
    });

    Route::prefix(env('ADMIN_URL'))
        ->middleware(['AuthAdmin'])
        ->group(function () {
            Route::get('/', 'AdminController@indexAction')
                ->name('admin.index');
        });
});

// Website viewer
Route::domain('{subdomain}.' . $appHelper->getAppUrlWithoutHttp(false))
    ->middleware(['CanDisplayWebsite', 'AddWebsiteDataToView'])
    ->group(function () {
        Route::get('/', 'WebsiteController@indexAction')
            ->name('website.home');

        Route::get('/albums', 'WebsiteController@albumsAction')
            ->name('website.albums');

        Route::get('/articles', 'WebsiteController@articlesAction')
            ->name('website.articles');

        Route::get('/evenements', 'WebsiteController@eventsAction')
            ->name('website.events');

        Route::get('/avis', 'WebsiteController@reviewsAction')
            ->name('website.reviews');

        Route::get('/{element}', 'WebsiteController@viewAction')
            ->name('website.view');
    });


