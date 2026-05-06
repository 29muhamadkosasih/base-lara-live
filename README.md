# Base Lara Live 🚀

Starter project professional **Laravel + Livewire + Spatie Permission** yang sudah dilengkapi dengan fitur lengkap dan siap production.

## ✨ Fitur Utama

### 📊 Dashboard & Analytics

-   Overview statistik User, Product, Role, Permission
-   Audit Log tracking untuk setiap perubahan data
-   Activity timeline dengan informasi lengkap

### 👥 User Management

-   Create, Read, Update, Delete User
-   Assign multiple roles per user
-   User profile management
-   Password management

### 🏷️ Product Management

-   CRUD Products dengan image upload
-   Cover image support dengan auto-cleanup temporary files
-   Responsive product list dengan pagination
-   Advanced search & filter

### 🔐 Role & Permission Management

-   CRUD Roles dengan permission assignment
-   CRUD Permissions dengan categorization
-   Permission matrix visibility
-   Fine-grained access control

### 📝 Audit Logging

-   Complete audit trail untuk semua aktivitas
-   Track user, action, timestamp, dan details
-   Export audit logs untuk reporting
-   Real-time activity monitoring

### 🎯 Additional Features

-   Show entries (10, 15, 25, 50, All) di setiap table
-   Real-time search & filter
-   Bootstrap 5 responsive design
-   Flash notifications
-   Livewire real-time validation
-   File management dengan secure upload

## 🗄️ Database Schema

### Tables

-   `users` - User accounts dengan roles
-   `products` - Product catalog dengan cover image
-   `roles` - Role definitions (Spatie)
-   `permissions` - Permission definitions (Spatie)
-   `setting_apps` - Application settings
-   `audit_logs` - Activity logging
-   `password_reset_tokens` - Password reset tokens
-   `failed_jobs` - Failed job tracking
-   `personal_access_tokens` - API tokens

## 📋 Default Credentials

Setelah seeding, gunakan akun berikut untuk login:

-   **Email**: `admin@gmail.com`
-   **Password**: `password123`
-   **Role**: Admin (dengan semua permissions)

## 🚀 Instalasi Cepat

### 1. Clone Repository

```bash
git clone <repository-url>
cd base-lara-live
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi Database (.env)

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Migration & Seeding

```bash
php artisan migrate --seed
```

**Atau jika ingin reset database:**

```bash
php artisan migrate:fresh --seed
```

### 6. Storage Link

```bash
php artisan storage:link
```

### 7. Build Frontend Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 8. Start Server

```bash
php artisan serve
```

Akses aplikasi di: `http://127.0.0.1:8000`

## 📁 Struktur Project

```
base-lara-live/
├── app/
│   ├── Livewire/
│   │   ├── Product/Index.php          (Product CRUD)
│   │   ├── User/Index.php             (User Management)
│   │   ├── Role/Index.php             (Role Management)
│   │   ├── Permission/Index.php       (Permission Management)
│   │   └── AuditLog/Index.php         (Audit Logs)
│   ├── Models/
│   │   ├── Product.php
│   │   ├── User.php
│   │   ├── SettingApp.php
│   │   └── AuditLog.php
│   └── Support/
│       └── AuditLogger.php            (Logging utility)
├── database/
│   ├── migrations/                    (9 migrations)
│   └── seeders/                       (5 seeders)
├── resources/views/
│   ├── livewire/
│   │   ├── product/
│   │   ├── user/
│   │   ├── role/
│   │   ├── permission/
│   │   └── audit-log/
│   └── layouts/
└── routes/
    ├── web.php
    └── api.php
```

## 🔧 Fitur Per Modul

### Product Management

✅ Create dengan cover image upload
✅ Edit dengan image replacement & auto-delete old file
✅ Delete dengan cascading image deletion
✅ Show entries pagination (10, 15, 25, 50, All)
✅ Real-time search & filter
✅ Audit logging untuk semua perubahan

### User Management

✅ CRUD User
✅ Multiple role assignment
✅ Password management
✅ Activity tracking
✅ Email unique constraint

### Role Management

