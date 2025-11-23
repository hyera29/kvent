# Sistem Informasi Manajemen Event Komunitas K-Popers

## Deskripsi Proyek
Aplikasi web berbasis PHP untuk mengelola event komunitas K-popers dengan fitur lengkap untuk admin, panitia, dan member.

## Teknologi yang Digunakan
- **Backend:** PHP 8.2
- **Database:** MySQL/MariaDB
- **Frontend:** HTML5, CSS3, JavaScript
- **Styling:** Custom CSS dengan tema K-pop (gradient pink-purple)

## Struktur Database
Database: `sim_event_kpopers`

Tabel utama:
- `user` - Data pengguna (admin, panitia, member)
- `events` - Data event
- `tickets` - Tiket event
- `registrations` - Registrasi peserta
- `payments` - Pembayaran
- `merch` - Merchandise
- `merch_orders` - Order merchandise
- `event_organizer` - Relasi event dan panitia

## Fitur Aplikasi

### 1. Admin Dashboard
- Verifikasi pembayaran
- Kelola semua event
- Kelola pengguna
- Monitoring registrasi
- Statistik lengkap

### 2. Panitia Dashboard
- Buat dan kelola event
- Kelola tiket event
- Kelola merchandise
- Monitoring event yang dibuat

### 3. Member Dashboard
- Lihat dan daftar event
- Registrasi dengan pilihan tiket
- Upload bukti pembayaran
- Order merchandise
- Riwayat registrasi dan pesanan

## Akun Testing
- **Admin:** username: `admin1`, password: `password`
- **Panitia:** username: `panitia`, password: `password`
- **Member:** username: `member`, password: `password`

## Cara Menjalankan di Visual Studio Code

### Opsi 1: Menggunakan XAMPP (Direkomendasikan)
1. Install XAMPP dari https://www.apachefriends.org/
2. Copy semua file project ke folder `C:\xampp\htdocs\kpopers-event\`
3. Jalankan XAMPP Control Panel
4. Start Apache dan MySQL
5. Buka phpMyAdmin (http://localhost/phpmyadmin)
6. Buat database baru dengan nama `sim_event_kpopers`
7. Import file `attached_assets/sim_event_kpopers_1763897376062.sql`
8. Buka browser dan akses `http://localhost/kpopers-event/`

### Opsi 2: Menggunakan PHP Built-in Server
1. Install PHP 8.2+ di komputer Anda
2. Install MySQL/MariaDB
3. Setup database seperti langkah di atas
4. Buka terminal di folder project
5. Jalankan: `php -S localhost:8000`
6. Buka browser dan akses `http://localhost:8000/`

## Konfigurasi Database
Edit file `config/db.php` jika perlu mengubah kredensial database:
```php
$host = 'localhost';
$dbname = 'sim_event_kpopers';
$username = 'root';
$password = '';
```

## Struktur Folder
```
├── config/
│   ├── db.php           # Konfigurasi database
│   └── session.php      # Session management
├── pages/
│   ├── admin/           # Halaman admin
│   ├── panitia/         # Halaman panitia
│   └── member/          # Halaman member
├── assets/
│   └── css/
│       └── style.css    # Styling
├── uploads/             # Bukti pembayaran
├── database/            # File SQL
└── index.php            # Halaman login

## Catatan Penting
- Pastikan folder `uploads/` memiliki permission write (0755)
- Password menggunakan bcrypt hash untuk keamanan
- Jalankan `database/update_passwords.sql` setelah import database
- Session timeout default adalah ketika browser ditutup
- Upload bukti pembayaran max 5MB dengan validasi mime type dan extension

## Development Guidelines & Keamanan
- Password menggunakan bcrypt hash (password_hash & password_verify)
- Semua query menggunakan prepared statements untuk mencegah SQL injection
- Session-based authentication
- Upload file divalidasi: mime type, extension whitelist, size limit
- Random filename dengan uniqid() untuk mencegah file override
- Responsive design untuk mobile dan desktop
- Tema K-pop dengan warna gradient ungu-pink

## Status Proyek
✅ Login multi-role
✅ Dashboard admin, panitia, member
✅ Manajemen event (CRUD)
✅ Sistem registrasi & ticketing
✅ Upload & verifikasi pembayaran
✅ Manajemen merchandise
✅ Responsive design

## Update Terakhir
23 November 2025 - Initial version dengan semua fitur MVP
