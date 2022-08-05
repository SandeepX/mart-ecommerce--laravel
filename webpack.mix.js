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
    .sass('resources/sass/app.scss', 'public/css');

mix.browserSync({
    proxy: 'http://127.0.0.1:8000/'
});

mix.webpackConfig({
    resolve: {
        extensions: ['.js', '.vue', '.json'],
        alias: {
            '@': __dirname + '/resources/js',

            //warehouse alias
            '@images': __dirname + '/resources/js/assets/img',
            '@warehouse~components': __dirname + '/resources/js/modules/warehouse/components',
            '@warehouse~pages': __dirname + '/resources/js/modules/warehouse/pages',
            '@warehouse~store': __dirname + '/resources/js/modules/warehouse/store',
            '@warehouse~services': __dirname + '/resources/js/modules/warehouse/service',
            '@warehouse~middlewares': __dirname + '/resources/js/modules/warehouse/middlewares',
            '@warehouse~helpers': __dirname + '/resources/js/modules/warehouse/helpers',

            //shared alias
            '@shared~components': __dirname + '/resources/js/modules/shared/components',
            '@shared~pages': __dirname + '/resources/js/modules/shared/pages',
            '@shared~store': __dirname + '/resources/js/modules/shared/store',
            '@shared~services': __dirname + '/resources/js/modules/shared/service',
            '@shared~middlewares': __dirname + '/resources/js/modules/shared/middlewares',
            '@shared~helpers': __dirname + '/resources/js/modules/shared/helpers',
        },
    },
})

