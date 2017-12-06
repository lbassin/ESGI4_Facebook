<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/facebook/login', function(SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb) {
    $login_link = $fb
        ->getRedirectLoginHelper()
        ->getLoginUrl('http://facebook.dev:8080/facebook/callback', ['email', 'user_events']);

    echo '<a href="' . $login_link . '">Log in with Facebook</a>';
});

Route::get('/facebook/callback', function() {
   dd($_GET);
});

Route::get('/facebook/javascript', function(SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb) {
    try {
        $token = $fb->getJavaScriptHelper()->getAccessToken();
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        // Failed to obtain access token
        dd($e->getMessage());
    }

    // $token will be null if no cookie was set or no OAuth data
    // was found in the cookie's signed request data
    if (! $token) {
        // User hasn't logged in using the JS SDK yet
    }
});