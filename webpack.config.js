// Imports
const process = require('process');
const path = require('path');

// Plugins
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const ManifestPlugin = require('webpack-manifest-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const LiveReloadPlugin = require('webpack-livereload-plugin');

// Config
const env = process.env.NODE_ENV || 'development';
const dist = env === 'development' ? 'public/dev-dist' : 'public/dist';

// Loaders
const cleanCssLoader = {
    loader: 'clean-css-loader',
    options: {
        compatibility: 'ie9',
        level: 2,
        inline: ['remote']
    }
};

const postCssLoader = {
    loader: 'postcss-loader',
    options: {
        ident: 'postcss',
        plugins: [
            require('autoprefixer')(),
        ]
    }
};

// Build configuration
module.exports = {
    entry: {
        frontend: './frontend/frontend.js',
        backend: './frontend/backend.js'
    },
    output: {
        path: path.resolve(dist),
        filename: env === 'development' ? '[name].js' : '[name]-[chunkhash:10].js'
    },

    // Enable a watcher on the entry
    watch: env === 'development',

    module: {
        rules: [
            // JS Files
            {
                test: /\.js$/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env']
                    }
                }
            },

            // SASS Files
            {
                test: /\.scss$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    cleanCssLoader,
                    postCssLoader,
                    'sass-loader',
                ]
            },

            // CSS Files
            {
                test: /\.css/,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    cleanCssLoader,
                    postCssLoader,
                ]
            },

            // Webfonts
            {
                test: /\.(ttf|eot|woff|woff2)$/,
                use: {
                    loader: 'file-loader',
                    options: {
                        name: 'fonts/[name]-[hash:10].[ext]',
                    },
                },
            },

            // Images
            {
                test: /\.(png|jpg|gif|svg)$/,
                loader: 'file-loader',
                options: {
                    name: 'images/[name]-[hash:10].[ext]'
                }
            }
        ]
    },

    plugins: [
        new LiveReloadPlugin(),
        new CleanWebpackPlugin([dist]),
        new MiniCssExtractPlugin({
            filename: env === 'development' ? '[name].css' : '[name]-[chunkhash:10].css',
            chunkFilename: '[id].css'
        }),
        new ManifestPlugin({
            filter: (desc) => {
                return !desc.isAsset;
            }
        })
    ]
};
