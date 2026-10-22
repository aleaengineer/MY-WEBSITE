# Portofolio Farhan — Laravel 12

Website portofolio & internal tools untuk **Farhan Maulana Syidiq** (NOC Engineer, Network Administrator, & Web Developer), dibangun dengan stack tradisional tanpa panel eksternal.

## Stack

- **Backend:** Laravel 12 (PHP 8.2+), MySQL/MariaDB
- **Frontend:** Blade + Tailwind CSS v4 (Vite) + Alpine.js
- **Admin:** Panel CRUD custom Blade + auth session bawaan Laravel (tanpa Filament/Breeze)
- **Produksi:** Nginx + PHP8.2-FPM di Ubuntu 22.04 / Debian 12

## Fitur

- Halaman publik: hero, keahlian, studi kasus proyek (tantangan, solusi, hasil), filter kategori, testimoni, blog, CV, dan formulir kontak dengan validasi, honeypot, serta rate-limit 5/menit.
- SEO berbagi: judul/deskripsi per proyek, canonical URL, Open Graph dan Twitter Card.
- Panel admin (`/admin`): dashboard statistik dan analitik kunjungan agregat, CRUD proyek/artikel/testimoni, pengelolaan pengalaman/sertifikasi/pendidikan, inbox pesan, pengaturan kontak/profil, serta daftar tautan aplikasi navbar.
- Analitik hanya menyimpan hitungan per hari dan path halaman; alamat IP dan identitas pengunjung tidak disimpan. Nilai adalah jumlah permintaan halaman, bukan pengunjung unik.
- Slug otomatis + unik dari judul proyek.

## Dokumen

| Dokumen | Isi |
|---|---|
| [LOCAL_SETUP.md](LOCAL_SETUP.md) | Setup lokal XAMPP/Laragon step-by-step |
| [DEPLOYMENT.md](DEPLOYMENT.md) | Deploy VPS Ubuntu/Debian lengkap + SSL Let's Encrypt |
| [deploy/nginx/portfolio.conf](deploy/nginx/portfolio.conf) | Virtual host Nginx produksi |

## Perintah Cepat

```bash
php artisan migrate --seed   # siapkan database + data contoh
php artisan storage:link     # symlink thumbnail publik
npm run build                # build aset front-end
php artisan serve            # jalankan di http://127.0.0.1:8000
php artisan test             # jalankan test suite
```

## Struktur Kode Utama

```
app/
├── Http/Controllers/
│   ├── PortfolioController.php      # halaman publik + form kontak
│   ├── AuthController.php           # login/logout admin
│   └── Admin/
│       ├── DashboardController.php
│       ├── ProjectController.php    # CRUD proyek + upload thumbnail
│       ├── TestimonialController.php # CRUD testimoni
│   ├── MessageController.php    # inbox pesan kontak
│   └── ApplicationLinkController.php # CRUD tautan dropdown aplikasi
├── Models/
│   ├── ApplicationLink.php
│   ├── Project.php
│   └── Message.php
database/
├── migrations/                      # projects & messages
└── seeders/                         # admin user + proyek contoh
resources/views/
├── portfolio/index.blade.php        # halaman publik
├── auth/login.blade.php
└── admin/                           # dashboard, projects, messages
routes/web.php
```
