<?php
session_start();
if (isset($_SESSION["user"])) {
   if ($_SESSION["is_admin"]) {
       header("Location: admin-dashboard.php");
   } else {
       header("Location: index.php");
   }
   exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>9AM - Login</title>
    <link rel="icon" href="img/9AM.svg" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        *{
    text-decoration: none;
}

html{
    scroll-behavior: smooth;
    scroll-padding-top: 200px;
    height: 100%;
}

body{
    font-family: 'Open Sans', sans-serif;
    margin: 0px;
    width: 100%;
    height: 100%;
    background-color: white;
    text-decoration: none;
    display: flex;
    flex-direction: column;
}
.containerloreg{
    display: flex;
    height: 100%;
}

.logoloreg{
    background-color: #FF8A08;
    display: flex;

    width: 30%;
    height: 100%;
    justify-content: center;
    align-items: center;
}
.logoloreg img {
    max-width: 90%;
    height: auto;
  }

.login-content{
    display: flex;
    flex-direction: column;
    width: 70%;
    align-items: center;
    justify-content:center;

}

.login-content h1, h4{
    padding: 0%;
    margin: 0px 0px;
}

.input-div{
    margin: 20px 0px;
}

form{
    width: 50%;
}

.input-div input{
    box-sizing: border-box;
    background-color: #F0F0F0;
    width: 100%;
    padding: 0px 0px 0px 10px;
    border: none;
    height: 40px;
    border-radius: 10px;
    outline: none;
}
.submit{
    width: 100%;
    background-color: #FF8A08;
    border-radius: 20px;
    height: 40px;
    border: none;
    color: white;
    font-size: 20px;
    font-weight: bold;
    margin: 8px 0px;
}

form a{
    font-size: 12px;
    width: 100%;
    color: #FF8A08;
    text-align: end;
}

form p{
    font-size: 12px;
    margin: 0%;
}

h4{
    margin: 4px 0px;
}

.alert{
    width: 50%;
    font-size: 14px;
    margin: 5px 0px;
    height: 40px;
    color: white;
    display: flex;
    align-items: center;
    border-radius: 10px;
    padding: 0px 10px;
}

.alertsuccess{
    background-color: green;
}
.alertfailed{
    background-color: #990f0f;
}

.alert-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
}
    </style>
</head>
<body> 
    <div class="containerloreg">
        <div class="logoloreg">
            <a href="index.php"><img src="img/9AM-white.svg" alt=""></a>
        </div>
        <div class="login-content">
        <?php
        if (isset($_POST["login"])) {
            $email = $_POST["email"];
            $password = $_POST["password"];
            require_once "config.php";

            // Prepare and execute the query securely
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user) {
                if (password_verify($password, $user["password"])) {
                    $_SESSION["email"] = $user["email"];
                    $_SESSION["user"] = $user["username"]; 
                    $_SESSION["user_id"] = $user["user_id"];
                    $_SESSION["is_admin"] = $user["is_admin"];
                    
                    if ($user["is_admin"]) {
                        header("Location: admin-dashboard.php");
                    } else {
                        header("Location: index.php");
                    }
                    exit();
                } else {
                    echo "<div class='alert alertfailed'>Password salah, coba lagi!</div>";
                }
            } else {
                echo "<div class='alert alertfailed'>Email tidak ditemukan!</div>";
            }
            
            // Close the statement
            $stmt->close();
        }
        ?>
            <form action="login.php" method="post">
                <h1 class="title">Masuk</h1>
                <div class="input-div one">
                    <div class="div">
                        <h4>Email</h4>
                        <input type="text" class="input" name="email" placeholder="Masukkan Email">
                    </div>
                </div>
                <div class="input-div pass">
                    <div class="div">
                        <h4>Password</h4>
                        <input type="password" class="input" name="password" placeholder="Masukkan Password">
                    </div>
                </div>
                <a href="forgetpass.php">Lupa Password?</a>
                <input type="submit" class="btn submit" name="login" value="Masuk">
                <p>Belum Punya Akun? <a href="daftar.php">Daftar</a></p>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="js/main.js"></script>
</body>
</html>