✅ CRUD Role
✅ Permission assignment per role
✅ View role permissions matrix
✅ Bulk permission assignment

### Permission Management

✅ CRUD Permission
✅ Permission categorization
✅ Guard-based permissions
✅ Role-permission linking

### Audit Logging

✅ Automatic audit trail
✅ User tracking
✅ Action logging
✅ Detailed change recording
✅ Export capability

## 📦 Tech Stack

### Backend

-   Laravel 11
-   Livewire 3 (Real-time UI)
-   Spatie Laravel Permission 6 (ACL)
-   MySQL / MariaDB

### Frontend

-   Bootstrap 5
-   Tabler Icons
-   Alpine.js (Included with Livewire)

## ⚙️ Configuration

### Key Files

-   `config/permission.php` - Spatie Permission config
-   `config/app.php` - Application config
-   `.env` - Environment variables
-   `phpunit.xml` - Testing config

## 🔒 Security Features

-   Laravel sanctum untuk API auth
-   Spatie permission untuk fine-grained access control
-   CSRF protection pada semua forms
-   SQL injection prevention
-   XSS protection dengan Blade escaping
-   File upload validation & sanitization
-   Secure password hashing dengan bcrypt

## 📊 Available Seeds

1. **PermissionTableSeeder** - 18 permissions untuk 5 modules
2. **CreateAdminUserSeeder** - Admin user dengan semua permissions
3. **SettingAppSeeder** - Application settings
4. **ProductSeeder** - 3 sample products dengan cover image
5. **AuditLogSeeder** - Kosong (untuk safety)

**Note**: Semua seeder menggunakan `firstOrCreate()` atau `updateOrCreate()`, aman untuk dijalankan berkali-kali.

## 🔄 Useful Commands

```bash
# Clear application cache
php artisan cache:clear

# Reset permissions cache
php artisan permission:cache-reset

# Fresh migration + seed
php artisan migrate:fresh --seed

# Reset database
php artisan migrate:reset

# Rollback 1 migration
php artisan migrate:rollback

# Interactive shell
php artisan tinker

# Run tests
php artisan test

# Clean temporary files
rm -rf storage/app/livewire-tmp/*
```

## 🐛 Troubleshooting

### Logo tidak tampil

```bash
php artisan storage:link
```

### Permission cache tidak ter-update

```bash
php artisan permission:cache-reset
```

### Database error

-   Pastikan database sudah dibuat
-   Check `.env` database configuration
-   Run `php artisan migrate:fresh --seed`

### File upload error

-   Pastikan folder `public/storage/uploads/cover_images` writable
-   Check file permissions: `chmod -R 755 public/storage/`

## 📝 Code Quality Standards

-   ✅ Clean code dengan proper type hints
-   ✅ Separation of concerns (validation rules, helpers)
-   ✅ Comprehensive PHPDoc documentation
-   ✅ Consistent naming convention
-   ✅ DRY (Don't Repeat Yourself) principle
-   ✅ Professional code structure
-   ✅ Reusable components & methods

## 🚀 Production Deployment

1. Set `APP_ENV=production` di `.env`
2. Set `APP_DEBUG=false` di `.env`
3. Generate new `APP_KEY`
4. Compile assets: `npm run build`
5. Clear cache: `php artisan config:cache`
6. Setup proper file permissions
7. Configure `.env` untuk production
8. Setup database backups
9. Monitor logs: `storage/logs/laravel.log`

## 📚 Documentation

Untuk dokumentasi lengkap:

-   [Laravel Documentation](https://laravel.com/docs)
-   [Livewire Documentation](https://livewire.laravel.com)
-   [Spatie Permission](https://spatie.be/docs/laravel-permission)

## 📄 License

Project ini tersedia under the MIT License.

## 👨‍💻 Developer

Dikembangkan sebagai starter project professional untuk rapid development dengan Laravel + Livewire + Spatie Permission.

## 🤝 Contributing

Untuk kontribusi, silahkan fork repository dan buat pull request.

---

**Happy Coding!** 🎉

Jika ada pertanyaan atau issue, silahkan buat issue di repository.
