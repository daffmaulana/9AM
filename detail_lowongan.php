<?php
session_start();
define('INCLUDE_CHECK', true);

if (isset($_SESSION["email"])) {
    include "navbar-in.php";
} else {
    include "navbar.php";
}
?>
<?php
include 'config.php'; // Include the database configuration file
// Get the article ID from the URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 1;

if ($id > 0) {
    // Prepare and execute the SQL query to fetch the article details
    $sql = "SELECT * FROM jobs WHERE id = ?";
    $stmt = $conn->prepare($sql);

    // Check if statement was prepared correctly
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $job = $result->fetch_assoc();
    $stmt->close();
} else {
    $job = null;
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>9AM – Lowongan Kerja</title>
    <link rel="icon" href="9AM.svg" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <style>
            
    .back-button {
        color: orange;
        text-decoration: underline;
        padding-right: 150px;
        padding-top: 50px;
        padding-left: 130px;
        padding-bottom: 10px;    
        display: block;
        text-align: left;
        margin-left: 20px; /* Adjust left margin for positioning */
        margin-bottom: 10px; /* Adjust margin as needed */
        font-weight: bold;
    }
    .containerlowongan {
        display: flex;
        flex-wrap: wrap;
        margin: 0% 10%;
        justify-content: space-between;

    }

    .lowocard {
        background-color: rgb(255, 255, 255);
        color: rgb(0, 0, 0);
        border-radius: 10px;
        font-size: 14px;
        min-width: 90%;
        height: 200px;
        padding: 20px;
        margin-bottom: 50px;
        text-align: left;
        display: flex;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.3);
        flex-direction: column;
    }

    .lowocard img {
        max-width: 50px;
        max-height: 50px;
    }

    .lowocard .image-container {
        display: flex;
        align-items: center;
    }

    .button-lamar, .button-lamar a {
        background-color: #FF8A08;
        border-radius: 100px;
        border: none;
        height: 50px;
        width: 200px;
        padding:0px;
        font-size: 25px;
        color: white;
        font-weight: bold;
    }

    .lowocard .details {
        padding-top: 10px;
        padding-left: 70px;
        padding-bottom: 10px;
        display: grid;
        grid-template-columns: auto 1fr; /* Menentukan lebar kolom pertama sesuai dengan konten, dan kolom kedua mengisi sisa ruang */
        align-items: center; /* Memposisikan elemen secara vertikal di tengah */
        column-gap: 10px; /* Menambahkan jarak antara kolom */
    }


    .lowocard .textcontainer {
        padding-left: 20px;
        display: flex;
        flex-direction: column; /* Stack text elements vertically */
        flex: 1; /* Take up remaining space */
    }

    .lowocard .jobpos {
        padding: 0px;
        font-size: 32px;
        font-weight: bold;
        color: #000000;
        text-align: left;
        margin: 0px;
    }

    .lowocard .company {
        font-size: 20px;
        text-align: left;
        font-weight: 500;
        margin: 0px;
    }

    .lowocard .loc {
        color: #6D6D6D;
        font-size: 12px;
        text-align: left;
    }

    .lowocard .salary {
        padding: 0px;
        font-size: 16px;
        font-weight: bold;
        color: #FF8A08;
        text-align: left; /* left align below the image */
        margin: 0px;
    }

    .lowocard .worktype {
        text-align: left; /* left align below the image */
        margin: 0px;
    }

    .lowocard .worktime {
        text-align: left; /* left align below the image */
        margin: 0px;
    }

    .lowocard .level {
        text-align: left; /* left align below the image */
        margin: 0px;
    }

    .lowocard i {
        margin: 8px 0px 8px;
        color: #000000;
    }

    .lowocard p {
        text-align: left;
        margin: 0px;
    }

    .lowocard .Desc {
        padding-right: 100px;
        padding-top: 10px;
        padding-left: 70px;
        padding-bottom: 10px;
        grid-template-columns: auto 1fr; /* Menentukan lebar kolom pertama sesuai dengan konten, dan kolom kedua mengisi sisa ruang */
        align-items: center; /* Memposisikan elemen secara vertikal di tengah */
        column-gap: 10px; /* Menambahkan jarak antara kolom */
    }

    .lowocard .Deskripsi {
        padding: 0px;
        font-size: 28px;
        font-weight: bold;
        color: #000000;
        text-align: left;
        margin: 0px;
    }

    .lowocard .about_us {
        font-size: 18px;
        padding-top: 15px;
        text-align: left;
        font-weight: bold;
        margin: 0px;
    }

    .lowocard .about_us_text {
        font-weight: 500;
        text-align: left;
        margin: 0px;
    }

    .lowocard .about_the_opport {
        font-size: 18px;
        padding-top: 15px;
        text-align: left;
        font-weight: bold;
        margin: 0px;
    }

    .lowocard .about_the_opport_text {
        font-weight: 500;
        text-align: left;
        margin: 0px;
    }

    .lowocard .responsibility {
        font-size: 18px;
        padding-top: 15px;
        text-align: left;
        font-weight: bold;
        margin: 0px;
    }

    .lowocard .responsibility_text {
        font-weight: 500;
        text-align: left;
        margin: 0px;
        font-family: 'Open Sans', sans-serif;
        white-space: pre-wrap;
    }

    .lowocard .kualifikasi {
        font-size: 18px;
        padding-top: 15px;
        text-align: left;
        font-weight: bold;
        margin: 0px;
    }

    .lowocard .about_you {
        font-size: 18px;
        padding-top: 15px;
        text-align: left;
        font-weight: bold;
        margin: 0px;
    }

    .lowocard .essentials {
        font-size: 18px;
        text-align: left;
        font-weight: bold;
        margin: 0px;
    }

    .lowocard .essentials_text {
        font-weight: 500;
        text-align: left;
        margin: 0px;
        font-family: 'Open Sans', sans-serif;
        white-space: pre-wrap;
    }
    </style>
