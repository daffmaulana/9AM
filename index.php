<?php
session_start();
define('INCLUDE_CHECK', true);

if (isset($_SESSION["email"])) {
    include "navbar-in.php";
} else {
    include "navbar.php";
}
// Fetch testimonials
$sqlTestimonials = "SELECT * FROM testimonials";
$resultTestimonials = $conn->query($sqlTestimonials);

// Fetch carousel images
$sqlCarousel = "SELECT * FROM carousel_images";
$resultCarousel = $conn->query($sqlCarousel);

// Fetch 6 newest jobs
$sqlJobs = "SELECT * FROM jobs ORDER BY id DESC LIMIT 6";
$resultJobs = $conn->query($sqlJobs);

$jobs = array();

if ($resultJobs->num_rows > 0) {
    while ($row = $resultJobs->fetch_assoc()) {
        $jobs[] = $row;
    }
}


// Fetch 6 newest jobs
$sqlNews = "SELECT * FROM data_berita ORDER BY id DESC LIMIT 6";
$resultNews = $conn->query($sqlNews);

$data_berita = array();

if ($resultNews->num_rows > 0) {
    while ($row = $resultNews->fetch_assoc()) {
        $data_berita[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>9AM - Web Cari Kerja Indonesia</title>
    <link rel="icon" href="img/9AM.svg" type="image/x-icon">
    <!-- <link rel="stylesheet" href="home.css" /> -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        
section{
    scroll-margin-top:1em;
}




.slider-wrapper{
    position: relative;
    margin: 0 auto;
}
.slider{
    display: flex;
    aspect-ratio: 6;
    overflow-x: hidden;
    scroll-snap-type: x, mandatory;
    scroll-behavior: smooth;
}
.slider img{
    flex: 1 0 100%;
    scroll-snap-align: start;
    object-fit: cover;
}
.slider-nav{
    display: flex;
    column-gap: 1rem;
    position: absolute;
    bottom: 1.25rem;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1;
}
.slider-nav a{
    width: 0.5rem;
    height: 0.5rem;
    border-radius: 50%;
    background-color: white;
    opacity: 0.75;
    transition: opacity ease 250ms;
}
.slider-nav a:hover{
    opacity: 100%;
}

/* LOWONGAN KERJA *//* LOWONGAN KERJA */
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


.containernews {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin: 0% 10%;
}

.newscard-link {
    text-decoration: none;
    /* Remove underline from link */
    color: inherit;
    /* Inherit text color */
    display: block;
    /* Make the link block-level to cover the entire card */
}

.newscard {
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

.newscard:hover {
    transform: scale(1.05);
    /* Slightly enlarge the card on hover */
}

.newscard img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 10px 10px 0 0;
}

.newscard .thumbnail {
    position: relative;
    width: 100%;
    height: 100%;
}

.newscard .textcontainer {
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

.newscard .judul {
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

.newscard .isi {
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


.titlesection h1{
    color: #FF8A08;
}
.titlesection{
    display: inline-flex;
    justify-content: space-between;
    margin: 5% 10% 1% 10%;
    align-items: center;
}

.button-show, .button-show a {
    background-color: #FF8A08;
    border-radius: 100px;
    border: none;
    height: 50px;
    width: 120px;
    padding: 0px 3px;
    font-size: 16px;
    color: white;
    font-weight: bold;
}


/* TESTIMONI */

.containergambartesti{
    background-image: url("img/testi/testibg.jpg");
    background-size: cover;
    padding: 100px;
    margin: 5% 0 5%;
}

.containertexttesti{
    text-align: center;
}

.containertexttesti h1{
    color: white;
    margin: 0px;
}
.containergambartesti p{
    color: #dadada;
    margin: 0px;
}

.containertesti{
    display: flex;
    flex-wrap: wrap;
    margin: 0% 5%;
    justify-content:space-around;}

.testicard{
    flex: 1 0 0;
    background-color: white;
    color: black;
    border-radius: 10px;
    font-size: 14px;
    margin: 30px 10px;
    padding: 20px;
    text-align: center;
    transition: transform 0.2s;
}
.testicard:hover {
    transform: scale(1.05);
    /* Slightly enlarge the card on hover */
}

.testicard img{
    margin: 0px 0px 8px;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
}
.testicard .role{
    color: #6D6D6D;
    text-align: center;
}
.testicard p{
    text-align: left;
    display: -webkit-box; 
    -webkit-line-clamp: 7; 
    -webkit-box-orient: vertical;
    text-overflow: ellipsis;
    overflow: hidden;
    margin: 0px;
    color: black;
}
.testicard i{
    margin: 8px 0px 8px;
    color: #FF8A08;

}
.testicard h4{
    margin:0px;
    color:black
}


    </style>
</head>
<body>
    <!-- CAROUSEL -->
    <section class="containerslider">
        <div class="slider-wrapper">
            <div class="slider">
            <?php
                $count = 0;
                while ($carousel = $resultCarousel->fetch_assoc()) { 
                    $imagePath = 'img/carousel/' . htmlspecialchars($carousel['image']);
                    $altText = htmlspecialchars($carousel['alt_text']);
                    echo '<img id="slide-' . $count . '" draggable="false" src="' . $imagePath . '" alt="' . $altText . '">';
                    $count++; 
                } ?>
            </div>
            <div class="slider-nav">
                <?php
                $resultCarousel->data_seek(0); // Reset the result set pointer
                $count = 0;
                while ($carousel = $resultCarousel->fetch_assoc()) {
                    echo '<a href="#slide-' . $count . '"></a>';
                    $count++;
                }
                ?>
            </div>
        </div>
    </section>

    <!-- LOWONGAN KERJA -->
     <div class="titlesection">
        <h1>Lowongan Kerja Populer</h1>
        <a class="button-show" href="lowongankerja.php"><button class="button-show">Lihat Lainnya</button></a>
     </div>
    <div class="containerlowongan" style="margin-top: 0%;margin-bottom: 0%;">
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

    <!-- BERITA -->
    <div class="titlesection">
        <h1>Tips & Trik Buat Kamu</h1>
        <a class="button-show" href="pilihberita.php#"><button class="button-show">Baca Lainnya</button></a>
    </div>
    <div class="containernews">
        <?php foreach ($data_berita as $berita): ?>
            <a href="detail_berita.php?id=<?php echo $berita['id']; ?>" class="newscard-link">
                <div class="newscard">
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
    </div>

    <!-- TESTIMONI -->

    <main>
        <div class="containergambartesti">
            <div class="containertexttesti">
                <h1>Mantan Pejuang Loker</h1>
                <p>Mereka berhasil dapat kerja dari 9AM. Apa kata mereka?</p>
            </div>
            <div class="containertesti">
                <?php while ($testimonial = $resultTestimonials->fetch_assoc()) { ?>
                    <div class="testicard">
                        <img draggable="false" src="img/testi/<?php echo htmlspecialchars($testimonial['image']); ?>" alt="Foto Profile Testimoni <?php echo htmlspecialchars($testimonial['name']); ?>">
                        <h4><?php echo htmlspecialchars($testimonial['name']); ?></h4>
                        <p class="role"><?php echo htmlspecialchars($testimonial['role']); ?></p>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <p><?php echo htmlspecialchars($testimonial['content']); ?></p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </main>
    <!-- FOOTER -->
<?php
include "footer.php";
?>
</body>
</html>
