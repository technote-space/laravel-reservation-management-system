const mix = require('laravel-mix');

const base = process.env.GH_PAGES_BASE || '/';
mix.webpackConfig(webpack => {
    return {
        plugins: [
            new webpack.DefinePlugin({
                ROUTE_BASE: JSON.stringify(base),
                ADAPTER: JSON.stringify('local' === process.env.NODE_ENV ? './mock' : './axios'),
                IS_STRICT: JSON.stringify('production' !== process.env.NODE_ENV && 'local' !== process.env.NODE_ENV),
            }),
        ],
    };
}).js('resources/js/app.js', 'public/js').sass('resources/sass/app.scss', 'public/css').setResourceRoot(base).version();
