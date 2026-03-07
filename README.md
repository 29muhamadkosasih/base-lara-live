# Base Lara Live

Starter project Laravel + Livewire + Spatie Permission yang sudah dilengkapi data awal agar bisa langsung dipakai setelah install.

## Fitur Data Awal

Setelah menjalankan seeder, project akan otomatis memiliki:

-   role `Admin`
-   default permissions untuk `Product`, `User`, `Role`, `Permission`, dan `Setting App`
-   akun admin default
-   data setting aplikasi default
-   contoh data product

## Default Login

-   Email: `admin@gmail.com`
-   Password: `password`

## Cara Install

### 1. Clone project

```sh
git clone <repository-url>
cd base-lara-live
```

### 2. Install dependency PHP

```sh
composer install
```

### 3. Install dependency frontend

```sh
npm install
```

### 4. Copy file environment

Linux / macOS:

```sh
cp .env.example .env
```

Windows CMD:

```bat
copy .env.example .env
```

### 5. Atur konfigurasi database di `.env`

Sesuaikan bagian berikut:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=base_lara_live
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Generate application key

```sh
php artisan key:generate
```

### 7. Jalankan migration dan seeder

```sh
php artisan migrate --seed
```

Jika ingin reset database sekaligus isi ulang data awal:

```sh
php artisan migrate:fresh --seed
```

### 8. Buat storage link

```sh
php artisan storage:link
```

### 9. Compile asset frontend

Untuk development:

```sh
npm run dev
```

Untuk production:

```sh
npm run build
```

### 10. Jalankan server

```sh
php artisan serve
```

Project akan berjalan di:

```text
http://127.0.0.1:8000
```

## Seeder yang Tersedia

-   `PermissionTableSeeder`
-   `CreateAdminUserSeeder`
-   `SettingAppSeeder`
-   `ProductSeeder`

Semua seeder sudah dibuat aman untuk dijalankan ulang karena memakai pola `firstOrCreate()` atau `updateOrCreate()`.

## Perintah Penting

Reset cache permission:

```sh
php artisan permission:cache-reset
```

Reset database dan isi ulang data:

```sh
php artisan migrate:fresh --seed
```

## Catatan

-   Pastikan database sudah dibuat sebelum menjalankan migration.
-   Jika logo aplikasi tidak tampil, pastikan folder storage sudah di-link dengan `php artisan storage:link`.
-   Akun admin default bisa langsung dipakai setelah seeding berhasil.

Happy coding 🚀
