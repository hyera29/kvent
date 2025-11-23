# 📖 Panduan Instalasi Lengkap

## Untuk Visual Studio Code + XAMPP

### Langkah 1: Install XAMPP
1. Download XAMPP dari https://www.apachefriends.org/
2. Install sesuai sistem operasi Anda (Windows/Mac/Linux)
3. Jalankan XAMPP Control Panel

### Langkah 2: Start Services
1. Pada XAMPP Control Panel, klik **Start** pada:
   - ✅ Apache
   - ✅ MySQL
2. Pastikan status berubah menjadi hijau/running

### Langkah 3: Setup Database
1. Buka browser, ketik: `http://localhost/phpmyadmin`
2. Klik tombol **New** (atau **Baru**) di sidebar kiri
3. Pada "Create database":
   - Database name: `sim_event_kpopers`
   - Collation: `utf8mb4_general_ci`
   - Klik **Create**
4. Pilih database `sim_event_kpopers` dari sidebar
5. Klik tab **Import** di menu atas
6. Klik **Choose File**, pilih: `attached_assets/sim_event_kpopers_1763897376062.sql`
7. Scroll ke bawah, klik **Go** (atau **Kirim**)
8. Tunggu sampai muncul "Import has been successfully finished"

### Langkah 4: Copy Project ke XAMPP
1. **Windows:**
   - Copy semua file/folder project ini
   - Paste ke: `C:\xampp\htdocs\kpopers-event\`

2. **Mac:**
   - Copy semua file/folder project ini
   - Paste ke: `/Applications/XAMPP/htdocs/kpopers-event/`

3. **Linux:**
   - Copy semua file/folder project ini
   - Paste ke: `/opt/lampp/htdocs/kpopers-event/`

### Langkah 5: Jalankan Aplikasi
1. Buka browser (Chrome, Firefox, Edge, dll)
2. Ketik URL: `http://localhost/kpopers-event/`
3. Anda akan melihat halaman login

### Langkah 6: Login
Gunakan salah satu akun berikut:

**Admin:**
- Username: `admin1`
- Password: `password`

**Panitia:**
- Username: `panitia`
- Password: `password`

**Member:**
- Username: `member`
- Password: `password`

---

## ✅ Verifikasi Instalasi Berhasil

Jika instalasi berhasil, Anda akan melihat:
- ✅ Halaman login dengan tema gradient ungu-pink
- ✅ Bisa login dengan salah satu akun di atas
- ✅ Dashboard sesuai role yang dipilih

---

## ⚠️ Troubleshooting

### Error: Database connection failed
**Solusi:**
1. Pastikan MySQL di XAMPP running (hijau)
2. Buka `config/db.php`, pastikan:
   ```php
   $host = 'localhost';
   $dbname = 'sim_event_kpopers';
   $username = 'root';
   $password = ''; // Kosong untuk XAMPP default
   ```
3. Pastikan database `sim_event_kpopers` sudah dibuat

### Error: Page not found (404)
**Solusi:**
1. Pastikan folder project ada di `htdocs/kpopers-event/`
2. Cek URL harus: `http://localhost/kpopers-event/` (dengan slash di akhir)
3. Restart Apache di XAMPP

### Apache tidak mau start
**Solusi:**
1. Port 80 mungkin dipakai aplikasi lain (Skype, IIS, dll)
2. Stop aplikasi yang menggunakan port 80
3. Atau ubah port Apache di XAMPP config

### Upload bukti pembayaran gagal
**Solusi:**
1. Pastikan folder `uploads/` ada
2. Cek permission folder (harus writable)
3. Max file size 5MB

---

## 📱 Mengakses dari HP/Device Lain

1. Cek IP komputer Anda:
   - Windows: `ipconfig` (cari IPv4 Address)
   - Mac/Linux: `ifconfig` atau `ip addr`
2. Dari HP, buka: `http://[IP-KOMPUTER]/kpopers-event/`
3. Contoh: `http://192.168.1.100/kpopers-event/`
4. Pastikan HP dan komputer dalam jaringan WiFi yang sama

---

## 🎯 Fitur yang Bisa Ditest

### Sebagai Admin:
- ✅ Verifikasi pembayaran member
- ✅ Lihat semua event
- ✅ Kelola user
- ✅ Monitoring dashboard

### Sebagai Panitia:
- ✅ Buat event baru
- ✅ Tambah tiket untuk event
- ✅ Tambah merchandise
- ✅ Kelola event sendiri

### Sebagai Member:
- ✅ Daftar ke event
- ✅ Pilih jenis tiket
- ✅ Upload bukti pembayaran
- ✅ Order merchandise
- ✅ Lihat riwayat registrasi

---

## 📞 Bantuan Lebih Lanjut

Jika masih mengalami masalah, pastikan:
1. ✅ XAMPP Apache dan MySQL running
2. ✅ Database sudah diimport
3. ✅ File project di folder `htdocs/kpopers-event/`
4. ✅ URL benar: `http://localhost/kpopers-event/`

---

**Selamat mencoba! 💜**
