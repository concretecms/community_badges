// Javascript dependencies are compiled with Laravel Mix https://laravel.com/docs/5.5/mix
let mix = require('laravel-mix');

mix.webpackConfig({
    resolve: {
        symlinks: false
    },
    externals: {
        jquery: 'jQuery',
        bootstrap: true,
        vue: 'Vue',
        moment: 'moment'
    },
    module: {
        rules: [
            { test: /\.html$/, loader: "underscore-template-loader" },
            {
                test: /\.jsx?$/,
                exclude: /(bower_components|node_modules\/v-calendar)/,
                use: [
                    {
                        loader: 'babel-loader',
                        options: Config.babel()
                    }
                ]
            },
        ]
    }
});

mix.setResourceRoot('../');
mix.setPublicPath('../blocks/community_badges/');

mix
    .sass('assets/community_badges/scss/community_badges.scss', '../blocks/community_badges/view.css')
    .js('assets/community_badges/js/community_badges.js', '../blocks/community_badges/view.js')
