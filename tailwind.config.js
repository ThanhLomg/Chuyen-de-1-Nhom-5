import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: ['./resources/**/*.blade.php', './resources/**/*.js'],
    theme: {
        extend: {
            colors: {
                primary: {
                    DEFAULT: '#2563eb', // blue-600
                    dark: '#1d4ed8',     // blue-700
                    light: '#3b82f6',    // blue-500
                },
            },
        },
    },
    plugins: [require('@tailwindcss/forms')],
}