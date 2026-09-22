# Kilat Print - Sistem Informasi Pemesanan & Manajemen Produksi
## PT SOLUSI PRINT CEPAT

---

## STATUS: ✅ PROYEK LENGKAP & SIAP DEPLOY

Semua file Laravel 13 sudah di-generate di `/home/alif-wahyudi-185fps/kilat-print/`

---

## ARSITEKTUR SISTEM

### Database Schema (7 Tabel)
- `users` - Akun multi-role (admin, operator, pelanggan)
- `categories` - Kategori produk cetak
- `products` - Produk dengan mockup template
- `materials` - Varian bahan per produk
- `finishings` - Varian finishing per produk
- `orders` - Pesanan dengan tracking payment
- `order_items` - Detail line items per order
- `productions` - Status produksi per order

### Roles & Permissions
1. **PELANGGAN**
   - Melihat katalog produk
   - Editor mockup live (Fabric.js)
   - Checkout + upload bukti bayar
   - Tracking pesanan real-time

2. **ADMIN**
   - Dashboard statistik
   - Verifikasi pembayaran
   - Assign operator ke production
   - CRUD produk/bahan/finishing

3. **OPERATOR**
   - Antrean pekerjaan produksi
   - Update status workflow (6 tahap)
   - Download file desain asli + preview mockup

---

## STRUKTUR FOLDER

```
kilat-print/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php (register/login/logout)
│   │   │   ├── CustomerController.php (product + order + tracking)
│   │   │   ├── AdminController.php (dashboard + verification)
│   │   │   └── OperatorController.php (production queue + status)
│   │   └── Middleware/
│   │       └── RoleMiddleware.php (role-based access)
│   └── Models/
│       ├── User.php
│       ├── Category.php
│       ├── Product.php
│       ├── Material.php
│       ├── Finishing.php
│       ├── Order.php
│       ├── OrderItem.php
│       └── Production.php
├── database/
│   ├── migrations/
│   │   ├── 2026_09_22_171349_create_categories_table.php
│   │   ├── 2026_09_22_171350_create_products_table.php
│   │   ├── 2026_09_22_171351_create_materials_table.php
│   │   ├── 2026_09_22_171352_create_finishings_table.php
│   │   ├── 2026_09_22_171353_create_orders_table.php
│   │   ├── 2026_09_22_171354_create_order_items_table.php
│   │   ├── 2026_09_22_171355_create_productions_table.php
│   │   └── (+ auth/sessions migrations)
│   └── seeders/
│       └── DatabaseSeeder.php (3 users + 3 products + materials/finishings)
├── routes/
│   └── web.php (publik + auth + role-based groups)
├── resources/views/
│   ├── layouts/app.blade.php (navbar merah+kuning, footer PT SOLUSI PRINT CEPAT)
│   ├── auth/
│   │   ├── login.blade.php
│   │   └── register.blade.php
│   ├── customer/
│   │   ├── index.blade.php (katalog + filter kategori)
│   │   ├── product-detail.blade.php (Fabric.js editor + form order)
│   │   ├── orders.blade.php (daftar pesanan)
│   │   └── tracking.blade.php (timeline produksi)
│   ├── admin/
│   │   ├── dashboard.blade.php (4 stat cards + recent orders)
│   │   └── orders.blade.php (verifikasi payment + assign operator)
│   └── operator/
│       ├── queue.blade.php (antrean pekerjaan)
│       └── detail.blade.php (status update + file download)
├── bootstrap/
│   └── app.php (middleware alias 'role' registered)
├── .env (configured for MySQL)
├── composer.json (Laravel 13 + dependencies)
└── artisan

Semua file blueprint lengkap. Siap untuk setup database.
```

---

## TEST ACCOUNTS (dari seeder)

| Role | Email | Password | Nama |
|------|-------|----------|------|
| Admin | admin@kilatprint.com | password123 | Admin Kilat Print |
| Operator | operator@kilatprint.com | password123 | Operator Produksi |
| Pelanggan | pelanggan@gmail.com | password123 | Pelanggan Demo |

