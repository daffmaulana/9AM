<?php
session_start();
define('INCLUDE_CHECK', true);

if (isset($_SESSION["email"])) {
    include "navbar-in.php";
} else {
    include "navbar.php";
}


// Tentukan jumlah berita per halaman
$jobs_per_page = 15;

// Dapatkan nomor halaman dari parameter GET, default ke halaman 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $jobs_per_page;

// Inisialisasi variabel untuk menyimpan hasil pencarian
$jobs = array();
$total_pages = 1;

// Proses pencarian jika ada query yang dikirimkan
if (isset($_GET['query'])) {
    $query = $_GET['query'];
    // Query SQL untuk menghitung total berita yang sesuai dengan kata kunci
    $sql_count = "SELECT COUNT(*) as total FROM jobs WHERE job_title LIKE '%$query%' OR company LIKE '%$query%'";
    $result_count = $conn->query($sql_count);
    $total_jobs = $result_count->fetch_assoc()['total'];
    $total_pages = ceil($total_jobs / $jobs_per_page);

    // Query SQL untuk mengambil berita dengan limitasi dan offset
    $sql = "SELECT * FROM jobs WHERE job_title LIKE '%$query%' OR company LIKE '%$query%' LIMIT $jobs_per_page OFFSET $offset";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Potong isi_berita menjadi 100 karakter
            $jobs[] = $row;
        }
    } else {
        // Jika tidak ada hasil, tetapkan $data_berita ke array kosong
        $jobs = array();
    }
} else {
    // Query SQL untuk menghitung total berita
    $sql_count = "SELECT COUNT(*) as total FROM jobs";
    $result_count = $conn->query($sql_count);
    $total_jobs = $result_count->fetch_assoc()['total'];
    $total_pages = ceil($total_jobs / $jobs_per_page);

    // Query SQL untuk mengambil semua data berita dengan limitasi dan offset
    $sql = "SELECT * FROM jobs LIMIT $jobs_per_page OFFSET $offset";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Potong isi_berita menjadi 100 karakter
            $jobs[] = $row;
        }
    }
}



// $sqlJobs = "SELECT * FROM jobs";
// $resultJobs = $conn->query($sqlJobs);

// $jobs = array();

// if ($resultJobs->num_rows > 0) {
//     while ($row = $resultJobs->fetch_assoc()) {
//         $jobs[] = $row;
//     }
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>9AM – Lowongan Kerja</title>
    <link rel="icon" href="9AM.svg" type="image/x-icon">
    <link rel="stylesheet" href="home.css" />
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

        .form-inline {
            display: flex;
            align-items: center;
            width: 100%;
            margin: 0% 10% 0%;
        }

        .form-control {
            margin-right: 10px; /* Jarak antara input dan tombol */
            padding: 8px; /* Padding agar input lebih berisi */
            border-radius: 5px; /* Membuat sudut input sedikit melengkung */
            border: 1px solid #FF8A08; /* Garis tepi input */
            width: 100%;
        }

        .btn {
            padding: 8px 16px; /* Padding tombol */
            border-radius: 5px; /* Membuat sudut tombol sedikit melengkung */
            border: none; /* Menghilangkan garis tepi tombol */
            cursor: pointer; /* Mengubah kursor saat diarahkan ke tombol */
            width: 100px;
        }

        .btn-outline-success {
            background-color: transparent; /* Menghapus warna latar belakang */
            color: #FF8A08; /* Warna teks hijau */
            border: 1px solid #FF8A08; /* Garis tepi tombol hijau */
        }

        .btn-outline-success:hover {
            background-color: #FF8A08; /* Warna latar belakang saat dihover */
            color: #fff; /* Warna teks saat dihover */
        }
        
        .containerlowongan {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* 3 columns */
    grid-template-rows: repeat(2, auto); /* 2 rows */
    gap: 20px;
    margin: 0% 10%;
}

.lowocard {
    background-color: rgb(255, 255, 255);
    color: rgb(0, 0, 0);
    border-radius: 10px;
    font-size: 14px;
    height: 200px; /* Ensuring all cards have the same height */
    padding: 20px;
    text-align: left;
    display: flex;
    flex-direction: column;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.3);
    transition: transform 0.2s;
}

.lowocard:hover {
    transform: scale(1.05);
    /* Slightly enlarge the card on hover */
}

.lowocard-link {
    text-decoration: none; /* Remove underline from link */
    color: inherit; /* Inherit text color */
    display: block; /* Make the link block-level to cover the entire card */
}

.lowocard img {
    max-width: 50px;
    max-height: 50px;
}

.lowocard .image-container {
    display: flex;
    align-items: center;
}

.lowocard .details {
    padding-top: 10px;
    display: grid;
    grid-template-columns: auto 1fr;
    align-items: center;
    column-gap: 10px;
}

.lowocard .textcontainer {
    padding-left: 20px;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.lowocard .jobpos {
    padding: 0px;
    font-size: 16px;
    font-weight: bold;
    color: #000000;
    text-align: left;
    margin: 0px;
}

.lowocard .company {
    font-size: 14px;
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
    text-align: left;
    margin: 0px;
}

.lowocard .worktype, .worktime, .level {
    text-align: left;
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

@media (max-width: 768px) {
    .containerlowongan {
        grid-template-columns: 1fr; /* Single column layout for smaller screens */
        grid-template-rows: auto;
    }
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
    <script src="mainn.js"></script>
    <script src="animate.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    
</head>
<body>
    <main>
    <h1>Lowongan Kerja</h1>    
    <div class="search-bar">
        <form action="lowongankerja.php" method="GET">
            <input type="text" name="query" placeholder="Cari Perusahaan atau Posisi Impianmu! " value="<?php echo isset($_GET['query']) ? $_GET['query'] : ''; ?>">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>
    <div class="containerlowongan">
        <?php foreach ($jobs as $job): ?>
            <a href="detail_lowongan.php?id=<?php echo $job['id']; ?>" class="lowocard-link">
                <div class="lowocard">
                    <div class="image-container">
                        <img draggable="false" src="img/loker/<?php echo $job['image_url']; ?>" alt="a">
                        <div class="textcontainer">
                            <p class="jobpos"><?php echo $job['job_title']; ?></p>
                            <p class="company"><?php echo $job['company']; ?></p>
                            <p class="loc"><?php echo $job['location']; ?></p>
                        </div>
                    </div>
                    <div class="details">
                        <i class="fa-solid fa-dollar-sign"></i>
                        <p class="salary"><?php echo $job['salary']; ?></p>
                        <i class="fa-solid fa-location-dot"></i>
                        <p class="worktype"><?php echo $job['work_type']; ?></p>
                        <i class="fa-solid fa-clock"></i>
                        <p class="worktime"><?php echo $job['work_time']; ?></p>
                        <i class="fa-solid fa-briefcase"></i>
                        <p class="level"><?php echo $job['level']; ?></p>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>

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
