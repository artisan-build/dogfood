const colors = require('tailwindcss/colors');
import defaultTheme from 'tailwindcss/defaultTheme';
/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'selector',
    content: [
        './packages/**/resources/**/*.blade.php',
		'./vendor/artisan-build/**/resources/**/*.blade.php',
		'./vendor/livewire/flux/stubs/**/*.blade.php',
		'./vendor/livewire/flux-pro/stubs/**/*.blade.php',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
			colors: {
                zinc: colors.neutral,
                accent: {
                    DEFAULT: 'var(--color-accent)',
                    content: 'var(--color-accent-content)',
                    foreground: 'var(--color-accent-foreground)',
                },
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    }
};