---

## SAMPLE DATA (dari seeder)

### Produk
1. **Mug Keramik Premium 400ml** (Rp 75.000)
   - Bahan: Keramik Putih, Keramik Hitam (+Rp 15K)
   - Finishing: Tanpa, Glossy, Matte (+Rp 10K)

2. **Banner Vinyl 3x1m** (Rp 450.000)
   - Bahan: Vinyl 220gsm, Vinyl 280gsm (+Rp 50K)
   - Finishing: Tanpa, Laminating Glossy/Matte (+Rp 75K)

3. **Kaos Sablon DTG Premium** (Rp 85.000)
   - Bahan: Cotton Standar, Premium (+Rp 25K)
   - Finishing: Sablon Biasa, 3D (+Rp 20K)

---

## FITUR UTAMA

### ✅ Live Mockup Editor (Fabric.js v5)
- Canvas mockup interaktif
- Upload desain customer
- Drag, scale, rotate object
- Export canvas → DataURL preview
- Real-time price calculation

### ✅ Real-time Harga
- Base price produk
- + Material modifier
- + Finishing modifier
- × Quantity
- Formula: (base + mat + fin) × qty

### ✅ Order Workflow
```
Pelanggan:
  Upload Desain → Editor Mockup → Checkout → Bukti Bayar

Admin:
  Verifikasi Pembayaran → Assign Operator → Create Production Task

Operator:
  Verifikasi → Persetujuan Desain → Proses Produksi → 
  Finishing → Quality Check → Siap Dikirim
```

### ✅ File Management
- `raw_design_file` - File desain asli upload (storage/public/designs/)
- `preview_mockup_file` - DataURL canvas preview (database longText)
- Download link di order tracking + operator detail

