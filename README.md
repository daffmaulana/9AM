# 9AM

<p align="center">
  <strong>Platform pencarian kerja Indonesia yang lengkap dan mudah digunakan.</strong>
</p>

<p align="center">
  Temukan lowongan impianmu, baca tips karir, dan buat CV profesional — semua dalam satu tempat.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white" alt="PHP" />
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=mysql&logoColor=white" alt="MySQL" />
  <img src="https://img.shields.io/badge/HTML5-E34F26?style=flat&logo=html5&logoColor=white" alt="HTML5" />
  <img src="https://img.shields.io/badge/CSS3-1572B6?style=flat&logo=css3&logoColor=white" alt="CSS3" />
  <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=flat&logo=javascript&logoColor=black" alt="JavaScript" />
</p>

<p align="center">
  <a href="#fitur">Fitur</a> •
  <a href="#struktur-proyek">Struktur Proyek</a> •
  <a href="#database">Database</a> •
  <a href="#cara-menjalankan">Cara Menjalankan</a> •
  <a href="#tim-developer">Tim Developer</a>
</p>

---

## Apa itu 9AM?

9AM adalah platform pencarian kerja berbasis web yang ditujukan untuk pencari kerja Indonesia. Pengguna dapat menelusuri lowongan kerja dari berbagai perusahaan, membaca artikel tips karir, serta membuat dan mengunduh CV profesional dalam format PDF, dan semuanya secara gratis!

```text
Daftar Akun → Jelajahi Lowongan → Baca Tips Karir → Buat CV → Lamar Pekerjaan
```

---

## Fitur

| Fitur | Deskripsi |
| --- | --- |
| Lowongan Kerja | Telusuri ratusan lowongan kerja dari berbagai perusahaan dengan filter pencarian |
| Detail Lowongan | Lihat deskripsi lengkap pekerjaan, kualifikasi, gaji, dan tautan lamaran langsung |
| Blog / Tips Karir | Baca artikel seputar tips interview, cara membuat CV, dan prospek karir |
| CV Builder | Buat CV profesional secara online dan unduh dalam format PDF |
| Autentikasi Pengguna | Daftar, masuk, dan lupa password dengan keamanan password hashing |
| Manajemen Profil | Perbarui username, foto profil, dan password akun |
| Testimoni | Tampilan testimoni pengguna yang berhasil mendapat pekerjaan melalui 9AM |
| Admin Dashboard | Kelola semua data (user, lowongan, berita, carousel, testimoni, CV) melalui panel admin |
| Carousel Beranda | Tampilan gambar slider dinamis yang dikelola admin |
| Pagination & Search | Pencarian dan navigasi halaman pada lowongan kerja dan blog |

---

## Struktur Proyek

```
9AM/
├── index.php               # Halaman beranda (carousel, lowongan terbaru, blog, testimoni)
├── login.php               # Halaman login
├── daftar.php              # Halaman registrasi akun baru
├── logout.php              # Proses logout
├── forgetpass.php          # Halaman lupa password
├── profile.php             # Halaman profil dan edit akun pengguna
├── lowongankerja.php       # Daftar semua lowongan kerja + search & pagination
├── detail_lowongan.php     # Detail halaman lowongan kerja
├── pilihberita.php         # Daftar artikel blog + search & pagination
├── detail_berita.php       # Detail halaman artikel blog
├── cv_builder.php          # Form pembuatan CV online
├── generate_pdf.php        # Generate dan unduh CV dalam format PDF
├── view_cv.php             # Preview CV sebelum diunduh
├── process_form.php        # Proses penyimpanan data CV
├── admin-dashboard.php     # Dashboard admin (CRUD semua konten)
├── aboutus.php             # Halaman tentang tim developer
├── privacypolicy.php       # Halaman kebijakan privasi
├── termsconditions.php     # Halaman syarat & ketentuan
├── config.php              # Konfigurasi koneksi database
├── navbar.php              # Navbar untuk tamu (belum login)
├── navbar-in.php           # Navbar untuk pengguna yang sudah login
├── footer.php              # Footer global
├── 9am.sql                 # File SQL untuk setup database
├── dompdf/                 # Library dompdf untuk generate PDF
└── img/                    # Aset gambar
    ├── blog/               # Thumbnail artikel berita
    ├── carousel/           # Gambar slider beranda
    ├── loker/              # Logo perusahaan lowongan kerja
    ├── testi/              # Foto testimoni
    ├── dev/                # Foto tim developer
    └── user/               # Foto profil pengguna
```

---

## Database

Proyek ini menggunakan MySQL dengan database bernama `9am`. Terdiri dari 6 tabel utama:

| Tabel | Fungsi |
| --- | --- |
| `users` | Data akun pengguna (username, email, password hash, role admin, foto) |
| `jobs` | Data lowongan kerja (judul, perusahaan, lokasi, gaji, deskripsi, dll.) |
| `data_berita` | Data artikel blog (judul, isi, tanggal, thumbnail) |
| `testimonials` | Data testimoni pengguna (nama, peran, isi, foto) |
| `carousel_images` | Data gambar slider beranda |
| `cv_data` | Data CV yang dibuat pengguna (nama, pengalaman, pendidikan, skill, dll.) |

---

## Tech Stack

### Frontend
- HTML5
- CSS3 (custom styling inline per halaman)
- JavaScript
- Font Awesome 6
- Bootstrap Icons
- Google Fonts (Open Sans)

### Backend
- PHP (Native, tanpa framework)
- Session-based authentication
- Password hashing dengan `password_hash()` / `password_verify()`
- Prepared statements (MySQLi) untuk keamanan query

### Database
- MySQL / MariaDBZ

---

## Cara Menjalankan

### Prasyarat
- PHP >= 7.4
- MySQL / MariaDB
- Web server (Apache/Nginx) atau XAMPP/Laragon

### Lokal (XAMPP)

1. Clone atau ekstrak proyek ke folder `htdocs`:
   ```
   C:/xampp/htdocs/9AM/
   ```

2. Import database:
   - Buka phpMyAdmin (`http://localhost/phpmyadmin`)
   - Buat database baru bernama `9am`
   - Import file `9am.sql`

3. Sesuaikan konfigurasi di `config.php`:
   ```php
   <?php
   $conn = mysqli_connect("localhost", "root", "", "9am") or die("Database Terputus");
   ?>
   ```

4. Akses aplikasi di browser:
   ```
   http://localhost/9AM/
   ```

### Hosting (InfinityFree / cPanel)

1. Buat database baru di cPanel → MySQL Databases
2. Import `9am.sql` via phpMyAdmin
3. Edit `config.php` sesuai kredensial database hosting:
   ```php
   <?php
   $conn = mysqli_connect(
       "sql***.infinityfree.net",  // Host database dari cPanel
       "epiz_XXXXXXX",             // Username database
       "password_db",              // Password database
       "epiz_XXXXXXX_9am"          // Nama database
   ) or die("Database Terputus");
   ?>
   ```
4. Upload semua file ke folder `htdocs` via File Manager atau FTP

---


## Tim Developer

Proyek ini dikembangkan sebagai tugas kuliah oleh mahasiswa Universitas Pembangunan Nasional "Veteran" Jakarta:

| Nama | NIM |
| --- | --- |
| Daffa Bagus Maulana | 2210511063 |
| Ananda Divana | 2210511053 |
| Dafa Andika Firmansyah | 2210511049 |
| Adrian Fakhriza Hakim | 2210511050 |

---

<p align="center">
  <strong>9AM</strong><br>
  Mulai karirmu dari sini.
</p>
