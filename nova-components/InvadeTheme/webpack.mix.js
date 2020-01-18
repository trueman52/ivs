let mix = require('laravel-mix')

mix.setPublicPath('resources')
    .sass('resources/sass/theme.scss', 'css')