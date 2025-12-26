console.log('Starting build process for WooCustomGateway plugin...');

const path = require('node:path');
const defaultConfig = require("@wordpress/scripts/config/webpack.config");
const JsonMinimizerPlugin = require("json-minimizer-webpack-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const WooCommerceDependencyExtractionWebpackPlugin = require('@woocommerce/dependency-extraction-webpack-plugin');

// Define source directory
const srcDirectory = path.resolve(__dirname, 'ui');

// Remove default WordPress dependency extraction plugin
const filteredPlugins = defaultConfig.plugins.filter(
    plugin => plugin.constructor.name !== 'DependencyExtractionWebpackPlugin'
);

module.exports = {
    ...defaultConfig,
    entry: {
        ...defaultConfig.entry(),
        "blocks/payment-gateway/index": path.resolve(srcDirectory, "blocks/payment-gateway/index.tsx"),
    },
    output: {
        filename: '[name].js',
        path: path.resolve(__dirname, 'src/Views/js/dist'),
    },
    plugins: [
        ...filteredPlugins,
        new WooCommerceDependencyExtractionWebpackPlugin(),
    ],
    optimization: {
        minimize: true,
        ...defaultConfig.optimization,
        minimizer: [
            ...defaultConfig.optimization.minimizer,
            new CssMinimizerPlugin(),
            new JsonMinimizerPlugin(),
        ]
    }
};
