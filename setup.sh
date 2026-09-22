#!/bin/bash
# Kilat Print - Setup Script
# Jalankan: bash setup.sh

set -e

PROJECT_DIR="/home/alif-wahyudi-185fps/kilat-print"
cd "$PROJECT_DIR"

echo "================================================"
echo "  Kilat Print - Sistem Informasi Pemesanan   "
echo "  PT SOLUSI PRINT CEPAT                       "
echo "================================================"
echo ""

# 1. Check PHP extensions
echo "[1/6] Checking PHP extensions..."
php -m | grep -q "PDO" && echo "  ✓ PDO" || echo "  ✗ PDO tidak ditemukan"

# 2. Install dependencies
echo ""
echo "[2/6] Installing dependencies..."
composer install --no-interaction

# 3. Generate app key
echo ""
echo "[3/6] Generating app key..."
php artisan key:generate

# 4. Storage link
echo ""
echo "[4/6] Creating storage link..."
php artisan storage:link

# 5. Backup existing migration
echo ""
echo "[5/6] Checking database configuration..."
echo "  Current DB_CONNECTION: $(grep DB_CONNECTION .env | cut -d= -f2)"

# 6. Migration instructions
echo ""
echo "[6/6] Database setup instructions:"
echo ""
echo "OPTION A: MySQL (dengan root password)"
echo "1. Update .env dengan MySQL credentials:"
echo "   DB_CONNECTION=mysql"
echo "   DB_HOST=127.0.0.1"
echo "   DB_PORT=3306"
echo "   DB_DATABASE=db_solusi_print_cepat"
echo "   DB_USERNAME=root"
echo "   DB_PASSWORD=your_password"
echo ""
echo "2. Buat database:"
echo "   mysql -u root -p -e 'CREATE DATABASE IF NOT EXISTS db_solusi_print_cepat;'"
echo ""
echo "3. Jalankan migrations:"
echo "   php artisan migrate:fresh --seed"
echo ""
echo "OPTION B: SQLite (Recommended untuk development)"
echo "1. Update .env:"
echo "   DB_CONNECTION=sqlite"
echo "   DB_DATABASE=/absolute/path/to/database.sqlite"
echo ""
echo "2. Jalankan migrations:"
echo "   php artisan migrate:fresh --seed"
echo ""
echo "================================================"
echo "  Setup Selesai!"
echo "================================================"
echo ""
echo "JALANKAN SERVER:"
echo "  php artisan serve --host=0.0.0.0 --port=8000"
echo ""
echo "URL: http://localhost:8000"
echo ""
