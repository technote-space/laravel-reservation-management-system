const mix = require('laravel-mix');

mix.webpackConfig(webpack => {
    return {
        plugins: [
            new webpack.DefinePlugin({
                ROUTE_BASE: JSON.stringify(process.env.GH_PAGES_BASE || '/'),
                ADAPTER: JSON.stringify('local' === process.env.NODE_ENV ? './mock' : './axios'),
                IS_STRICT: JSON.stringify('production' !== process.env.NODE_ENV && 'local' !== process.env.NODE_ENV),
            }),
        ],
    };
}).js('resources/js/app.js', 'public/js').sass('resources/sass/app.scss', 'public/css').version();