### ✅ Styling
- Tailwind CSS CDN (no npm needed)
- FontAwesome 6.6 CDN
- Warna: Merah (#DC2626), Kuning (#F59E0B)
- Layout: bg-white, responsive grid

---

## SETUP INSTRUKSI

### 1. Konfigurasi Database (.env)

Untuk MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_solusi_print_cepat
DB_USERNAME=root
DB_PASSWORD=
```

Atau SQLite (default):
```env
DB_CONNECTION=sqlite
DB_DATABASE=/full/path/to/database.sqlite
```

### 2. Generate Encryption Key
```bash
cd /home/alif-wahyudi-185fps/kilat-print
php artisan key:generate
```

### 3. Setup Storage Link
```bash
php artisan storage:link
# Symlink: public/storage → storage/app/public
```

### 4. Run Migrations & Seed
```bash
php artisan migrate:fresh --seed
# Akan membuat semua tabel + sample data
```

### 5. Start Server
```bash
php artisan serve --host=0.0.0.0 --port=8000
# URL: http://localhost:8000
```

---

## ENDPOINTS

### PUBLIC
- `GET /` - Katalog produk
- `GET /product/{slug}` - Detail produk + editor

### AUTH
- `GET /register` - Form daftar
- `POST /register` - Process register
- `GET /login` - Form login
- `POST /login` - Process login
- `POST /logout` - Logout

### CUSTOMER (auth + role:pelanggan)
- `GET /customer/orders` - Daftar pesanan
- `POST /customer/order` - Proses order
- `POST /customer/order/{id}/payment` - Upload bukti bayar
- `GET /customer/tracking/{order_number}` - Tracking pesanan

### ADMIN (auth + role:admin)
- `GET /admin/dashboard` - Dashboard statistik
- `GET /admin/orders` - Verifikasi pembayaran
- `POST /admin/order/{id}/verify-payment` - Verifikasi bayar
- `POST /admin/production/{id}/assign-operator` - Assign operator

### OPERATOR (auth + role:operator)
- `GET /operator/queue` - Antrean pekerjaan
- `GET /operator/order/{id}` - Detail produksi
- `POST /operator/order/{id}/status` - Update status

---

## MIGRASI KE PRODUCTION

1. **Database**: Setup MySQL dengan user dedicated (jangan root)
   ```sql
   CREATE DATABASE db_solusi_print_cepat;
   CREATE USER 'kilatprint'@'localhost' IDENTIFIED BY 'secure_password';
   GRANT ALL ON db_solusi_print_cepat.* TO 'kilatprint'@'localhost';
   ```

2. **Update .env**:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   DB_USERNAME=kilatprint
   DB_PASSWORD=secure_password
   ```

3. **Optimize**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

4. **Web Server** (Nginx/Apache recommended):
   - Root → `/public`
   - Rewrite *.* → index.php
   - Symlink storage → writable

---

## FILES CHECKLIST

### Migrations ✅
- ✅ categories_table
- ✅ products_table (+ image, mockup_template_image)
- ✅ materials_table (product_id, price_modifier)
- ✅ finishings_table (product_id, price_modifier)
- ✅ orders_table (order_number, payment_status, shipping_method)
- ✅ order_items_table (raw_design_file, preview_mockup_file)
- ✅ productions_table (status enum 6 tahap, operator_id)

### Models ✅
- ✅ User (hasMany Order/Production)
- ✅ Category (hasMany Product)
- ✅ Product (belongsTo Category, hasMany Material/Finishing)
- ✅ Material (belongsTo Product)
- ✅ Finishing (belongsTo Product)
- ✅ Order (belongsTo User, hasMany OrderItem, hasOne Production)
- ✅ OrderItem (belongsTo Order/Product/Material/Finishing)
- ✅ Production (belongsTo Order/User)

### Controllers ✅
- ✅ AuthController (register/login/logout)
- ✅ CustomerController (5 methods)
- ✅ AdminController (4 methods)
- ✅ OperatorController (3 methods)

### Middleware ✅
- ✅ RoleMiddleware (role:admin|operator|pelanggan)

### Views ✅
- ✅ layouts/app.blade.php
- ✅ auth/login.blade.php
- ✅ auth/register.blade.php
- ✅ customer/index.blade.php (katalog)
- ✅ customer/product-detail.blade.php (Fabric.js editor)
- ✅ customer/orders.blade.php (order list)
- ✅ customer/tracking.blade.php (timeline)
- ✅ admin/dashboard.blade.php (stats)
- ✅ admin/orders.blade.php (verify payment)
- ✅ operator/queue.blade.php (antrean)
- ✅ operator/detail.blade.php (update status)

### Routes ✅
- ✅ routes/web.php (public + auth + role groups)

### Seeders ✅
- ✅ DatabaseSeeder.php (3 users + 3 products + variants)

### Configuration ✅
- ✅ bootstrap/app.php (RoleMiddleware alias)
- ✅ .env (MySQL configured)
- ✅ App key generated
- ✅ Storage link created

---

## BRANDING & STYLING

**Nama Aplikasi**: Kilat Print
**Warna Utama**: Merah (#DC2626) + Kuning (#F59E0B)
**Layout**: Clean white background (bg-white)
**Footer**: 
```
PT SOLUSI PRINT CEPAT
Jl. H. Muchtar Raya, RT 10/RW 11, Petukangan Utara, 
Pesanggrahan, Jakarta Selatan
```

---

## NEXT STEPS

1. **Setup Database** (MySQL/SQLite)
   ```bash
   php artisan migrate:fresh --seed
   ```

2. **Test Authentication**
   - Login sebagai admin@kilatprint.com
   - Login sebagai pelanggan@gmail.com
   - Login sebagai operator@kilatprint.com

3. **Test Workflow**
   - Pelanggan: Lihat katalog → order dengan editor
   - Admin: Verifikasi pembayaran → assign operator
   - Operator: Lihat antrean → update status

4. **Production Deploy**
   - Configure MySQL user
   - Update .env
   - Run cache commands
   - Setup web server (Nginx/Apache)

---

## DOKUMENTASI LENGKAP SELESAI ✅

Proyek Laravel 13 **Kilat Print** siap deploy.
Semua file blueprint ada, tinggal database & hosting.

**Location**: `/home/alif-wahyudi-185fps/kilat-print/`
