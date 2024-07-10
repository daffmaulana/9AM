<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>9AM - Daftar</title>
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
	<!-- <img class="wave" src="img/wave.png"> -->
	<div class="containerloreg">
        <div class="logoloreg">
                    <a href="index.php"><img src="img/9AM-white.svg" alt=""></a>
        </div>
        <div class="login-content">
                <?php
                include("config.php");
                if(isset($_POST['submit'])){
                        $username = $_POST['username'];
                        $email = $_POST['email'];
                        $password = $_POST['password'];
                        $konfirmasi = $_POST['konfirmasi'];
                        
                        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                        $errors = array();

                        if (empty($username) OR empty($email)OR empty($password)OR empty($konfirmasi)){
                                array_push($errors,"Semua kolom wajib diisi!");
                        }
                        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                                array_push($errors, "Email tidak valid!");
                        }
                        if (strlen($password)<8) {
                        array_push($errors,"Password minimal 8 karakter!");
                        }
                        if ($password!==$konfirmasi) {
                        array_push($errors,"Password tidak cocok!");}

                        require_once "config.php";
                        $sql = "SELECT * FROM users WHERE email = '$email'";
                        $result = mysqli_query($conn, $sql);
                        $rowCount = mysqli_num_rows($result);
                        if ($rowCount>0) {
                         array_push($errors,"Email telah digunakan, coba lagi!");
                        }

                        if (count($errors) > 0) {
                                echo "<div class='alert-wrapper'>";
                                foreach ($errors as $error) {
                                    echo "<div class='alert alertfailed'>$error</div>";
                                }
                                echo "</div>";
                                } else {
                                $sql = "INSERT INTO users (username, email, password) VALUES ( ?, ?, ? )";
                                $stmt = mysqli_stmt_init($conn);
                                $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
                                if ($prepareStmt) {
                                        mysqli_stmt_bind_param($stmt,"sss",$username, $email, $passwordHash);
                                        mysqli_stmt_execute($stmt);
                                        echo "<div class='alert alertsuccess'>Kamu berhasil membuat akun!</div>";
                                        echo "<div class='alert alertsuccess'>Redirect ke halaman login...</div>";
                                        header("refresh:3;url=login.php");
                                        } else{
                                                die("Terjadi error, coba lagi!");
                                        }
                                }
                }
                ?>
            <form action="daftar.php" method="post">
                <!-- <img src="img/avatar.svg"> -->
                <h1 class="title">Daftar</h2>   
                <div class="input-div user">
                <div class="div">
                        <h4>Username</h4>
                        <input type="text" class="input" name="username" maxlength="16" placeholder="Masukkan Username">
                </div>
                </div>
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
                <div class="input-div pass">
                <div class="div">
                        <h4>Konfirmasi Password</h4>
                        <input type="password" class="input" name="konfirmasi" placeholder="Masukkan Ulang Password">
                </div>
                </div>
                <input type="submit" class="btn submit" value="Daftar" name="submit">
                <p>Sudah Punya Akun? <a href="login.php ">Login</a></p>
            </form>
        </div>
    </div>
</body>
</html>