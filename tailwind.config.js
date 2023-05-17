const defaultTheme = require('tailwindcss/defaultTheme');
const defaultColors = require('tailwindcss/colors');


/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue'
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Mulish', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                background: {
                    primary: '#ffe0cb',
                    secondary: '#663d29',
                    contrast: defaultColors.orange["200"],
                    light: '#ffece2',
                }
            }
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