</head>
<body>

    <!-- CONTENT -->
    <main>
    <a class="back-button" href="javascript:history.back()">
            Kembali ke halaman sebelumnya
    </a>
    <?php if ($job): ?>
    <div class="containerlowongan">
            <div class="lowocard">
                <div class="image-container">
                    <img draggable="false" src="img/loker/<?php echo htmlspecialchars($job['image_url']); ?>" alt="a">
            <div class="textcontainer">
                        <p class="jobpos"><?php echo htmlspecialchars($job['job_title'] ); ?></p>
                        <p class="company"><?php echo htmlspecialchars($job['company']); ?></p>
                </div>
                <a class="button-lamar" href=<?php echo htmlspecialchars($job['url']); ?>><button class="button-lamar">Lamar!</button></a>
                </div>  
                <div class="details">
                    <i class="fa-solid fa-dollar-sign"></i>
                    <p class="salary"><?php echo htmlspecialchars($job['salary']); ?></p>
                    <i class="fa-solid fa-location-dot"></i>
                    <p class="worktype"><?php echo htmlspecialchars($job['work_type']); ?></p>
                    <i class="fa-solid fa-clock"></i>
                    <p class="worktime"><?php echo htmlspecialchars($job['work_time']); ?></p>
                    <i class="fa-solid fa-briefcase"></i>
                    <p class="level"><?php echo htmlspecialchars($job['level']); ?></p>
                </div>
                <hr width="90%" height="2px">
                <div class="Desc">
                    <p class="Deskripsi">Deskripsi Pekerjaan</p>
                    <p class="about_us">About us</p>
                    <p class="about_us_text"><?php echo htmlspecialchars($job['about_us']); ?></p>
                    <p class="about_the_opport">About the opportunity</p>
                    <p class="about_the_opport_text"><?php echo str_replace("itzspace", "<br>",  htmlspecialchars($job['opport'])); ?></p>
                    <p class="responsibility">Your main responsibility is:</p>
                    <pre class="responsibility_text" id="text-content"><?php echo htmlspecialchars($job['responsibilities']); ?></pre>
                    <p class="kualifikasi">Kualifikasi</p>
                    <pre class="essentials_text" id="text-content"><?php echo htmlspecialchars($job['essential']); ?></pre>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </main>
<?php
include "footer.php";
?>
</body>
</html>