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
        ->getLoginUrl('https://exmaple.com/facebook/callback', ['email', 'user_events']);

    echo '<a href="' . $login_link . '">Log in with Facebook</a>';
});