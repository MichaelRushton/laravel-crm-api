import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import tailwindcss from "@tailwindcss/vite";
import vueDevTools from "vite-plugin-vue-devtools";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/js/app.js"],
            refresh: true,
        }),
        vue(),
        tailwindcss(),
        vueDevTools(),
    ],
    server: {
        host: "0.0.0.0",
        hmr: {
            host: process.env.HMR_HOST || "localhost",
        },
    },
});
