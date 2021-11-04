const mix = require('laravel-mix')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.js('resources/material-dashboard/js/app.js', 'public/js/material-dashboard-app.js')
  .sass('resources/material-dashboard/sass/material-dashboard.scss', 'public/css/material-dashboard-app.css')
  .postCss('resources/material-dashboard/css/nucleo-icons.css', 'public/css')
  .postCss('resources/material-dashboard/css/nucleo-svg.css', 'public/css')
  .js('resources/material-dashboard/js/material-dashboard.js', 'public/js')

mix.js('resources/js/app.js', 'public/js')
  .sass('resources/sass/app.scss', 'public/css')

mix.js('resources/js/admin.js', 'public/js')
  .sass('resources/sass/admin.scss', 'public/css')

mix.copy('node_modules/trumbowyg/dist/ui/icons.svg', 'public/js/ui/icons.svg')

if (mix.inProduction()) {
  mix.version().sourceMaps();
}
