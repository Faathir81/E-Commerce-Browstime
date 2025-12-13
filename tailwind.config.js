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
        },
    },

    plugins: [forms],

    safelist: [
        // Product card add-to-cart button styles coming from view models (not visible to the scanner)
        'w-full',
        'flex',
        'items-center',
        'justify-center',
        'gap-2',
        'text-white',
        'text-sm',
        'py-2',
        'rounded-xl',
        'transition',
        'bg-[#3b241a]',
        'hover:bg-[#2c1c14]',
        'bg-gray-400',
        'cursor-not-allowed',
    ],
};
