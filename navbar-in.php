<?php
if (!defined('INCLUDE_CHECK')) {
    http_response_code(403);
    echo "Forbidden";
    exit();
}

if (!isset($_SESSION["email"])) {
   header("Location: login.php");
   exit();
}

require 'config.php';
$email = $_SESSION["email"];

// Query for the specific user based on the session data
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if (!$result) {
    echo "User not found!";
    exit();
}

$profileImage = $result['image'] ? 'img/user/' . $result['image'] : 'img/user/default-profile.jpg'; // Set default image if none exists
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/9AM.svg" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        *{
            text-decoration: none;
        }

        html{
            scroll-behavior: smooth;
            scroll-padding-top: 200px;
        }

        body{
            font-family: 'Open Sans', sans-serif;
            margin: 0px;
            width: 100%;
            background-color: #F0F0F0;
            text-decoration: none;
            display: flex;
            flex-direction: column;
        }
        .navbar{
            position: sticky;
            top: 0;
            z-index: 2;
            background: #FF8A08; 
            padding-right: 5%;
            padding-left: 5%;
        }
        .navdiv{
            display: flex;
            align-items: center;
            justify-content:space-between;
        }
        .navlogo a{
            display: flex;
            margin:15px 0px;
        }
        .navlogo img{
            max-height: 50px;
        }
        .navdir{
            padding:0px;
            list-style: none;
            margin: 0%;
        }
        .navli {
            display: inline-block;
            color: white;
            font-size: 16px;
            font-weight: bold;
            padding-inline: 20px;
        }
        .navli a:visited, .navli a:link {
            color: white;
        }
        .navbutton{
            padding: 0px;
            display: flex;
            justify-content: center;
            margin: 3px 0px;
            height: 100%;
        }
        .fa-right-from-bracket, .fa-right-from-bracket a{
            padding: 0px 0px;
            margin: 20px 0px 20px 5px;
            text-align: center;
        }
        .button-login{
            background-color: transparent;
            border: none;
        }
        .button-login a{
            padding: 0px;
            font-size: 16px;
            color: white;
            font-weight: bold;
            overflow: hidden;
        }
        .profpic{
            flex-direction: column;
            justify-content: center;
            width: 50px; 
            height: 50px; 
            border-radius: 50%;
            object-fit: cover;
            overflow: hidden;
            margin: 0px 5px 0px 0px;
        }
    </style>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/js/bootstrap.min.js" integrity="sha512-EKWWs1ZcA2ZY9lbLISPz8aGR2+L7JVYqBAYTq5AXgBkSjRSuQEGqWx8R1zAX16KdXPaCjOCaKE8MCpU0wcHlHA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap.min.css" integrity="sha512-Ez0cGzNzHR1tYAv56860NLspgUGuQw16GiOOp/I2LuTmpSK9xDXlgJz3XN4cnpXWDmkNBKXR/VDMTCnAaEooxA==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
</head>
<body>
    <!-- NAVBAR -->    
    <nav class="navbar">
        <div class="navdiv">
            <div class="navlogo"><a href="index.php#"><img draggable="false" src="img/9AM-white.svg" alt="Logo 9AM"></a></div>
            <ul class="navdir">
                <li class="navli"><a href="lowongankerja.php#">Lowongan Kerja</a></li>
                <li class="navli"><a href="pilihberita.php#">Blog</a></li>
                <li class="navli"><a href="cv_builder.php#">CV Builder</a></li>
            </ul>
            <ul class="navbutton">
                <img class="profpic" src="<?php echo $profileImage; ?>" alt="Profile Picture" draggable="false">
                <button class="button-login"><a href="profile.php#"><?php echo htmlspecialchars($result['username']); ?></a></button>
                <a href="logout.php" style="color: white;"><i class="fa-solid fa-right-from-bracket"></i></a>
            </ul>
        </div>
    </nav>
</body>
</html>
