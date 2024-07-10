<?php
session_start();

// Redirect logged-in users to the index page
if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

require 'config.php';

$step = 1; // Step 1: Verify user details, Step 2: Reset password

if (isset($_POST["verify"])) {
    $email = $_POST["email"];
    $username = $_POST["username"];

    // Check if the provided details are correct
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND username = ?");
    if ($stmt) {
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($result) {
            $_SESSION["reset_user"] = $username;
            $_SESSION["reset_email"] = $email;
            $step = 2;
        } else {
            echo "<script>alert('Data tidak ditemukan. Coba lagi!');</script>";
        }
    } else {
        echo "<script>alert('Terjadi error pada database: " . $conn->error . "');</script>";
    }
}

if (isset($_POST["reset"])) {
    if (isset($_SESSION["reset_user"])) {
        $newPassword = $_POST["password"];
        $confirmPassword = $_POST["confirm_password"];

        if ($newPassword === $confirmPassword && strlen($newPassword) >= 8) {
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $username = $_SESSION["reset_user"];

            // Update the password in the database
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
            if ($stmt) {
                $stmt->bind_param("ss", $hashedPassword, $username);
                if ($stmt->execute()) {
                    unset($_SESSION["reset_user"]);
                    unset($_SESSION["reset_email"]);
                    echo "<script>alert('Password berhasil direset!'); window.location.href = 'login.php';</script>";
                } else {
                    echo "<script>alert('Gagal mereset password: " . $stmt->error . "');</script>";
                }
                $stmt->close();
            } else {
                echo "<script>alert('Terjadi error pada database: " . $conn->error . "');</script>";
            }
        } else {
            echo "<script>alert('Password konfirmasi tidak cocok atau password terlalu pendek (minimal 8 karakter!)');</script>";
        }
    } else {
        echo "<script>alert('Sesi telah berakhir. Coba lagi!');</script>";
        $step = 1;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>9AM - Reset Password</title>
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
            <?php if ($step == 1): ?>
                <form action="forgetpass.php" method="post">
                    <h1 class="title">Reset Password</h1>
                    <div class="input-div one">
                        <div class="div">
                            <h4>Email</h4>
                            <input type="text" class="input" name="email" placeholder="Masukkan Email" required>
                        </div>
                    </div>
                    <div class="input-div one">
                        <div class="div">
                            <h4>Username</h4>
                            <input type="text" class="input" name="username" placeholder="Masukkan Username" required>
                        </div>
                    </div>
                    <input type="submit" class="btn submit" name="verify" value="Verify">
                    <p>Kembali ke <a href="login.php">Login</a></p>
                </form>
            <?php elseif ($step == 2): ?>
                <form action="forgetpass.php" method="post">
                    <h1 class="title">Reset Password</h1>
                    <div class="input-div one">
                        <div class="div">
                            <h4>New Password</h4>
                            <input type="password" class="input" name="password" placeholder="Masukkan Password Baru" required>
                        </div>
                    </div>
                    <div class="input-div pass">
                        <div class="div">
                            <h4>Confirm Password</h4>
                            <input type="password" class="input" name="confirm_password" placeholder="Masukkan Ulang Password" required>
                        </div>
                    </div>
                    <input type="submit" class="btn submit" name="reset" value="Reset Password">
                </form>
            <?php endif; ?>
        </div>
    </div>
    <script type="text/javascript" src="js/main.js"></script>
</body>
</html>
