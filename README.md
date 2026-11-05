# e-Arsip KSP

Sistem Informasi Manajemen Arsip Dokumen Koperasi Simpan Pinjam (KSP).

---

## Panduan Setup Proyek

Ikuti langkah-langkah berikut untuk menjalankan proyek di komputer lokal:

### 1. Clone Repository
```bash
git clone https://github.com/jerry-sutisno/earsip-ksp.git
cd earsip-ksp
```

### 2. Install Dependensi PHP
```bash
composer install
```

### 3. Konfigurasi Environment (`.env`)
Salin file `.env.example` menjadi `.env` lalu generate application key:
```bash
cp .env.example .env
php artisan key:generate
```

Buka file `.env` dan sesuaikan pengaturan database MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=earsip_ksp
DB_USERNAME=root
DB_PASSWORD=
```
*(Pastikan database `earsip_ksp` sudah dibuat di MySQL/phpMyAdmin)*

### 4. Migrasi & Seeder Database
Jalankan migrasi tabel beserta data dummy (Master, Transaksi, dan User):
```bash
php artisan migrate:fresh --seed
```

> **Catatan:** Tersedia juga file backup dump database di [`database/earsip_ksp_sprint1.sql`](database/earsip_ksp_sprint1.sql) jika ingin di-import langsung.

### 5. Jalankan Server
```bash
php artisan serve
```
Akses aplikasi melalui browser di: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## Akun Demo

Gunakan akun berikut untuk login dan menguji hak akses role:

| Role | Email | Password | Hak Akses |
| :--- | :--- | :--- | :--- |
| **Petugas Arsip** | `petugas@earsip.test` | `password` | Dashboard (Statistik 8 anggota, 10 arsip, 2 kredit macet), Menu Arsip |
| **Pimpinan** | `pimpinan@earsip.test` | `password` | Dashboard, Menu Arsip, Menu Approval (`/approval`) |

---

## Menjalankan Unit/Feature Test

Untuk memverifikasi fungsionalitas autentikasi, role middleware, dan dashboard:
```bash
php artisan test
```
