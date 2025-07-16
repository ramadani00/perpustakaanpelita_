# 🏛️ Perpustakaan Pelita - Sistem Informasi Perpustakaan Digital

![App Banner](assets/img/Poster.png)

**Aplikasi Perpustakaan Berbasis Web**  
*Proyek Ujian Akhir Semester Pemrograman Web 2 - Universitas Pelita Bangsa*

## 📋 Tentang Proyek

### Latar Belakang
Perpustakaan Pelita menghadapi tantangan dalam:
- Proses peminjaman manual (10-15 menit/transaksi)
- 20% kesalahan pencatatan data
- Akses informasi terbatas
- Kesulitan pelacakan riwayat peminjaman

### Solusi Digital
Aplikasi ini menyediakan platform terintegrasi untuk:
✅ Manajemen katalog buku digital  
✅ Sistem peminjaman online mandiri  
✅ Dashboard statistik real-time  
✅ Portal literasi digital  
✅ Sistem keanggotaan terpusat  

## 👥 Tim Pengembang
| NIM | Nama | Role | Kontribusi |
|------|----------------|-------------------------------|----------------------------|
| 312310120 | Dini Ramadani | Backend Developer | Frontend Developer | CodeIgniter, Database |
| 312310090 | Dian Fitriani | QA & Dokumentasi | Testing, Laporan |
| 312310635 | Jakaria Firmansyah | Frontend Developer | UI/UX, Bootstrap |

**Dosen Pengampu:**  
Agung Nugroho, S.Kom., M.Kom.

## 🛠️ Teknologi
### Frontend
![HTML5](https://img.shields.io/badge/HTML5-E34F26?logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?logo=css3&logoColor=white)
![Bootstrap5](https://img.shields.io/badge/Bootstrap-7952B3?logo=bootstrap&logoColor=white)
![jQuery](https://img.shields.io/badge/jQuery-0769AD?logo=jquery&logoColor=white)
![FontAwesome](https://img.shields.io/badge/Font_Awesome-528DD7?logo=fontawesome&logoColor=white)

### Backend
![CodeIgniter4](https://img.shields.io/badge/CodeIgniter-EF4223?logo=codeigniter&logoColor=white)
![MySQL8](https://img.shields.io/badge/MySQL-4479A1?logo=mysql&logoColor=white)
![PHP8](https://img.shields.io/badge/PHP-777BB4?logo=php&logoColor=white)

## 🌟 Fitur Utama
### 🧑‍💻 Untuk Pengunjung
- 🔍 Pencarian buku dengan multi-filter
- 📚 Preview koleksi buku terbaru
- 📰 Baca artikel literasi
- 🏢 Informasi perpustakaan

### 👨‍🎓 Untuk Anggota
- 📥 Peminjaman online mandiri
- ⏳ Riwayat transaksi lengkap
- 📊 Dashboard pribadi
- 🔔 Notifikasi pengembalian

### 👨‍💼 Untuk Admin
- 📖 Manajemen katalog buku (CRUD)
- 👥 Kelola data anggota
- 📈 Statistik real-time
- ✍️ Publikasi artikel
- 📁 Laporan periodik

## 🖥️ Mockup Desain
![Homepage](docs/mockup-home.png)  
*Lihat desain lengkap di [Figma](https://www.figma.com/design/tK3YWBE25Q4Ca2PIMs5wPy/PerpustakaanPelita)*

## 🚀 Instalasi

### Prasyarat
- PHP 7.4+
- Composer
- MySQL 5.7+
- Node.js (opsional untuk frontend)

### Langkah-langkah
1. Clone repository:
   
```bash
   git clone https://github.com/diniramadani00/library.git
   cd library
```

2. Install dependencies:

    
```bash
composer install
```

3. Setup database:

```bash
mysql -u username -p library < database.sql
```

4. Konfigurasi koneksi di:

```php
app/Config/Database.php
```

5. Jalankan aplikasi:

```bash
php spark serve
```

6. Akses di browser:

```text
http://localhost:8080
```

📂 Struktur Projek
text
library/
├── app/
│   ├── Config/       # Konfigurasi sistem
│   ├── Controllers/  # Logic aplikasi
│   ├── Models/       # Database models
│   └── Views/        # Template halaman
├── public/           # Assets publik
├── database/         # Skema & migrasi
└── tests/            # Unit testing

