# CyberOlympus Olah Data

## Deskripsi Project

Project ini adalah aplikasi olah data berbasis Laravel yang digunakan untuk mengelola, menganalisis, dan menampilkan data hasil import/dump lama (data dummy) dari database Cyber Olympus. Fitur utama meliputi:
- Analisis dan statistik data customer, agent, order, produk, dsb
- Laporan top customer, top agent, top produk, dsb
- Visualisasi data (opsional)
- Pengelolaan data dummy lama (bukan data hasil seeder CRUD)

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
5. **Import database lama**
   - Import file `Database.sql` ke database MySQL/MariaDB Anda.
6. **Jalankan aplikasi**
   ```bash
   php artisan serve
   ```

## Catatan Khusus
- **Data yang dikelola di project ini adalah data dummy hasil import/dump lama** (bukan data hasil seeder CRUD).
- Banyak data dummy yang tidak memiliki relasi sempurna (misal: `referral_id` NULL, dsb), sehingga beberapa fitur edit/delete/show mungkin tidak berjalan sempurna.
- Project ini fokus pada analisis, statistik, dan pelaporan data lama, bukan pada CRUD customer/user baru.
- Jika ingin data yang bisa dioperasikan penuh (CRUD), gunakan project utama backend CRUD.

## Saran
- Untuk keperluan olah data, pastikan database sudah terisi data dummy lama dari file SQL.
- Jika ingin melakukan analisis lebih lanjut, pastikan relasi antar tabel sudah diperbaiki atau lakukan pembersihan data terlebih dahulu.

---

Jika ada error saat import database atau menjalankan aplikasi, cek file `storage/logs/laravel.log` untuk detail error.
