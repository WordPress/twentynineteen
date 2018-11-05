/*jshint esversion: 6 */

const postcssFocusWithin = require('postcss-focus-within');

module.exports = {
    plugins: {
        autoprefixer: {}
    }
};

module.exports = {
    plugins: [
        postcssFocusWithin(/* pluginOptions */)
    ]
};
