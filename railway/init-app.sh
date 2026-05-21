#!/bin/bash
set -e

# 1. Pastikan dependencies terinstall jika folder vendor kosong
if [ ! -d "vendor" ]; then
    echo "Folder vendor tidak ditemukan, menjalankan composer install..."
    composer install --no-dev --optimize-autoloader --no-interaction
fi

# 2. Jalankan migrasi database
echo "Menjalankan migrasi database..."
php artisan migrate --force

# 3. Optimasi cache Laravel
echo "Mengoptimalkan cache..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Jalankan web server bawaan Railway (Caddy + PHP-FPM)
echo "Memulai aplikasi..."
# Perintah di bawah ini adalah command default Railway untuk menjalankan app
exec caddy run --config /etc/caddy/Caddyfile --adapter caddyfile