# 📁 E-Arsip (Sistem Informasi Penyimpanan Digital)

Aplikasi web berbasis Laravel dan Vue.js untuk manajemen arsip digital yang aman, modern, dan mudah digunakan. Dilengkapi dengan sistem Role-Based Access Control (RBAC) untuk memisahkan hak akses antara Super Admin, Admin, dan User biasa.

## ✨ Fitur Utama

### 👥 Manajemen Pengguna (User Management)
- **Super Admin:** Memiliki akses penuh, dapat membuat/menghapus Admin & User, serta melihat dashboard monitoring akun.
- **Admin:** Dapat mengelola User biasa dan melihat Log Aktivitas.
- **User:** Hanya dapat mengupload, melihat, dan mengunduh arsip miliknya sendiri.

### 📂 Manajemen Arsip
- Upload berbagai jenis file (Gambar, Video, Dokumen).
- Preview file langsung di browser (Modal Preview).
- Pencarian dan filter arsip berdasarkan kategori.
- Download arsip dengan mudah.

### 📊 Dashboard & Monitoring
- Statistik arsip real-time (Total, Gambar, Video, Dokumen).
- Grafik upload harian.
- Dashboard Monitoring khusus Super Admin untuk melihat total akun.
- Log Aktivitas sistem.

## 🛠️ Teknologi yang Digunakan

- **Backend:** Laravel 11+ (PHP 8.3)
- **Frontend:** Vue 3, Inertia.js, Tailwind CSS
- **Build Tool:** Vite
- **Database:** SQLite (Default)
- **UI Components:** SweetAlert2, FontAwesome

## 🚀 Cara Instalasi

Ikuti langkah berikut untuk menjalankan project ini di komputer lokal Anda:

### 1. Persyaratan (Requirements)
Pastikan komputer Anda sudah terinstall:
- PHP >= 8.1
- Composer
- Node.js & NPM
- Git

### 2. Clone Repository
```bash
git clone https://github.com/samsolakmal/penyimanan--igital.git
cd penyimanan--igital