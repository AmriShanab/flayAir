import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    // Keeps the base path correct for web access
    base: '/', 
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(), // Assuming you need this plugin based on your original snippet
    ],
    build: {
    outDir: 'public/build',
    emptyOutDir: true,
}

});
