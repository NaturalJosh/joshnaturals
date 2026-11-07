import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({ /* keep your existing settings */ }),
        vue(),
        tailwindcss(),
    ],
    // keep your server block
});