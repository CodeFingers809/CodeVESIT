import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                gruvbox: {
                    light: {
                        bg0: '#fbf1c7',
                        bg1: '#ebdbb2',
                        bg2: '#d5c4a1',
                        bg3: '#bdae93',
                        bg4: '#a89984',
                        fg0: '#282828',
                        fg1: '#3c3836',
                        fg2: '#504945',
                        fg3: '#665c54',
                        fg4: '#7c6f64',
                        red: '#cc241d',
                        green: '#98971a',
                        yellow: '#d79921',
                        blue: '#458588',
                        purple: '#b16286',
                        aqua: '#689d6a',
                        orange: '#d65d0e',
                    },
                    dark: {
                        bg0: '#282828',
                        bg1: '#3c3836',
                        bg2: '#504945',
                        bg3: '#665c54',
                        bg4: '#7c6f64',
                        fg0: '#fbf1c7',
                        fg1: '#ebdbb2',
                        fg2: '#d5c4a1',
                        fg3: '#bdae93',
                        fg4: '#a89984',
                        red: '#fb4934',
                        green: '#b8bb26',
                        yellow: '#fabd2f',
                        blue: '#83a598',
                        purple: '#d3869b',
                        aqua: '#8ec07c',
                        orange: '#fe8019',
                    },
                },
            },
        },
    },

    plugins: [forms],
};
