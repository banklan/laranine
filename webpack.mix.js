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

// mix.js('resources/js/app.js', 'public/js')
//     .sass('resources/sass/app.scss', 'public/css');


//     mix.webpackConfig({
//         module: {
//             rules: [
//                 {
//                     test: /\.mjs$/,
//                     include: /node_modules/,
//                     type: "javascript/auto"
//                 }
//             ]
//         }
//     });

mix.js('resources/js/app.js', 'public/js').vue()
    .sass('resources/sass/app.scss', 'public/css')
    .browserSync({
        proxy: "localhost:8000",
        port: 8000,
        ui: {
            port: 8000
        },
    });

