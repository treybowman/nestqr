import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#f3f0ff',
                    100: '#e9e3ff',
                    200: '#d4c9ff',
                    300: '#b49dff',
                    400: '#9b7bff',
                    500: '#8e63f5',
                    600: '#7c3eed',
                    700: '#6b2dd4',
                    800: '#5925b0',
                    900: '#4a2090',
                    950: '#2d1161',
                },
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [forms, typography],
};
