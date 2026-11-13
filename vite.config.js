import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        })
    ],
    experimental: {
        // CSS and the assets it references (fonts) live together in public/build/assets,
        // so url()s inside CSS must be relative; otherwise the build bakes in the
        // ASSET_URL of whichever .env it ran with (e.g. http://localhost:8000).
        renderBuiltUrl(_filename, { hostType }) {
            if (hostType === 'css') {
                return { relative: true };
            }
        },
    },
});
