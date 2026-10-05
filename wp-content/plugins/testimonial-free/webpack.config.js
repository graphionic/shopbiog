const defaultConfig = require("@wordpress/scripts/config/webpack.config");
const path = require("path");

module.exports = {
    ...defaultConfig,
    output: {
        ...defaultConfig.output,
        clean: false, // Prevent webpack from cleaning dist directory
    },
    resolve: {
        ...defaultConfig.resolve,
        alias: {
            ...defaultConfig.resolve.alias,
            "@testimonial/components": path.resolve(__dirname, "app/components/"),
            "@testimonial/controls": path.resolve(__dirname, "app/controls/Controls.jsx"),
            "@testimonial/constants": path.resolve(__dirname, "app/constants/"),
            "@testimonial/templates": path.resolve(__dirname, "app/templates/"),
            "@testimonial/hooks": path.resolve(__dirname, "app/hooks/"),
            "@testimonial/icons": path.resolve(__dirname, "app/icons/"),
            "@testimonial/context": path.resolve(__dirname, "app/context/"),
        },
    },
};