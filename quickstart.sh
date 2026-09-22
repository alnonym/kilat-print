#!/bin/bash
# Kilat Print - Quick Start untuk Development & Demo

PROJECT_DIR="/home/alif-wahyudi-185fps/kilat-print"
cd "$PROJECT_DIR"

echo "========================================="
echo "  Kilat Print - Quick Start Setup"
echo "========================================="
echo ""

# Option 1: Development dengan SQLite in-memory
if [ "$1" == "demo" ]; then
    echo "[DEMO MODE] Using SQLite in-memory..."
    
    # Create empty SQLite database
    touch database/app.sqlite
    
    # Update .env
    sed -i 's/DB_CONNECTION=.*/DB_CONNECTION=sqlite/g' .env
    sed -i 's|DB_DATABASE=.*|DB_DATABASE=database/app.sqlite|g' .env
    
    echo "✓ Database configured: SQLite (database/app.sqlite)"
    echo ""
    echo "Running migrations..."
    php artisan migrate:fresh --seed --force 2>&1 | tail -10
    
    echo ""
    echo "========================================="
    echo "  Starting Laravel Development Server"
    echo "========================================="
    echo ""
    php artisan serve --host=0.0.0.0 --port=8000
    
elif [ "$1" == "setup-mysql" ]; then
    echo "[PRODUCTION] Setting up MySQL..."
    echo ""
    echo "Step 1: Create MySQL user and database"
    echo "========================================"
    echo ""
    echo "Run these commands as root:"
    echo ""
    echo "  mariadb -u root"
    echo ""
    echo "Then in MariaDB prompt:"
    cat << 'SQL'
CREATE DATABASE db_solusi_print_cepat CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'kilatprint'@'localhost' IDENTIFIED BY 'SecurePass123!';
GRANT ALL PRIVILEGES ON db_solusi_print_cepat.* TO 'kilatprint'@'localhost';
FLUSH PRIVILEGES;
EXIT;
SQL
    echo ""
    echo "Step 2: Update .env file"
    echo "========================================"
    echo ""
    echo "Edit .env and set:"
    echo "  DB_CONNECTION=mysql"
    echo "  DB_HOST=127.0.0.1"
    echo "  DB_PORT=3306"
    echo "  DB_DATABASE=db_solusi_print_cepat"
    echo "  DB_USERNAME=kilatprint"
    echo "  DB_PASSWORD=SecurePass123!"
    echo ""
    echo "Step 3: Run migrations"
    echo "========================================"
    echo ""
    echo "  php artisan migrate:fresh --seed"
    echo ""
    
else
    echo "Usage:"
    echo "  bash quickstart.sh demo       # Run with SQLite (Development)"
    echo "  bash quickstart.sh setup-mysql # Setup instructions for MySQL"
    echo ""
fi
