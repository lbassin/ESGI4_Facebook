<?php

namespace App\Providers;

use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use SammyK\LaravelFacebookSdk\LaravelPersistentDataHandler;
use SammyK\LaravelFacebookSdk\LaravelUrlDetectionHandler;

class LaravelFacebookSdkServiceProvider extends \SammyK\LaravelFacebookSdk\LaravelFacebookSdkServiceProvider
{
    /**
     * @override Change class register to be a singleton
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('SammyK\LaravelFacebookSdk\LaravelFacebookSdk', function ($app) {
            $config = $app['config']->get('laravel-facebook-sdk.facebook_config');

            if (!isset($config['persistent_data_handler']) && isset($app['session.store'])) {
                $config['persistent_data_handler'] = new LaravelPersistentDataHandler($app['session.store']);
            }

            if (!isset($config['url_detection_handler'])) {
                $config['url_detection_handler'] = new LaravelUrlDetectionHandler($app['url']);
            }

            return new LaravelFacebookSdk($app['config'], $app['url'], $config);
        });
    }
}
