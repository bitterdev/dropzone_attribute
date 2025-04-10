let mix = require('laravel-mix');
const path = require("path");

mix.webpackConfig({
    externals: {
        jquery: "jQuery",
        bootstrap: true
    },
    // NOTE: This doesn't work with Laravel Mix 6 so I'm commenting it out for now. Someone more versed in this
    // will have to fix this if it's still required.
    // Override the default js compile settings to replace exclude with something that doesn't exclude node_modules.
    // @see node_modules/laravel-mix/src/components/JavaScript.js for the original
    module: {
        rules: [
            {
                test: /.(otf|eot|ttf|woff|woff2|svg)(\?\S*)?$/,
                loader: "file-loader",
                options: {
                  publicPath: "../../",
                  name: "./[path][name].[ext]",
                  emitFile: false
                }
            }
        ]
    }
});

mix.setResourceRoot('./');

mix.setPublicPath('../');

mix
    .sass('assets/attributes/dropzone/_form.scss', 'attributes/dropzone/form.css', {
        sassOptions: {
            includePaths: [
                path.resolve(__dirname, './node_modules/')
            ]
        }
    })
    .js('assets/attributes/dropzone/form.js', 'attributes/dropzone/form.js').vue();
