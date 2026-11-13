# 📚 The Book Atelier - Laravel Edition

Versi modern dari aplikasi toko buku online **The Book Atelier** yang dibangun menggunakan framework **Laravel 11**, **MySQL**, **PHP 8.3**, dan **Bootstrap 5.3**.

---

## 🚀 Fitur & Arsitektur Sistem

1. **Arsitektur MVC (Model-View-Controller)**:
   - **Models & Relasi Eloquent:** `User`, `Category`, `Book`, `Order`, `OrderItem`, `Message`.
   - **Controllers Terstruktur:** Pemisahan controller publik (`HomeController`, `BookController`, `CartController`, `OrderController`, dll) dan panel admin (`Admin\BookController`, `Admin\CategoryController`, dll).
   - **Blade Templates:** Komponen modular dengan layout terpadu (`layouts/app.blade.php` dan `layouts/admin.blade.php`).

2. **Integritas & Proteksi Data Transaksi**:
   - Buku dan Kategori yang terhubung dengan riwayat pesanan user **tidak dapat dihapus** (`ON DELETE RESTRICT` pada basis data dan proteksi keamanan di controller).
   - Indikator gembok 🔒 dan lencana pesanan user aktif di tabel admin.

3. **Sistem Keranjang & Checkout Transaksional**:
   - Validasi stok sebelum checkout menggunakan *pessimistic locking* (`lockForUpdate`).
   - Kontrol transaksi database aman (`DB::transaction`) untuk memastikan pemotongan stok dan pembuatan nomor pesanan `ORD-YYYYMMDD-XXXXX` berjalan atomik (*all-or-nothing*).

4. **Autentikasi & Otorisasi Berbasis Peran**:
   - Sistem login aman dengan hashing kata sandi bawaan Laravel BCRYPT.
   - Route Middleware: `auth` untuk transaksi pelanggan dan `admin` untuk panel manajemen.

5. **Layanan Concierge Dua Arah**:
   - Pengguna dapat mengirimkan pesan ke pengelola toko.
   - Admin dapat membalas pesan dan riwayat balasan langsung muncul di akun pengguna.

---

## 🔑 Akun Demo Pengujian

| Peran (Role) | Alamat Email | Kata Sandi | Halaman Akses |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@bookstore.com` | `admin123` | `/admin` |
| **User (Pelanggan)** | `budi@gmail.com` | `user1234` | `/` |

---

## 🌐 Cara Menjalankan di Laragon

1. Pastikan Laragon berjalan (**Start All** Apache & MySQL).
2. Database MySQL: `bookstore_laravel_db` (sudah ter-migrasi dan ter-seeding secara otomatis).
3. Akses via Browser:
   - 👉 **`http://bookstore-laravel.test`**  
   - Atau via localhost:  
     👉 **`http://localhost/bookstore-laravel/public`**
   - Panel Admin:  
     👉 **`http://localhost/bookstore-laravel/public/admin`**

---

## 📸 Tangkapan Layar & Preview Tampilan
Dokumentasi visual lengkap aplikasi (14 tangkapan layar antarmuka pengguna & administrator) dapat dilihat langsung pada folder [**`screenshots/`**](screenshots/README.md).
