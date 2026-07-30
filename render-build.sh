#!/usr/bin/env bash
# render-build.sh - Script build otomatis untuk Render.com

set -o errexit

# Install Composer dependencies
composer install --no-dev --optimize-autoloader

# Generate application key jika belum ada
php artisan key:generate --force

# Clear & cache config
php artisan config:clear
php artisan config:cache

# Run database migrations
php artisan migrate --force

# (Opsional) Jalankan seeder untuk data awal
# php artisan db:seed --force
