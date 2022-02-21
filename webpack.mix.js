const dotenvExpand = require('dotenv-expand');
dotenvExpand(require('dotenv').config({ path: '../../.env' }));

const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

let publicPath = '../../public';

mix.setPublicPath(publicPath).mergeManifest();

mix.copyDirectory(__dirname + '/Resources/assets/img', `${publicPath}/img`);

mix.js(__dirname + '/Resources/assets/js/main.js', 'js/site-main.js')
    .js(__dirname + '/Resources/assets/js/meetings.js', 'js/site-meetings.js')
    .sass(__dirname + '/Resources/assets/sass/app.scss', 'css/site.css')
    .sourceMaps(true, 'source-map');

if (mix.inProduction()) {
    mix.version();
}
