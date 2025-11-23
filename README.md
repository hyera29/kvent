# 🎵 Sistem Informasi Manajemen Event K-Popers

Aplikasi web untuk mengelola event komunitas K-popers dengan fitur lengkap untuk Admin, Panitia, dan Member.

## ✨ Fitur Utama

### 👨‍💼 Admin
- ✅ Verifikasi pembayaran registrasi
- ✅ Kelola semua event dan pengguna
- ✅ Dashboard monitoring lengkap
- ✅ Lihat statistik registrasi

### 🎤 Panitia
- ✅ Buat dan kelola event
- ✅ Kelola tiket untuk setiap event
- ✅ Kelola merchandise event
- ✅ Monitoring event yang dibuat

### 💜 Member
- ✅ Lihat dan daftar event
- ✅ Pilih jenis tiket
- ✅ Upload bukti pembayaran
- ✅ Order merchandise
- ✅ Riwayat registrasi dan pesanan

## 🚀 Cara Menjalankan di Visual Studio Code

### Prasyarat
- XAMPP (Download dari https://www.apachefriends.org/)
- Visual Studio Code
- Browser (Chrome, Firefox, Edge, dll)

### Langkah-langkah:

#### 1. Install XAMPP
- Download dan install XAMPP
- Jalankan XAMPP Control Panel
- Klik **Start** pada Apache dan MySQL

#### 2. Setup Database
1. Buka browser, akses: `http://localhost/phpmyadmin`
2. Klik tab **New** atau **Baru**
3. Buat database dengan nama: `sim_event_kpopers`
4. Pilih database yang baru dibuat
5. Klik tab **Import**
6. Pilih file: `attached_assets/sim_event_kpopers_1763897376062.sql`
7. Klik **Go** atau **Kirim**

**Catatan:** Password akan otomatis di-hash saat pertama kali aplikasi dijalankan.

#### 3. Copy File Project
1. Copy semua file project ini
2. Paste ke folder: `C:\xampp\htdocs\kpopers-event\`
3. (Di Mac/Linux: `/Applications/XAMPP/htdocs/kpopers-event/`)

#### 4. Jalankan Aplikasi
1. Buka browser
2. Akses: `http://localhost/kpopers-event/`
3. Login menggunakan akun berikut:

## 🔑 Akun Testing

| Role | Username | Password |
|------|----------|----------|
| Admin | admin1 | password |
| Panitia | panitia | password |
| Member | member | password |

## 📁 Struktur Database

**Database:** `sim_event_kpopers`

**Tabel:**
- `user` - Data pengguna (admin, panitia, member)
- `events` - Data event
- `tickets` - Tiket untuk setiap event
- `registrations` - Registrasi peserta event
- `payments` - Pembayaran registrasi
- `merch` - Merchandise event
- `merch_orders` - Pesanan merchandise
- `event_organizer` - Relasi event dengan panitia

## ⚙️ Konfigurasi

Jika database tidak terkoneksi, edit file `config/db.php`:

```php
$host = 'localhost';
$dbname = 'sim_event_kpopers';
$username = 'root';
$password = ''; // Kosongkan jika default XAMPP
```

## 🎨 Teknologi

- **Backend:** PHP 8.2
- **Database:** MySQL/MariaDB
- **Frontend:** HTML5, CSS3, JavaScript
- **Design:** Custom CSS dengan tema K-pop (gradient pink-purple)

## 📝 Catatan Penting

1. **Folder Uploads:** Pastikan folder `uploads/` memiliki permission write
2. **Database:** Harus sudah diimport sebelum menjalankan aplikasi
3. **XAMPP:** Apache dan MySQL harus running
4. **Port:** Pastikan port 80 (Apache) dan 3306 (MySQL) tidak digunakan aplikasi lain

## 🔒 Keamanan

- Password menggunakan bcrypt hash (password_hash & password_verify)
- PDO prepared statements untuk mencegah SQL injection
- Session-based authentication
- Upload file divalidasi (mime type, extension, size limit 5MB)
- Filename upload menggunakan random string untuk mencegah override
- Folder permission 0755 untuk uploads

## 📞 Troubleshooting

### Database connection failed
- Pastikan MySQL di XAMPP sudah running
- Cek kredensial di `config/db.php`
- Pastikan database `sim_event_kpopers` sudah dibuat dan diimport

### Login failed
- Password akan otomatis di-hash pada akses pertama
- Jika masih gagal, hapus file `config/.migration_done` dan refresh halaman
- Default password untuk semua user: `password`

### Page not found
- Pastikan file ada di folder `htdocs/kpopers-event/`
- Cek URL: `http://localhost/kpopers-event/`

### Upload file failed
- Pastikan folder `uploads/` ada dan writable
- Cek `php.ini` untuk `upload_max_filesize`

## 📖 Panduan Penggunaan

### Untuk Admin:
1. Login sebagai admin
2. Lihat dashboard untuk statistik
3. Verifikasi pembayaran di menu "Verifikasi Pembayaran"
4. Kelola user dan event melalui menu yang tersedia

### Untuk Panitia:
1. Login sebagai panitia
2. Buat event baru
3. Tambahkan tiket untuk event
4. Tambahkan merchandise (opsional)

### Untuk Member:
1. Login sebagai member
2. Pilih event yang tersedia
3. Pilih jenis tiket dan daftar
4. Upload bukti pembayaran
5. Order merchandise (opsional)

## 🎯 Status Proyek

✅ Sistem login multi-role  
✅ Dashboard untuk 3 role (Admin, Panitia, Member)  
✅ Manajemen event (CRUD)  
✅ Sistem registrasi & ticketing  
✅ Upload & verifikasi pembayaran  
✅ Manajemen merchandise  
✅ Responsive design  

---

**Dibuat dengan 💜 untuk komunitas K-Popers**
