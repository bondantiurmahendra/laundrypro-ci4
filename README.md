# Pro Laundry - Sistem Manajemen Laundry Modern

Pro Laundry adalah aplikasi web modern untuk memudahkan proses pemesanan, pengelolaan, dan pelacakan laundry baik untuk pelanggan maupun admin. Sistem ini mendukung notifikasi otomatis, pembayaran online, dan integrasi email.

## Fitur Utama

### Untuk Pelanggan

- **Registrasi & Login**
- **Melakukan Pemesanan**: Pilih jenis layanan, input detail laundry, pilih antar/jemput, dan metode pembayaran (Cash/Online)
- **Melihat Estimasi Biaya & Waktu**
- **Menerima Bukti Transaksi** (via email)
- **Cek Status Laundry**
- **Menerima Notifikasi** status pesanan
- **Melakukan Penerimaan** laundry

### Untuk Admin

- **Login ke Sistem Admin**
- **Pencatatan & Kelola Transaksi Baru**
- **Kelola Daftar Layanan** (tambah/edit/hapus layanan)
- **Cetak/Kirim Bukti Transaksi** ke pelanggan
- **Update Status Laundry** (Dijemput, Diterima, Dicuci, Selesai, dll)
- **Kelola Notifikasi Pelanggan**
- **Verifikasi Pengambilan**
- **Laporan Transaksi** (export CSV/Excel)
- **Kelola Data Pelanggan**

## Teknologi yang Digunakan

- **Frontend**: Alpine.js, Tailwind CSS, HTML5
- **Backend**: CodeIgniter 4 (PHP)
- **Database**: Firebase Firestore 
- **Email**: SMTP (Brevo/Sendinblue)
- **Pembayaran Online**: Midtrans Snap

## Instalasi & Setup

1. **Clone repository**
   ```bash
   git clone https://github.com/username/pro-laundry.git
   cd pro-laundry
   ```
2. **Install dependencies**
   ```bash
   composer install
   npm install # jika ada frontend build
   ```
3. **Copy & edit konfigurasi**
   - Salin file `.env.example` ke `.env` dan sesuaikan konfigurasi database, email, dan API key Midtrans/Firebase.
4. **Jalankan server**
   ```bash
   php spark serve
   # atau gunakan web server Apache/Nginx
   ```
5. **Akses aplikasi**
   - Buka `http://localhost:8080` di browser.

## Instruksi Penggunaan

- **Pelanggan** dapat mendaftar/login, membuat pesanan, memilih layanan, dan memantau status laundry.
- **Admin** dapat login ke dashboard admin untuk mengelola transaksi, layanan, dan pelanggan.
- Notifikasi email otomatis dikirim ke admin setiap ada pesanan baru.
- Pembayaran online menggunakan Midtrans Snap, status pembayaran otomatis terupdate.

## Kontribusi

Kontribusi sangat terbuka! Silakan fork repo ini, buat branch baru, dan ajukan pull request.

## Kontak

- Email: fc202d5y2290@student.devacademy.id
- Developer: Jack Kolor

---

Pro Laundry © 2025 - Sistem Laundry Modern
