# CyberOlympus Laravel Test

## Deskripsi Project

Project ini adalah aplikasi backend berbasis Laravel (minimal versi 7) untuk keperluan tes Backend Programmer di Cyber Olympus. Fitur utama meliputi:
- Autentikasi admin (login)
- CRUD customer (nama, alamat, nomor telepon, tanggal registrasi)
- Pencarian dan filter data customer
- Pagination (10 data per halaman)
- Halaman detail customer
- Data uji coba dikelola melalui database seeder

## Requirement
- PHP >= 7.2.5
- Composer
- MySQL/MariaDB
- Node.js & npm (untuk frontend build jika diperlukan)
- Laravel 7 atau lebih baru

## Cara Setup
1. **Clone repository**
2. **Install dependency**
   ```bash
   composer install
   npm install && npm run dev # jika ada asset frontend
   ```
3. **Copy file .env**
   ```bash
   cp .env.example .env
   # Edit konfigurasi database di .env
   ```
4. **Generate key**
   ```bash
   php artisan key:generate
   ```
5. **Migrasi dan seed database**
   ```bash
   php artisan migrate:fresh --seed
   ```
6. **Jalankan aplikasi**
   ```bash
   php artisan serve
   ```

## Akun Admin Default
- Email: `admin@cyberolympus.com`
- Password: `cyberadmin`

## Catatan Masalah & Solusi

### 1. Data Dummy Lama Tidak Bisa Dikelola
- Data dummy hasil import/dump lama pada tabel `users` dan `customer` **tidak bisa dioperasikan** (edit, delete, show) melalui aplikasi.
- Penyebab: data dummy tidak memiliki relasi yang benar antar tabel (`referral_id` NULL, dsb).
- **Solusi:** Abaikan data dummy lama, gunakan data hasil seeder sendiri.

### 2. Data yang Bisa Dikelola Hanya Data yang Diinput Sendiri
- Hanya data yang diinput melalui aplikasi atau hasil seeder sendiri yang bisa dioperasikan penuh (CRUD, search, dsb).
- Data hasil seeder sudah dijamin konsisten dan relasi benar.

### 3. Seeder Belum Berhasil Terinput
- Seeder sudah di-refactor untuk membuat 1 admin dan 20 customer beserta relasinya.
- Namun, saat dijalankan, data belum masuk ke database (perlu pengecekan error di log Laravel atau constraint database).
- **Solusi sementara:** Cek dan pastikan constraint database benar, hapus data dummy lama, lalu jalankan ulang seeder.

## Saran
- Untuk penilaian backend, **cukup gunakan data hasil seeder sendiri**.
- Jika ingin data clean, hapus data dummy lama sebelum menjalankan seeder.

---

Jika ada error saat seeding atau migrasi, cek file `storage/logs/laravel.log` untuk detail error. 