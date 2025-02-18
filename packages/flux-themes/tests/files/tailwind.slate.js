const colors = require('tailwindcss/colors');
/** @type {import('tailwindcss').Config} */
export default {
    content: [
		'./vendor/artisan-build/**/resources/**/*.blade.php',
		'./vendor/livewire/flux/stubs/**/*.blade.php',
		'./vendor/livewire/flux-pro/stubs/**/*.blade.php',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
			colors: {
                zinc: colors.slate,
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
    },
    plugins: [],
};
