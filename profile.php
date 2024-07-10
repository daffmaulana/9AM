<?php
session_start();
define('INCLUDE_CHECK', true);

if (isset($_SESSION["email"])) {
    include "navbar-in.php";
} else {
    header("Location: login.php");
}

require 'config.php';
$email = $_SESSION["email"];
$user = $_SESSION["user"];

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

$id = $result['user_id']; // Get the user ID from the database result

// Update profile if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["simpan"])) {
    $newUsername = $_POST["username"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["konfirmasi"];
    $imageName = $_FILES["image"]["name"];
    $imageSize = $_FILES["image"]["size"];
    $tmpName = $_FILES["image"]["tmp_name"];
    $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
    $validImageExtensions = ['jpg', 'jpeg', 'png'];

    // Validate password match and length
    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match');</script>";
    } elseif (strlen($password) < 8) {
        echo "<script>alert('Password must be at least 8 characters long');</script>";
    } else {
        if ($imageName) {
            if (!in_array($imageExtension, $validImageExtensions)) {
                echo "<script>alert('Invalid Image Extension');</script>";
            } elseif ($imageSize > 3000000) {
                echo "<script>alert('Image Size Is Large');</script>";
            } else {
                $newImageName = 'user' . $id . "." . $imageExtension;
                move_uploaded_file($tmpName, "img/user/" . $newImageName);

                $sql = "UPDATE users SET image=? WHERE email=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $newImageName, $email);
                $stmt->execute();
            }
        }

        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET username=?, password=? WHERE email=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $newUsername, $hashedPassword, $email);
        } else {
            $sql = "UPDATE users SET username=? WHERE email=?";
            $stmt->bind_param("ss", $newUsername, $email);
        }

        if ($stmt->execute()) {
            $_SESSION["user"] = $newUsername;
            echo "<script>alert('Profile updated successfully'); window.location.href = 'profile.php';</script>";
        } else {
            echo "<script>alert('Error updating profile');</script>";
        }
    }
} elseif (isset($_POST["delete"])) {
    // Remove account if the delete button is pressed
    $sql = "DELETE FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    if ($stmt->execute()) {
        session_destroy();
        echo "<script>alert('Account deleted successfully'); window.location.href = 'login.php';</script>";
    } else {
        echo "<script>alert('Error deleting account');</script>";
    }
}

$profileImage = $result['image'] ? 'img/user/' . $result['image'] : 'img/user/default-profile.jpg'; // Set default image if none exists
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>9AM - Profile</title>
    <link rel="icon" href="9AM.svg" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        
        .container {
            display: flex;
            flex: 1;
            justify-content: center;
            align-items: stretch;
            padding: 20px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }
        
        .pp, .profile {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            margin: 10px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .pp{
            display: flex;
            justify-content: center;
        }
        
        .ppwrapper {
            text-align: center;
        }
        
        .ppwrapper .circle {
            border-radius: 50%;
            border: #FF8A08 solid 10px;
            width: 200px;
            height: 200px;
            object-fit: cover;
            margin-bottom: 20px;
        }
        
        .pp h2 {
            margin: 10px 0;
        }
        
        .profile h4 {
            margin: 10px 0;
        }
        
        .input {
            box-sizing: border-box;
            background-color: #F0F0F0;
            width: 100%;
            padding: 10px;
            border: none;
            height: 40px;
            border-radius: 10px;
            outline: none;
            margin-bottom: 10px;
        }
        
        .submit, .remove {
            width: 100%;
            border-radius: 20px;
            height: 50px;
            border: none;
            color: white;
            font-size: 20px;
            font-weight: bold;
            margin-top: 10px;
        }
        
        .submit {
            background-color: #FF8A08;
        }
        
        .remove {
            background-color: #990f0f;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="pp">
            <div class="ppwrapper">
                <img class="circle" src="<?php echo $profileImage; ?>" width="90" alt="Profile Picture" draggable="false">
                <h2><?php echo htmlspecialchars($result['username']); ?></h2>
                <h2><?php echo htmlspecialchars($result['email']); ?></h2>
            </div>
        </div>
        <div class="profile">
            <form action="#" enctype="multipart/form-data" method="post">
                <h1 class="title">Edit Profile</h1>
                <h4>Profile Picture</h4>
                <input type="hidden" name="id" id="user_id">
                <input type="file" class="input" name="image" accept=".jpg, .jpeg, .png">
                <h4>Username</h4>
                <input type="text" class="input" name="username" placeholder="Masukkan Username" value="<?php echo htmlspecialchars($result['username']); ?>" maxlength="16">
                <h4>Password</h4>
                <input type="password" class="input" name="password" placeholder="Masukkan Password">
                <h4>Konfirmasi Password</h4>
                <input type="password" class="input" name="konfirmasi" placeholder="Masukkan Ulang Password">
                <input type="submit" class="btn submit" name="simpan" value="Simpan">
                <input type="submit" class="btn remove" name="delete" value="Hapus Akun">
            </form>
        </div>
    </div>
<?php
include "footer.php";
?>
</body>
</html>
