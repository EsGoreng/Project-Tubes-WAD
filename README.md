# SiBersih - Sistem Manajemen Laundry

![Logo Proyek](public/images/logo_main.png)

Aplikasi kasir dan tracking berbasis web ini dirancang untuk menertibkan manajemen usaha laundry melalui pencatatan transaksi dan pemantauan status pesanan secara real-time. Fitur cek status mandiri memungkinkan pelanggan memantau progres (cuci/setrika/selesai) tanpa perlu menghubungi admin, sehingga mengurangi beban komunikasi. Aplikasi ini juga terintegrasi dengan API cuaca untuk membantu estimasi waktu pengerjaan, terutama pada proses pengeringan yang dipengaruhi kondisi cuaca.

Dokumentasi instalasi dan pengaturan proyek **SiBersih**, sebuah aplikasi manajemen laundry berbasis web yang dibangun menggunakan Laravel dan Filament.

## Prasyarat

Sebelum memulai, pastikan perangkat kamu sudah terinstal:

-   [PHP](https://www.php.net/)
-   [Composer](https://getcomposer.org/)
-   [Node.js & NPM](https://nodejs.org/)
-   Web Server (Laragon/XAMPP)
-   Database (MySQL/MariaDB)

---

## Langkah Instalasi

Ikuti langkah-langkah berikut secara berurutan untuk menjalankan proyek ini di lingkungan lokal (local environment).

### 1. Clone Repository

Unduh source code proyek dari GitHub.

```bash
git clone [https://github.com/EsGoreng/Project-Tubes-WAD](https://github.com/EsGoreng/Project-Tubes-WAD)
cd Project-Tubes-WAD

```

### 2. Install Dependensi Backend

Jalankan perintah berikut untuk mengunduh semua library PHP yang dibutuhkan.

```bash
composer install

```

### 3. Konfigurasi Environment (.env)

Salin file contoh konfigurasi dan buat file `.env` baru.

```bash
cp .env.example .env

```

Buka file `.env` kamu dan sesuaikan konfigurasi berikut:

**A. Kunci Aplikasi & Nama**
Generate key baru dan ubah nama aplikasi.

```bash
php artisan key:generate

```

Ubah baris berikut di `.env`:

```dotenv
APP_NAME=SiBersih

```

**B. Konfigurasi Database**
Pastikan kamu telah membuat database kosong bernama `sistem_laundry_db` di phpMyAdmin atau SQL client kamu, lalu sesuaikan `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1 //kalau pake docker isi dengan 'mysql'
DB_PORT=3306
DB_DATABASE=sistem_laundry_db
DB_USERNAME=root
DB_PASSWORD= //kalau pake docker isi dengan 'secret'

```

**C. Konfigurasi Queue**
Ubah koneksi antrian menjadi sync:

```dotenv
QUEUE_CONNECTION=sync

```

**D. Konfigurasi Mailer (SMTP)**
Untuk fitur pengiriman email, sesuaikan dengan kredensial SMTP (contoh menggunakan Gmail):

```dotenv
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=uremail@gmail.com
MAIL_PASSWORD=masukkan_password_app_anda_disini
MAIL_FROM_ADDRESS="uremail@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"

```

### 4. Setup Database & Seeding

Jalankan migrasi untuk membuat tabel dan mengisi data dummy (seeder).

```bash
php artisan migrate:fresh --seed

```

### 5. Setup Frontend (Tailwind CSS)

Instal dependensi untuk Tailwind CSS dan Vite.

```bash
npm install tailwindcss @tailwindcss/vite --save-dev

```

### 6. Setup Filament (Admin Panel)

Install paket-paket Filament beserta plugin tambahannya (Excel & Apex Charts).

```bash
composer require filament/tables:"^4.0" filament/schemas:"^4.0" filament/forms:"^4.0" filament/infolists:"^4.0" filament/actions:"^4.0" filament/notifications:"^4.0" filament/widgets:"^4.0" pxlrbt/filament-excel leandrocfe/filament-apex-charts:"^4.0"

```

Lakukan instalasi panel Filament:

```bash
php artisan filament:install

```

---
## Menjalankan Aplikasi Via Docker

1. Build dan jalankan semua container
docker compose -f docker-compose.yml up -d --build

2. Generate app key (pertama kali)
docker compose -f docker-compose.yml exec app php artisan key:generate

3. Jalankan migrasi
docker compose -f docker-compose.yml exec app php artisan migrate

4. Seed database
docker compose -f docker-compose.yml exec app php artisan db:seed


---

## Menjalankan Aplikasi

1. Jalankan server lokal Laravel:

```bash
php artisan serve

```

atau menggunakan composer

```bash
php artisan serve

```

2. (Opsional) Jika memerlukan build aset frontend:

```bash
npm run dev

```

3. Buka browser dan akses: `http://localhost:8000`

---
