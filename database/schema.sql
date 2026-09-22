-- Kilat Print - Manual SQL Schema
-- PT SOLUSI PRINT CEPAT
-- Created: 2026-09-22

-- Execute di MySQL/MariaDB setelah membuat database:
-- mysql> CREATE DATABASE db_solusi_print_cepat CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- mysql> USE db_solusi_print_cepat;
-- mysql> source /path/to/schema.sql;

-- ===================================
-- USERS (Role: admin, operator, pelanggan)
-- ===================================
CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    no_hp VARCHAR(20),
    role ENUM('admin', 'pelanggan', 'operator') DEFAULT 'pelanggan',
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100),
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_email (email),
    KEY idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- CATEGORIES
-- ===================================
CREATE TABLE IF NOT EXISTS categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- PRODUCTS (dengan mockup template)
-- ===================================
CREATE TABLE IF NOT EXISTS products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description LONGTEXT,
    base_price DECIMAL(12,2) NOT NULL,
    image VARCHAR(255),
    mockup_template_image VARCHAR(255),
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_product_category FOREIGN KEY (category_id) 
        REFERENCES categories(id) ON DELETE CASCADE,
    KEY idx_slug (slug),
    KEY idx_category (category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- MATERIALS (varian bahan per produk)
-- ===================================
CREATE TABLE IF NOT EXISTS materials (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    price_modifier DECIMAL(12,2) DEFAULT 0,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_material_product FOREIGN KEY (product_id) 
        REFERENCES products(id) ON DELETE CASCADE,
    KEY idx_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- FINISHINGS (varian finishing per produk)
-- ===================================
CREATE TABLE IF NOT EXISTS finishings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    price_modifier DECIMAL(12,2) DEFAULT 0,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_finishing_product FOREIGN KEY (product_id) 
        REFERENCES products(id) ON DELETE CASCADE,
    KEY idx_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- ORDERS (Pesanan dengan tracking payment)
-- ===================================
CREATE TABLE IF NOT EXISTS orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(255) UNIQUE NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    total_price DECIMAL(12,2) NOT NULL,
    payment_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    payment_proof VARCHAR(255),
    shipping_method ENUM('pickup', 'delivery') DEFAULT 'pickup',
    delivery_address LONGTEXT,
    notes LONGTEXT,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_order_user FOREIGN KEY (user_id) 
        REFERENCES users(id) ON DELETE CASCADE,
    KEY idx_order_number (order_number),
    KEY idx_payment_status (payment_status),
    KEY idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- ORDER_ITEMS (Detail pesanan)
-- ===================================
CREATE TABLE IF NOT EXISTS order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    material_id BIGINT UNSIGNED,
    finishing_id BIGINT UNSIGNED,
    quantity INT NOT NULL,
    custom_width DECIMAL(8,2),
    custom_height DECIMAL(8,2),
    raw_design_file VARCHAR(255),
    preview_mockup_file LONGTEXT,
    subtotal DECIMAL(12,2) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_item_order FOREIGN KEY (order_id) 
        REFERENCES orders(id) ON DELETE CASCADE,
    CONSTRAINT fk_item_product FOREIGN KEY (product_id) 
        REFERENCES products(id) ON DELETE CASCADE,
    CONSTRAINT fk_item_material FOREIGN KEY (material_id) 
        REFERENCES materials(id) ON DELETE SET NULL,
    CONSTRAINT fk_item_finishing FOREIGN KEY (finishing_id) 
        REFERENCES finishings(id) ON DELETE SET NULL,
    KEY idx_order (order_id),
    KEY idx_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- PRODUCTIONS (Status produksi 6 tahap)
-- ===================================
CREATE TABLE IF NOT EXISTS productions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    operator_id BIGINT UNSIGNED,
    status ENUM(
        'verifikasi', 
        'persetujuan_desain', 
        'proses_produksi', 
        'finishing', 
        'quality_check', 
        'siap_dikirim'
    ) DEFAULT 'verifikasi',
    notes LONGTEXT,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_production_order FOREIGN KEY (order_id) 
        REFERENCES orders(id) ON DELETE CASCADE,
    CONSTRAINT fk_production_operator FOREIGN KEY (operator_id) 
        REFERENCES users(id) ON DELETE SET NULL,
    KEY idx_order (order_id),
    KEY idx_operator (operator_id),
    KEY idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- MIGRATIONS (Laravel internal)
-- ===================================
CREATE TABLE IF NOT EXISTS migrations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- SAMPLE DATA
-- ===================================

-- Insert Users (admin, operator, pelanggan)
INSERT INTO users (name, email, password, no_hp, role) VALUES
('Admin Kilat Print', 'admin@kilatprint.com', '$2y$12$XQGhvb5F.P/5EkH/n7XrXO8X5p5Pt5Y5Y5Y5Y5Y5Y5Y5Y5Y5Y5Y5Y', '081234567890', 'admin'),
('Operator Produksi', 'operator@kilatprint.com', '$2y$12$XQGhvb5F.P/5EkH/n7XrXO8X5p5Pt5Y5Y5Y5Y5Y5Y5Y5Y5Y5Y5Y5Y', '081987654321', 'operator'),
('Pelanggan Demo', 'pelanggan@gmail.com', '$2y$12$XQGhvb5F.P/5EkH/n7XrXO8X5p5Pt5Y5Y5Y5Y5Y5Y5Y5Y5Y5Y5Y5Y', '085555555555', 'pelanggan');

-- Insert Categories
INSERT INTO categories (name, slug) VALUES
('Mug Custom', 'mug-custom'),
('Banner', 'banner'),
('Kaos Sablon', 'kaos-sablon');

-- Insert Products
INSERT INTO products (category_id, name, slug, description, base_price) VALUES
(1, 'Mug Keramik Premium 400ml', 'mug-keramik-premium-400ml', 'Mug berkualitas tinggi dari keramik pilihan, cocok untuk hadiah atau merchandise', 75000.00),
(2, 'Banner Vinyl 3x1m', 'banner-vinyl-3x1m', 'Banner vinyl tahan cuaca, cetak digital full color resolusi tinggi', 450000.00),
(3, 'Kaos Sablon DTG Premium', 'kaos-sablon-dtg-premium', 'Kaos 100% cotton dengan sablon DTG berkualitas tinggi', 85000.00);

-- Insert Materials
INSERT INTO materials (product_id, name, price_modifier) VALUES
(1, 'Keramik Putih', 0.00),
(1, 'Keramik Hitam', 15000.00),
(2, 'Vinyl 220gsm', 0.00),
(2, 'Vinyl 280gsm', 50000.00),
(3, 'Cotton Standar', 0.00),
(3, 'Cotton Premium', 25000.00);

-- Insert Finishings
INSERT INTO finishings (product_id, name, price_modifier) VALUES
(1, 'Tanpa Finishing', 0.00),
(1, 'Glossy', 10000.00),
(1, 'Matte', 10000.00),
(2, 'Tanpa Finishing', 0.00),
(2, 'Laminating Glossy', 75000.00),
(2, 'Laminating Matte', 75000.00),
(3, 'Sablon Biasa', 0.00),
(3, 'Sablon 3D', 20000.00);

-- ===================================
-- Sample Orders (optional - untuk testing)
-- ===================================
-- INSERT INTO orders (order_number, user_id, total_price, payment_status, shipping_method)
-- VALUES ('ORD-20260922-0001', 3, 100000.00, 'pending', 'pickup');

-- END OF SCHEMA
