const mix = require('laravel-mix');
const WorkboxPlugin = require('workbox-webpack-plugin');
const fs = require('fs');

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

mix.js('resources/js/app.js', 'public/js').vue()
    .sass('resources/sass/app.scss', 'public/css')
    .version()
    .webpackConfig((webpack) => {
        return {
            plugins: [
                new webpack.DefinePlugin({
                    __VUE_PROD_DEVTOOLS__: process.env.NODE_ENV === 'development',
                }),
                new WorkboxPlugin.InjectManifest({
                    swSrc: './resources/js/serviceWorker/serviceWorker.js',
                    swDest: 'serviceWorker.js'
                })
            ],
            output: {
                publicPath: ''
            }
        }
    }).then(
    ({compilation}) => {
        const path = './public/';
        const regex = /precache-manifest\..*\.js/;
        fs.readdirSync(path)
            .filter((filename) => {
                return (!Object.keys(compilation.assets).includes(filename)) && regex.test(filename);
            })
            .map((filename) => {
                fs.unlinkSync(path + filename)
            });
    });
