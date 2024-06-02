// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    server: { https: true }, // Not needed for Vite 5+
    build: {
        outDir: 'public/build', // Menentukan direktori hasil kompilasi
        manifest: true, // Menghasilkan berkas manifest.json
    },
});
