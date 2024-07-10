<?php
if (!defined('INCLUDE_CHECK')) {
    http_response_code(403);
    echo "Forbidden";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/9AM.svg" type="image/x-icon">
    <link rel="stylesheet" href="style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>       
    footer{
        display: flex;
        flex-wrap: wrap;
        background-color: #1C1C1C;
        padding: 30px 10%;
    }

    .footercol{
        display: flex;
        flex: 1 0 0;
        justify-content: center;
        align-items: center;
        color: white;
        margin: 0px 20px;
    }

    .footerlogo img{
        max-width: 50%;
    }
    .footerlogo{
        text-align: center;
    }

    .footercol a:visited, .footercol a:link{
        color: #dcdcdc;
    }
    .footerflex{
        display: flex;
        flex-direction: column;
        justify-content: start;
    }
    .footercol li{
        text-decoration: none;
        list-style: none;
    }

    .footercol h4{
        position: relative;
        display: inline-block;
    }
    .footercol h4::after{
        content: "";
        position: absolute;
        left:0px;
        bottom:-8px;
        background-color: #FF8A08;
        height: 2px;
        width: 100%;
    }

    .footercol ul{
        margin: 0px;
        padding: 0px;
    }

    .logosocmed a{
        margin: 0px 5px 0px 5px;
        font-size: 50px;
    }
    </style>
</head>
<body>
    <!-- FOOTER -->
    <footer>
        <div class="footercol">
            <div class="footerlogo"><a href="index.php#"><img draggable="false" src="img/9AM.svg" alt="Logo 9AM"></a></div>
        </div>
        <div class="footercol">
            <div>
                <h4>Alamat</h4>
                <ul>
                    <li><a target="_blank" href="https://maps.app.goo.gl/uFGQ3cNDB9ugFrzW6">9AM Group</a></li>
                    <li><a target="_blank" href="https://maps.app.goo.gl/uFGQ3cNDB9ugFrzW6">UPNVJ Working Space</a></li>
                    <li><a target="_blank" href="https://maps.app.goo.gl/uFGQ3cNDB9ugFrzW6">Jl. Fatmawati Raya 1</a></li>
                    <li><a target="_blank" href="https://maps.app.goo.gl/uFGQ3cNDB9ugFrzW6">Pondok Labu</a></li>
                    <li><a target="_blank" href="https://maps.app.goo.gl/uFGQ3cNDB9ugFrzW6">Jakarta Selatan</a></li>
                    <li><a target="_blank" href="https://maps.app.goo.gl/uFGQ3cNDB9ugFrzW6">Daerah Khusus Jakarta</a></li>
                    <li><a target="_blank" href="https://maps.app.goo.gl/uFGQ3cNDB9ugFrzW6">12450</a></li>
                </ul>
            </div>
        </div>
        <div class="footercol footerflex">
            <div>
                <h4>Tentang</h4>
                <ul>
                    <li><a href="aboutus.php#">Cerita Kami</a></li>
                    <li><a href="privacypolicy.php#">Privacy Policy</a></li>
                    <li><a href="termsconditions.php#">Terms Conditions</a></li>
                </ul>
            </div>
        </div>
        <div class="footercol footersocmed">
            <div class="logosocmed">
                <a target="_blank" href="https://www.linkedin.com/in/daffamaulana/"><i class="fab fa-linkedin"></i></a>
                <a target="_blank" href="https://www.instagram.com/dffalann"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </footer>
</body>
</html>
