<div align="center">

# 🏋️‍♂️ FITNESSVALS 🥗
### *Next-Gen AI-Powered Gym Management & Personalized Health Ecosystem*

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Go](https://img.shields.io/badge/Go-1.25-00ADD8?style=for-the-badge&logo=go&logoColor=white)](https://golang.org)
[![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-4.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Vite](https://img.shields.io/badge/Vite-7.x-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vitejs.dev)
[![WebSocket](https://img.shields.io/badge/Laravel_Reverb-Realtime-10B981?style=for-the-badge&logo=socket.io&logoColor=white)](https://reverb.laravel.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](https://opensource.org/licenses/MIT)

<p align="center">
  <b>Platform manajemen kebugaran modern yang memadukan keindahan antarmuka web dengan kecerdasan buatan (AI) untuk menghadirkan pengalaman gym yang terpersonalisasi, responsif, dan real-time.</b>
</p>

[Fitur Unggulan](#-fitur-unggulan) • [Arsitektur Sistem](#-arsitektur-sistem) • [Tech Stack](#-teknologi-yang-digunakan) • [Cara Menjalankan](#-cara-menjalankan-proyek)

</div>

---

## 🌟 Mengapa FitnessVals?

Dalam era digital modern, aplikasi kebugaran sering kali terpecah: satu aplikasi untuk mencatat latihan, aplikasi lain untuk menghitung kalori, dan aplikasi terpisah untuk memesan kelas gym. 

**FitnessVals** menyatukan seluruh kebutuhan tersebut ke dalam **satu ekosistem terpadu**. Dengan mengadopsi arsitektur **Hybrid Two-Tier**, FitnessVals memanfaatkan kenyamanan dan kecepatan pengembangan dari **Laravel 12** di sisi UI/UX, didukung oleh performa komputasi tinggi dari microservice **Go (Golang)** yang bertindak sebagai *AI Engine* dan kalkulator kesehatan medis.

---

## ✨ Fitur Unggulan

### 🥗 1. AI Rule-Based Meal Recommendation Engine
Bukan sekadar daftar makanan biasa. Microservice Go kami memproses profil biometrik pengguna (preferensi diet seperti *Vegetarian/Vegan*, serta riwayat medis seperti *Diabetes/Hipertensi*) dan memadukannya dengan **intensitas workout hari ini** (*HIIT, Cardio, Strength, Rest*). Sistem secara otomatis menghitung skor kesesuaian (`match_rate`) dan menyajikan **Top 3 Rekomendasi Makanan** terbaik secara dinamis!

### ⚡ 2. Real-Time Class Capacity Monitoring
Tidak ada lagi kelas gym yang overcapacity atau pemesanan ganda. Menggunakan **Laravel Reverb + Pusher WebSocket**, setiap kali seorang member memesan atau membatalkan kelas, sisa kuota kelas akan **ter-update secara real-time** di layar seluruh pengguna tanpa perlu memuat ulang (*refresh*) halaman.

### 📊 3. Smart Biometric & Calorie Calculator
Menghitung Body Mass Index (BMI) secara presisi dan menentukan BMR (*Basal Metabolic Rate*) serta TDEE (*Total Daily Energy Expenditure*) menggunakan standar ilmiah **Mifflin-St Jeor**. Sistem otomatis merekomendasikan target surplus atau defisit kalori sesuai tujuan (*Bulk / Cut / Maintain*).

### 🏋️ 4. Comprehensive Health & Workout Logging
- **Intake Log:** Catat asupan kalori, protein, karbohidrat, dan lemak harian.
- **Workout Log:** Catat fokus latihan harian (*Chest, Back, Cardio, Legs*) disertai catatan evaluasi.
- **Progress History:** Pantau grafik perkembangan berat badan dan nutrisi dari waktu ke waktu.

### 🛡️ 5. Dedicated Admin Panel & Analytics
Panel kontrol khusus bagi pengelola gym untuk memantau statistik member aktif, mengelola jadwal kelas gym, menambahkan instruktur/trainer, melakukan export laporan ke CSV, dan mengatur konfigurasi sistem secara menyeluruh.

---

## 🏗️ Arsitektur Sistem

FitnessVals menggunakan arsitektur modern yang memisahkan beban presentasi dan logika komputasi berat:

```
┌────────────────────────────────────────────────────────┐
│                    USER BROWSER                        │
└──────────────────────────┬─────────────────────────────┘
                           │ HTTP / WebSocket
                           ▼
┌────────────────────────────────────────────────────────┐
│          FRONTEND & GATEWAY — Laravel 12               │
│   • Blade Templates + Tailwind CSS 4                   │
│   • Auth & Session Management                          │
│   • Laravel Reverb WebSocket Server (Real-time Push)   │
└──────────────────────────┬─────────────────────────────┘
                           │ HTTP REST API + JWT Auth
                           ▼
┌────────────────────────────────────────────────────────┐
│            AI & COMPUTATION ENGINE — Go                │
│   • Gin HTTP Framework (Port 8080)                     │
│   • Rule-Based AI Meal Filtering Algorithm             │
│   • Mifflin-St Jeor Biometric & Calorie Calculation    │
└──────────────────────────┬─────────────────────────────┘
                           │ GORM / Eloquent
                           ▼
┌────────────────────────────────────────────────────────┐
│                   RELATIONAL DATABASE                  │
│            (MySQL / SQLite Storage Engine)             │
└────────────────────────────────────────────────────────</ul>
```

---

## 🛠️ Teknologi yang Digunakan

| Kategori | Teknologi | Kegunaan |
|---|---|---|
| **Frontend Framework** | [Laravel 12](https://laravel.com) | Core web application, routing, MVC, Blade templating |
| **Styling & UI** | [Tailwind CSS v4](https://tailwindcss.com) + Vite | Styling UI modern, glassmorphism, responsive design |
| **Backend / AI Engine** | [Go (Golang)](https://golang.org) + Gin | High-performance REST API, AI meal recommendation |
| **Real-Time WebSocket** | [Laravel Reverb](https://reverb.laravel.com) | Broadcast event `ClassBooked` secara real-time |
| **Database & ORM** | MySQL / SQLite + GORM / Eloquent | Penyimpanan data relasional dan manajemen skema |
| **Security** | JWT (*JSON Web Token*) + Session | Autentikasi aman antar layanan Laravel dan Go |

---

## 🚀 Cara Menjalankan Proyek

Ikuti langkah mudah berikut untuk menjalankan FitnessVals di komputer lokal Anda:

### 📋 Prasyarat
- **PHP** ≥ 8.2 & **Composer**
- **Go (Golang)** ≥ 1.25
- **Node.js** ≥ 18 & **NPM**
- **MySQL / XAMPP / Laragon** (untuk database server)

### 1️⃣ Clone Repositori
```bash
git clone https://github.com/Arthursakaa/Web_FitnessVals.git
cd Web_FitnessVals
```

### 2️⃣ Jalankan Backend AI Engine (Go / Gin)
Buka **Terminal 1**, lalu jalankan server backend Go:
```bash
cd backend
go run main.go
```
> *Server Go akan berjalan di `http://localhost:8080` dan terhubung ke database.*

### 3️⃣ Jalankan Web Application (Laravel + Vite)
Buka **Terminal 2**, lalu jalankan frontend Laravel beserta *asset bundler*:
```bash
cd frontend
composer install
npm install
composer run dev
```
> *Perintah `composer run dev` secara otomatis menjalankan server Laravel (`http://localhost:8000`) dan Vite server secara bersamaan!*

### 4️⃣ *(Opsional)* Aktifkan Fitur Real-Time WebSocket
Jika ingin menguji fitur update kapasitas kelas tanpa refresh, buka **Terminal 3**:
```bash
cd frontend
php artisan reverb:start
```
---

<div align="center">
  <p><i>Project GYM — Web Programming 2026</i></p>
</div>
