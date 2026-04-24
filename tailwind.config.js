import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'success': '#1BC199',
                'primary': '#E6AD56',
                'links': '#3584e4',
                'info': '#7C817D',
                'warning': '#ECFF8C',
                'dark': '#05060F',
                'Fondo': '#E9EEEA',
                'Blanco': '#FAFafa',
                'light': '#D9D9D9',
                'danger': '#E11D48',
                'azul': '#1E293B',
            },
            fontFamily: {
                sans: ['Montserrat', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        forms,
        require('daisyui'),
    ],

    daisyui: {
        themes: [
            {
                ocre: {
                    // Mapeo de DaisyUI a tus variables originales
                    "primary": "#E6AD56",      // Tu 'primary'
                    "secondary": "#3584e4",    // Tu 'links'
                    "accent": "#1E293B",       // Tu 'azul'
                    "neutral": "#05060F",      // Tu 'dark'
                    "base-100": "#FAFafa",     // Tu 'Blanco' (para el fondo de la card)
                    "info": "#7C817D",         // Tu 'info'
                    "success": "#1BC199",      // Tu 'success'
                    "warning": "#ECFF8C",      // Tu 'warning'
                    "error": "#E11D48",        // Tu 'danger'
                    "light": "#D9D9D9",        // Tu 'light'

                    // Colores de fondo de la página se controlan con base-200/300
                    "base-200": "#E9EEEA",     // Tu 'Fondo'
                },
            },
        ],
    },

};
