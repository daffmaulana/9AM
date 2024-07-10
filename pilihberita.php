<?php
session_start();
define('INCLUDE_CHECK', true);

if (isset($_SESSION["email"])) {
    include "navbar-in.php";
} else {
    include "navbar.php";
}

// Tentukan jumlah berita per halaman
$news_per_page = 15;

// Dapatkan nomor halaman dari parameter GET, default ke halaman 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $news_per_page;

// Inisialisasi variabel untuk menyimpan hasil pencarian
$data_berita = array();
$total_pages = 1;

// Proses pencarian jika ada query yang dikirimkan
if (isset($_GET['query'])) {
    $query = $_GET['query'];
    // Query SQL untuk menghitung total berita yang sesuai dengan kata kunci
    $sql_count = "SELECT COUNT(*) as total FROM data_berita WHERE judul_berita LIKE '%$query%'";
    $result_count = $conn->query($sql_count);
    $total_news = $result_count->fetch_assoc()['total'];
    $total_pages = ceil($total_news / $news_per_page);

    // Query SQL untuk mengambil berita dengan limitasi dan offset
    $sql = "SELECT * FROM data_berita WHERE judul_berita LIKE '%$query%' LIMIT $news_per_page OFFSET $offset";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Potong isi_berita menjadi 100 karakter
            $row['isi_berita'] = substr($row['isi_berita'], 0, 100) . '...';
            $data_berita[] = $row;
        }
    } else {
        // Jika tidak ada hasil, tetapkan $data_berita ke array kosong
        $data_berita = array();
    }
} else {
    // Query SQL untuk menghitung total berita
    $sql_count = "SELECT COUNT(*) as total FROM data_berita";
    $result_count = $conn->query($sql_count);
    $total_news = $result_count->fetch_assoc()['total'];
    $total_pages = ceil($total_news / $news_per_page);

    // Query SQL untuk mengambil semua data berita dengan limitasi dan offset
    $sql = "SELECT * FROM data_berita LIMIT $news_per_page OFFSET $offset";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Potong isi_berita menjadi 100 karakter
            $row['isi_berita'] = substr($row['isi_berita'], 0, 100) . '...';
            $data_berita[] = $row;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>9AM – Blog</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>

        h1{
            color: #FF8A08;
            margin: 3% 10%;
        }

        .search-bar {
            background-color: #EFEFEF;
            margin: 1% 10%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .search-bar form {
            display: flex;
            align-items: center;
            width: 100%;
        }

        .search-bar input[type="text"] {
            padding: 10px;
            border: none;
            border-radius: 5px;
            width: 100%;
            margin-right: 10px;
        }

        .search-bar button {
            background-color: #FF8A08;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #FFA733;
        }

        .containerlowongan {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin: 0% 10%;
        }

        .lowocard-link {
            text-decoration: none;
            /* Remove underline from link */
            color: inherit;
            /* Inherit text color */
            display: block;
            /* Make the link block-level to cover the entire card */
        }

        .lowocard {
            position: relative;
            background-color: rgb(255, 255, 255);
            color: rgb(0, 0, 0);
            border-radius: 10px;
            font-size: 14px;
            height: 300px;
            width: 100%;
            overflow: hidden;
            padding: 0;
            text-align: left;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.3);
            transition: transform 0.2s;
            /* Add a transition for hover effect */
        }

        .lowocard:hover {
            transform: scale(1.05);
            /* Slightly enlarge the card on hover */
        }

        .lowocard img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
        }

        .lowocard .thumbnail {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .lowocard .textcontainer {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 40%;
            width: 100%;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 10px;
            box-sizing: border-box;
        }

        .lowocard .judul {
            padding: 0px;
            font-size: 16px;
            font-weight: bold;
            color: #ffffff;
            text-align: left;
            margin: 0px 0 10px 0;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .lowocard .isi {
            font-size: 14px;
            text-align: left;
            font-weight: 500;
            margin: 0px;
            color: #dcdcdc;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            margin-bottom: 50px;
        }

        .pagination a {
            color: white;
            text-decoration: none;
            margin: 0 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            background-color: orange;
            border-radius: 50%;
        }

        .pagination a.active {
            background-color: darkorange;
        }
    </style>
</head>

<body>
    <main>
        <h1>Blog</h1>
        <!-- Search Bar -->
        <div class="search-bar">
            <form action="pilihberita.php" method="GET">
                <input type="text" name="query" placeholder="Cari Tips n Trick Interview? Cara Membuat CV? Prospek Pekejaan di Masa Depan?" value="<?php echo isset($_GET['query']) ? $_GET['query'] : ''; ?>">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <div class="containerlowongan">
            <?php if (!empty($data_berita)) : ?>
                <?php foreach ($data_berita as $berita) : ?>
                    <a href="detail_berita.php?id=<?php echo $berita['id']; ?>" class="lowocard-link">
                        <div class="lowocard">
                            <div class="thumbnail">
                                <img draggable="false" src="img/blog/<?php echo $berita['thumbnail']; ?>" alt="<?php echo $berita['judul_berita']; ?>">
                                <div class="textcontainer">
                                    <p class="judul"><?php echo $berita['judul_berita']; ?></p>
                                    <p class="isi"><?php echo $berita['isi_berita']; ?></p>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="lowocard">
                    <p style="text-align:center;">Tidak ada berita yang ditemukan.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Navigasi Halaman -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                <a href="?<?php echo isset($_GET['query']) ? 'query=' . $_GET['query'] . '&' : ''; ?>page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
    </main>

    <!-- FOOTER -->
    <?php
include "footer.php";
?>
</body>

</html>
