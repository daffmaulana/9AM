<?php
session_start();
define('INCLUDE_CHECK', true);

if (isset($_SESSION["email"])) {
    include "navbar-in.php";
} else {
    include "navbar.php";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>9AM - Cerita Kami</title>
    <link rel="icon" href="9AM.svg" type="image/x-icon">
    <link rel="stylesheet" href="home.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>

    .container{
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    a:visited, a:link{
        color: #FF8A08;
    }
    
    .about-section {
    padding: 2em;
    background-color: #f0f0f0;
    }

    .about-section .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1em;
    }

    .about-section h2 {
        font-size: 1.5em;
        margin-bottom: 0.5em;
        color: #333;
    }

    .about-section p {
        font-size: 1em;
        color: #555;
        margin-bottom: 1em;
        line-height: 1.5;
    }

    .about-developers {
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
    }

    .developer-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        padding: 1em;
        margin: 0.5em;
        width: 21%; /* Ensure four cards fit nicely */
        text-align: center;
        transition: transform 0.2s;
    }

    .developer-card:hover {
    transform: scale(1.05);
}

    .developer-card img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 0.5em;
    }

    .developer-card h4 {
        color: #333;
        margin-bottom: 0.5em;
    }

    .developer-card p {
        font-size: 1em;
        color: #555;
        margin-bottom: 0.5em;
    }

    </style>
</head>
<body>
    <!-- ABOUT US SECTION -->
    <section class="about-section">
        <div class="container">
        <h1 style="color:#FF8A08; font-size: 2em;">Cerita Kami</h1>
            <div class="about-history">
                <h2>Awal Cerita</h2>
                <p>9AM didirikan oleh 4 orang pada tahun 2024 dengan misi untuk menghubungkan pencari kerja dengan pekerjaan impian mereka. Selama bertahun-tahun, kami telah membantu ribuan individu menemukan peluang kerja ideal mereka dan bermitra dengan berbagai perusahaan untuk mengisi lowongan mereka dengan talenta terbaik.</p>
            </div>
            <div class="about-function">
                <h2>Tujuan Kami</h2>
                <p>Kami menyediakan platform yang lengkap dengan daftar lowongan pekerjaan, saran karier, dan alat bantu untuk membuat resume profesional. Tujuan kami adalah membuat proses pencarian kerja menjadi lebih mudah dan efisien, sehingga pengguna dapat membangun karier mereka dengan lancar dan percaya diri.</p>
            </div>
            <h2>Kenalan dengan Developers</h2>
            <div class="about-developers">
                <div class="developer-card">
                    <img src="img/dev/dev1.jpg" alt="Developer 1">
                    <h4 style="margin-bottom: 0px;">Daffa Bagus Maulana</h4>
                    <h4 style="margin-top: 0px;">2210511063</h4>
                    <h5 style="color:#6e6e6e; margin:0px"><i>The Mastermind</i></h5>
                    <p>Hallo semuanya! this is Alan speaking. Thank you sudah mengunjungi website ini. I'm open to all discussions, whether personal or professional. Hit me up through my Instagram DMs!"</p>
                    <p><a href="https://instagram.com/dffalann">Visit Instagram</a></p>
                </div>
                <div class="developer-card">
                    <img src="img/dev/dev2.jpg" alt="Developer 2">
                    <h4 style="margin-bottom: 0px;">Ananda Divana</h4>
                    <h4 style="margin-top: 0px;">22105110XX</h4>
                    <h5 style="color:#6e6e6e; margin:0px"><i>Master of Loker</i></h5>
                    <p>Walaupun aku bikin info loker, tapi aku juga butuh info loker, AHAHAH! Info loker ya guys, kasih tahu aku di DM, thank you!</p>
                    <p><a href="https://www.instagram.com/anandadivana/">Visit Instagram</a></p>
                </div>
                <div class="developer-card">
                    <img src="img/dev/dev3.jpg" alt="Developer 3">
                    <h4 style="margin-bottom: 0px;">Dafa Andika Firmansyah</h4>
                    <h4 style="margin-top: 0px;">22105110XX</h4>
                    <h5 style="color:#6e6e6e; margin:0px"><i>Blog Warrior</i></h5>
                    <p>Senang baca berita, membuka jendela dunia. Halo, kenalin aku Dafa, yang siap menemani hari-harimu dengan berita seru!</p>
                    <p><a href="https://www.instagram.com/dafaaaf/">Visit Instagram</a></p>
                </div>
                <div class="developer-card">
                    <img src="img/dev/dev4.jpg" alt="Developer 4">
                    <h4 style="margin-bottom: 0px;">Adrian Fakhriza Hakim</h4>
                    <h4 style="margin-top: 0px;">2210511050</h4>
                    <h5 style="color:#6e6e6e; margin:0px"><i>Builder CV Builder</i></h5>
                    <p>Open for Job.</p>
                    <p><a href="https://www.instagram.com/drianriza_/">Visit Instagram</a></p>
                </div>
            </div>
        </div>
    </section>
    <!-- FOOTER -->
    <?php
    include "footer.php";
    ?>
</body>
</html>
