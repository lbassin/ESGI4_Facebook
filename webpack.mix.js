let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js')
    .js('resources/assets/js/fb/fb-login.js', 'public/js')
    .js('resources/assets/js/fb/fb-reAskPermissions', 'public/js')
    .js('resources/assets/js/particles.js', 'public/js')
    .sass('resources/assets/sass/dashboard/app.scss', 'public/css')
    .sass('resources/assets/sass/website/website.scss', 'public/css')
    .sass('resources/assets/sass/admin/admin.scss', 'public/css');
