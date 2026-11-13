#!/bin/bash
set -e

# Buat .env jika belum ada
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Generate APP_KEY jika belum ada
if ! grep -q "APP_KEY=base64:" .env; then
    php artisan key:generate --force
fi

# Pastikan file SQLite siap jika menggunakan koneksi sqlite
if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
    mkdir -p database
    touch database/database.sqlite
    chown -R www-data:www-data database
    chmod -R 775 database
fi

# Pastikan permission storage & cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Bersihkan cache
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Migrasi dan seeding database secara otomatis
php artisan migrate --force
php artisan db:seed --force

exec "$@"
