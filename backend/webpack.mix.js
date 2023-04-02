const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .copy('resources/js/htmlTemplate.js', 'public/js/htmlTemplate.js')
    .copy('resources/js/chatApp.js', 'public/js/chatApp.js')
    .copy('resources/js/chatNotifications.js', 'public/js/chatNotifications.js')
    .copy('resources/js/chatSidebar.js', 'public/js/chatSidebar.js')
    .copy('resources/js/views', 'public/js/views');
