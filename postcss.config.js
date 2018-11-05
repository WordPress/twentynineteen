/*jshint esversion: 3 */

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
