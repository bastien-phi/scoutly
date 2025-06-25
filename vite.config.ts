import tailwindcss from '@tailwindcss/vite';
import react from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';
import { resolve } from 'node:path';
import { defineConfig } from 'vite';
import svgr from "vite-plugin-svgr";
import run from 'vite-plugin-run';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.tsx'],
            ssr: 'resources/js/ssr.tsx',
            refresh: true,
        }),
        react(),
        tailwindcss(),
        svgr(),
        run([
            {
              name: 'typescript transform',
              run: ['php', 'artisan', 'typescript:transform'],
              pattern: ['app/Data/**/*Data.php', 'app/Enums/**/*.php'],
            },
            {
              name: 'ziggy route list',
              run: ['php', 'artisan', 'ziggy:generate', '--types-only', 'resources/js/types/ziggy.d.ts'],
              pattern: ['routes/**/*.php'],
            }
        ]),
    ],
    esbuild: {
        jsx: 'automatic',
    },
    resolve: {
        alias: {
            'ziggy-js': resolve(__dirname, 'vendor/tightenco/ziggy'),
            '@assets': resolve(__dirname, './resources/assets'),
        },
    },
});
