<?php
session_start();
define('INCLUDE_CHECK', true);

if (isset($_SESSION["email"])) {
    include "navbar-in.php";
} else {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>9AM - CV Builder</title>
    <style>
        h1, h3 {
            text-align: center;
            margin: 5px;
            color: #FF8A08;
        }

        h1{
            margin-top: 30px;
        }

        form {
            margin: 2% 25%;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-top: 20px;
            color: #34495e;
            font-weight: bold;
        }
        input[type="text"], textarea, input[type="email"], input[type="url"], input[type="tel"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }
        textarea {
            height: 300px;
            resize: vertical;
        }
        input[type="submit"] {
            background-color: #FF8A08;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #c76c08;
        }
</style>

<body>
    </nav><h1>CV Builder</h1>
    <h3>Mulai karirmu dengan CV yang baru!</h3>
    <form action="process_form.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" placeholder="Masukkan Nama Panjang: Adrian Bagus Andika Divana" required>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" placeholder="Masukkan Alamat Lengkap: Jalan Fatmawati Raya, Pondok Labu, Cilandak, Jakarta Selatan, Jakarta 12450" required>

        <label for="phone_number">Phone Number:</label>
        <input type="tel" id="phone_number" name="phone_number" maxlength="14" placeholder="Masukkan Nomor Telepon: 6281256781234" required>

        <label for="email_address">Email Address:</label>
        <input type="email" id="email_address" name="email_address" placeholder="Masukkan Email Valid: contoh@gmail.com" required>

        <label for="linkedin_profile_url">LinkedIn Profile URL:</label>
        <input type="url" id="linkedin_profile_url" name="linkedin_profile_url" placeholder="Masukkan LinkedIn: https://linkedin.com/in/contoh" required>

        <label for="work_experience">Work Experience:</label>
        <textarea id="work_experience" name="work_experience" placeholder="Masukkan Semua Pengalaman Kerja:" required>
Masukkan Pengalaman Kerja:

PERUSAHAAN
Jabatan
    • Tanggung jawab/kpi
    • Tanggung jawab/kpi
    • Tanggung jawab/kpi

PT. Mayora Makmur Tbk.
Direktur Utama Operasional
    • Mengawasi efisiensi operasional harian
    • Memastikan pencapaian target produksi dan kualitas
    • Mengelola anggaran operasional dengan KPI
        </textarea>

        <label for="organization_experience">Organization Experience:</label>
        <textarea id="organization_experience" name="organization_experience" required>
Masukkan Pengalaman Organisasi:

ORGANISASI
Jabatan
    • Tanggung jawab/kpi
    • Tanggung jawab/kpi
    • Tanggung jawab/kpi

AIESEC in Wonderland
Vice President of Marketing
    • Melayani 33 delegasi untuk memastikan keterlibatan dan pengembangan mereka dengan proyek.
    • Dianugerahi sebagai departemen terbaik kuartal pertama dari 6 departemen lainnya.
    • Melebihi target pendaftar untuk produk AIESEC Future Leaders, mencapai 46/40.
        </textarea>

        <label for="education">Education:</label>
        <textarea id="education" name="education" required>
Masukkan Riwayat Pendidikan:

UNIVERSITAS/PENDIDIKAN LAINNYA
Jurusan, Fakultas, IPK
    • Pengalaman Perkuliahan/Pendidikan Lainnya
    • Pengalaman Perkuliahan/Pendidikan Lainnya
    • Pengalaman Perkuliahan/Pendidikan Lainnya

UNIVERSITAS PEMBANGUNAN NASIONAL "VETERAN" JAKARTA
Informasi, Fakultas Ilmu Komputer, 4.00/4.00
    • Aktif terlibat dalam 2 komunitas mahasiswa global, mengelola lebih dari 7 program unggulan.
    • Secara konsisten mencapai kinerja tinggi dengan IPK 3,90, sambil menyeimbangkan tugas kuliah dan keterlibatan organisasi.
    • Memimpin tim penelitian yang memenangkan penghargaan sebagai proyek terbaik dalam kompetisi nasional mahasiswa.
        </textarea>

        <label for="skill">Skills:</label>
        <textarea id="skill" name="skill" required>
Masukkan Keahlian:

• Keahlian Lainnya    :   .........................

• Skills              :	Leadership, Creative Thinking, Analytical, and Problem Solving.
• Softwares           :	Microsoft Office (Ms. Word, Ms. Excel, Ms. Powerpoint) and Adobe Family (Photoshop, Illustrator, Premiere Pro).
• Languages           :  Indonesian (Native), English (Beginner).
• Programming Language: 	Python, C++, C, Java.
        </textarea>

        <label for="certificates">Certificates:</label>
        <textarea id="certificates" name="certificates" required>
Masukkan Penghargaan:

• (TAHUN):  DESKRIPSI PENGHARGAAN

• (2024):  PEMENANG PIALA CITRA AKTOR TERBAIK BY SZATV
• (2023):  JUARA 1 LOMBA TERLATIH PATAH HATI
• (2023):  MEMBER TERBAIK BULAN SEPTEMBER
        </textarea>

        <label for="reference">Reference:</label>
        <textarea id="reference" name="reference" required>
Masukkan Relasi Profesional:

• Nama - Jabatan - Email

• Praz Mulyo - Kepala Pembangunan UPNVJ - prasyo@gmail.com
• Silvi Pradita - Ketua Tim Marketing - silprad@gmail.com
        </textarea>

        <label for="describe_urself">Describe Yourself:</label>
        <textarea id="describe_urself" name="describe_urself" placeholder="Masukkan Deskripsi Diri" required></textarea>
        <input type="submit" value="Generate CV">
    </form>
<?php
include "footer.php";
?>
</body>
</html>