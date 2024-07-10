<?php
session_start();
define('INCLUDE_CHECK', true);

if (isset($_SESSION["email"])) {
    include "navbar-in.php";
} else {
    include "navbar.php";
}

// Get the article ID from the URL
$id_berita = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_berita > 0) {
    // Prepare and execute the SQL query to fetch the article details
    $sql = "SELECT * FROM data_berita WHERE id = ?";
    $stmt = $conn->prepare($sql);

    // Check if statement was prepared correctly
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("i", $id_berita);
    $stmt->execute();
    $result = $stmt->get_result();
    $berita = $result->fetch_assoc();
    $stmt->close();
} else {
    $berita = null;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>9AM - <?php echo $berita ? htmlspecialchars($berita['judul_berita']) : 'Artikel Tidak Ditemukan'; ?> – 9AM</title>
    <link rel="stylesheet" href="detail_berita.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link href="'https://fonts.googleapis.com/css?family=Muli:400,300|Merriweather:400,300,700'" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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

    .Read {
        padding: 20px;
        border-radius: 10px;
        background-color: #f8f8f8;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin: 0 10% 50px; 
        display: grid;
        grid-template-columns: auto 1fr; /* Menentukan lebar kolom pertama sesuai dengan konten, dan kolom kedua mengisi sisa ruang */
        align-items: start; /* Memposisikan elemen secara vertikal di awal */
        column-gap: 10px; /* Menambahkan jarak antara kolom */
    }

    .Read img {
        width: 100%;
        height: 300px; /* Set a fixed height for the image */
        object-fit: cover; /* Ensure the image covers the area without distorting */
        border-radius: 10px; 
        grid-column: span 2; /* Make the image span across both columns */
    } 

    .Judul {
        padding: 0px;
        font-size: 32px;
        font-weight: bold;
        color: #000000;
        text-align: left;
        margin: 20px 0 0 0;
        grid-column: span 2; /* Make the title span across both columns */
    }

    .Posted{
        margin: 0;

    }

    .Isi {
        padding-right: 50px; 
        font-size: 18px;
        padding: 50px 0;
        text-align: left;
        font-weight: 450;
        margin: 0px;
        grid-column: span 2; /* Make the content span across both columns */
    }

    </style>
</head>

<body>
    <main>
        <a class="back-button" href="javascript:history.back()">
            Kembali ke halaman sebelumnya
        </a>
        <?php if ($berita): ?>
        <div class="Read">
            <img src="img/blog/<?php echo htmlspecialchars($berita['thumbnail']); ?>" alt="">
            <p class="Judul"><?php echo htmlspecialchars($berita['judul_berita']); ?></p>
            <i><p class="Posted">Tanggal Upload: <?php echo htmlspecialchars($berita['tanggal_upload']); ?></p></i>
            <p class="Isi"><?php echo nl2br(htmlspecialchars($berita['isi_berita'])); ?></p>
        </div>
        <?php else: ?>
        <p>Artikel tidak ditemukan. ID Berita: <?php echo htmlspecialchars($id_berita); ?></p>
        <?php endif; ?>
    </main>
<?php
include "footer.php";
?>
</body>
</html>
