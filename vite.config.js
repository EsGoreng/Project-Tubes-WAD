import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    server: {
            host: '0.0.0.0', // Membuka akses agar bisa dijangkau dari luar container
            port: 5173,
            hmr: {
                host: 'localhost', // Memberitahu browser untuk mencari HMR di localhost
            },
            watch: {
                usePolling: true, // WAJIB untuk pengguna Windows agar perubahan file terdeteksi
            },
        },
    plugins: [
        tailwindcss(),
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
});
