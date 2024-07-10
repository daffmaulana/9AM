<?php
session_start();
if (!isset($_SESSION["email"]) || !$_SESSION["is_admin"]) {
    header("Location: login.php");
    exit();
}

require 'config.php';

// Fetch data to display on the admin dashboard
$sqlUsers = "SELECT * FROM users";
$resultUsers = $conn->query($sqlUsers);
$sqlTestimonials = "SELECT * FROM testimonials";
$resultTestimonials = $conn->query($sqlTestimonials);
$sqlCarousel = "SELECT * FROM carousel_images";
$resultCarousel = $conn->query($sqlCarousel);
$sqlNews = "SELECT * FROM data_berita";
$resultNews = $conn->query($sqlNews);
$sqlJobs = "SELECT * FROM jobs";
$resultJobs = $conn->query($sqlJobs);
$sqlCVs = "SELECT * FROM cv_data";
$resultCVs = $conn->query($sqlCVs);

// Function to validate email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to generate unique image name
function generateImageName($section, $id, $extension) {
    return $section . $id . "." . $extension;}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["add_user"])) {
        $email = $_POST["email"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $is_admin = isset($_POST["is_admin"]) ? 1 : 0;
        $image = $_FILES["image"]["name"];

        if (isValidEmail($email)) {
            if (!empty($password) && strlen($password) >= 8) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Check if email already exists
                $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                    echo "Email already exists!";
                } else {
                    $stmt->close();
                    // Insert user without the image first
                    $stmt = $conn->prepare("INSERT INTO users (email, username, password, is_admin) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("sssi", $email, $username, $hashedPassword, $is_admin);
                    $stmt->execute();
                    $user_id = $stmt->insert_id;
                    $stmt->close();

                    // Handle the image
                    if ($image) {
                        $imageExtension = pathinfo($image, PATHINFO_EXTENSION);
                        $imageName = generateImageName('user', $user_id, $imageExtension);
                        move_uploaded_file($_FILES["image"]["tmp_name"], "img/user/" . $imageName);
                    } else {
                        $imageName = "default-profile.jpg";
                    }

                    // Update user with the image name
                    $stmt = $conn->prepare("UPDATE users SET image = ? WHERE user_id = ?");
                    $stmt->bind_param("si", $imageName, $user_id);
                    $stmt->execute();
                    $stmt->close();

                    header("Location: admin-dashboard.php");
                }
            } 
        }
    } elseif (isset($_POST["edit_user"])) {
        $id = $_POST["id"];
        $email = $_POST["email"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $is_admin = isset($_POST["is_admin"]) ? 1 : 0;
        $image = $_FILES["image"]["name"];

        if (isValidEmail($email)) {
            $sql = "UPDATE users SET email = ?, username = ?, is_admin = ?";
            $params = [$email, $username, $is_admin];
            $types = "ssi";

            if (!empty($password)) {
                if (strlen($password) >= 8) {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $sql .= ", password = ?";
                    $params[] = $hashedPassword;
                    $types .= "s";
                }
            }

            if (!empty($image)) {
                $imageExtension = pathinfo($image, PATHINFO_EXTENSION);
                $imageName = generateImageName('user', $id, $imageExtension);
                move_uploaded_file($_FILES["image"]["tmp_name"], "img/user/" . $imageName);
                $sql .= ", image = ?";
                $params[] = $imageName;
                $types .= "s";
            }

            $sql .= " WHERE user_id = ?";
            $params[] = $id;
            $types .= "i";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $stmt->close();
            header("Location: admin-dashboard.php");
        }
    } elseif (isset($_POST["delete_user"])) {
        $id = $_POST["id"];

        $stmt = $conn->prepare("DELETE FROM users WHERE user_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        header("Location: admin-dashboard.php");
    }
}

// Handle add testimonial
if (isset($_POST["add_testimonial"])) {
    $name = $_POST["name"];
    $role = $_POST["role"];
    $content = $_POST["content"];
    $image = $_FILES["image"]["name"];
    $content ='“' . $content . '”';
    // Insert the testimonial to get the ID
    $stmt = $conn->prepare("INSERT INTO testimonials (name, role, content, image) VALUES (?, ?, ?, ?)");
    $imageName = "default-profile.jpg"; // Default image
    $stmt->bind_param("ssss", $name, $role, $content, $imageName);
    $stmt->execute();
    $testimonial_id = $stmt->insert_id;
    $stmt->close();

    if ($image) {
        $imageExtension = pathinfo($image, PATHINFO_EXTENSION);
        $imageName = generateImageName('testi', $testimonial_id, $imageExtension);
        move_uploaded_file($_FILES["image"]["tmp_name"], "img/testi/" . $imageName);

        // Update the testimonial with the correct image name
        $stmt = $conn->prepare("UPDATE testimonials SET image=? WHERE id=?");
        $stmt->bind_param("si", $imageName, $testimonial_id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: admin-dashboard.php");
} elseif (isset($_POST["edit_testimonial"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $role = $_POST["role"];
    $content = $_POST["content"];
    $image = $_FILES["image"]["name"];
    $content = '“' . $content . '”';
    if (!empty($image)) {
        $imageExtension = pathinfo($image, PATHINFO_EXTENSION);
            $imageName = generateImageName('testi', $id, $imageExtension);
            move_uploaded_file($_FILES["image"]["tmp_name"], "img/testi/" . $imageName);
        $stmt = $conn->prepare("UPDATE testimonials SET name = ?, role = ?, content = ?, image = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $name, $role, $content, $imageName, $id);
    } else {
        $stmt = $conn->prepare("UPDATE testimonials SET name = ?, role = ?, content = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $role, $content, $id);
    }

    $stmt->execute();
    $stmt->close();
    header("Location: admin-dashboard.php");

} elseif (isset($_POST["delete_testimonial"])) {
    $id = $_POST["id"];

    $stmt = $conn->prepare("DELETE FROM testimonials WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin-dashboard.php");
    exit();
}


//handle carousel
if (isset($_POST["add_carousel"])) {
    $altText = $_POST["alt_text"];
    $image = $_FILES["image"]["name"];

    // Insert to get the new ID
    $stmt = $conn->prepare("INSERT INTO carousel_images (image, alt_text) VALUES ('', ?)");
    $stmt->bind_param("s", $altText);
    $stmt->execute();
    $carousel_id = $stmt->insert_id;

    if ($image) {
        $imageExtension = pathinfo($image, PATHINFO_EXTENSION);
            $imageName = generateImageName('carousel', $carousel_id, $imageExtension);
            move_uploaded_file($_FILES["image"]["tmp_name"], "img/carousel/" . $imageName);

        // Update with the correct image name
        $stmt = $conn->prepare("UPDATE carousel_images SET image=? WHERE id=?");
        $stmt->bind_param("si", $imageName, $carousel_id);
        $stmt->execute();
    }
    $stmt->close();
    header("Location: admin-dashboard.php");
} elseif (isset($_POST["edit_carousel"])) {
    $id = $_POST["id"];
    $altText = $_POST["alt_text"];
    $image = $_FILES["image"]["name"];

    if (!empty($image)) {
        $imageExtension = pathinfo($image, PATHINFO_EXTENSION);
        $imageName = generateImageName('carousel', $id, $imageExtension);
        move_uploaded_file($_FILES["image"]["tmp_name"], "img/carousel/" . $imageName);
        $stmt = $conn->prepare("UPDATE carousel_images SET image = ?, alt_text = ? WHERE id = ?");
        $stmt->bind_param("ssi", $imageName, $altText, $id);
    } else {
        $stmt = $conn->prepare("UPDATE carousel_images SET alt_text = ? WHERE id = ?");
        $stmt->bind_param("si", $altText, $id);
    }

    $stmt->execute();
    $stmt->close();
    header("Location: admin-dashboard.php");
} elseif (isset($_POST["delete_carousel"])) {
    $id = $_POST["id"];

    $stmt = $conn->prepare("DELETE FROM carousel_images WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin-dashboard.php");
}

// handle berita
if (isset($_POST["add_news"])) {
    $title = $_POST["judul_berita"];
    $content = $_POST["isi_berita"];
    $uploadDate = $_POST["tanggal_upload"];
    $thumbnail = $_FILES["thumbnail"]["name"];
    $thumbnailName = "default-thumbnail.jpg";

    // Insert news data to get the new ID
    $stmt = $conn->prepare("INSERT INTO data_berita (judul_berita, isi_berita, tanggal_upload, thumbnail) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $content, $uploadDate, $thumbnailName);
    $stmt->execute();
    $news_id = $stmt->insert_id;
    $stmt->close();

    if ($thumbnail) {
        $thumbnailExtension = pathinfo($thumbnail, PATHINFO_EXTENSION);
        $thumbnailName = generateImageName('news', $news_id, $thumbnailExtension);
        move_uploaded_file($_FILES["thumbnail"]["tmp_name"], "img/blog/" . $thumbnailName);

        // Update news with the correct image name
        $stmt = $conn->prepare("UPDATE data_berita SET thumbnail=? WHERE id=?");
        $stmt->bind_param("si", $thumbnailName, $news_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: admin-dashboard.php");
}

// Handle editing news
elseif (isset($_POST["edit_news"])) {
    $id = $_POST["id"];
    $title = $_POST["judul_berita"];
    $content = $_POST["isi_berita"];
    $uploadDate = $_POST["tanggal_upload"];
    $thumbnail = $_FILES["thumbnail"]["name"];

    $sql = "UPDATE data_berita SET judul_berita = ?, isi_berita = ?, tanggal_upload = ?";
    $params = [$title, $content, $uploadDate];
    $types = "sss";

    if (!empty($thumbnail)) {
        $thumbnailExtension = pathinfo($thumbnail, PATHINFO_EXTENSION);
        $thumbnailName = generateImageName('news', $id, $thumbnailExtension);
        move_uploaded_file($_FILES["thumbnail"]["tmp_name"], "img/blog/" . $thumbnailName);
        $sql .= ", thumbnail = ?";
        $params[] = $thumbnailName;
        $types .= "s";
    }

    $sql .= " WHERE id = ?";
    $params[] = $id;
    $types .= "i";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $stmt->close();
    header("Location: admin-dashboard.php");
} 

// Handle deleting news
elseif (isset($_POST["delete_news"])) {
    $id = $_POST["id"];

    $stmt = $conn->prepare("DELETE FROM data_berita WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin-dashboard.php");
}

if (isset($_POST["add_job"])) {
    $title = $_POST["job_title"];
    $company = $_POST["company"];
    $location = $_POST["location"];
    $salary = $_POST["salary"];
    $workType = $_POST["work_type"];
    $workTime = $_POST["work_time"];
    $level = $_POST["level"];
    $aboutUs = $_POST["about_us"];
    $opport = $_POST["opport"];
    $responsibilities = $_POST["responsibilities"];
    $essential = $_POST["essential"];
    $url = $_POST["url"];
    $image = $_FILES["image_url"]["name"];

    if ($image) {
        $imageExtension = pathinfo($image, PATHINFO_EXTENSION);
            $imageName = generateImageName('job', $id, $imageExtension);
            move_uploaded_file($_FILES["image_url"]["tmp_name"], "img/loker/" . $imageName);
    } else {
        $imageName = "default-job.jpg";
    }

    $stmt = $conn->prepare("INSERT INTO jobs (job_title, company, location, salary, work_type, work_time, level, image_url, about_us, opport, responsibilities, essential, url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssssss", $title, $company, $location, $salary, $workType, $workTime, $level, $imageName, $aboutUs, $opport, $responsibilities, $essential, $url);
    $stmt->execute();
    $stmt->close();
    header("Location: admin-dashboard.php");
} 

// Handle editing job
elseif (isset($_POST["edit_job"])) {
    $id = $_POST["id"];
    $title = $_POST["job_title"];
    $company = $_POST["company"];
    $location = $_POST["location"];
    $salary = $_POST["salary"];
    $workType = $_POST["work_type"];
    $workTime = $_POST["work_time"];
    $level = $_POST["level"];
    $aboutUs = $_POST["about_us"];
    $opport = $_POST["opport"];
    $responsibilities = $_POST["responsibilities"];
    $essential = $_POST["essential"];
    $url = $_POST["url"];
    $image = $_FILES["image_url"]["name"];

    $sql = "UPDATE jobs SET job_title = ?, company = ?, location = ?, salary = ?, work_type = ?, work_time = ?, level = ?, about_us = ?, opport = ?, responsibilities = ?, essential = ?, url = ?";
    $params = [$title, $company, $location, $salary, $workType, $workTime, $level, $aboutUs, $opport, $responsibilities, $essential, $url];
    $types = "ssssssssssss";

    if (!empty($image)) {
        $imageExtension = pathinfo($image, PATHINFO_EXTENSION);
        $imageName = generateImageName('job', $id, $imageExtension);
        move_uploaded_file($_FILES["image_url"]["tmp_name"], "img/loker/" . $imageName);
        $sql .= ", image_url = ?";
        $params[] = $imageName;
        $types .= "s";
    }

    $sql .= " WHERE id = ?";
    $params[] = $id;
    $types .= "i";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $stmt->close();
    header("Location: admin-dashboard.php");
} 

// Handle deleting job
elseif (isset($_POST["delete_job"])) {
    $id = $_POST["id"];

    $stmt = $conn->prepare("DELETE FROM jobs WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin-dashboard.php");
}

// Hanle CV
if (isset($_POST["add_cv"])) {
    $name = $_POST["name"];
    $address = $_POST["address"];
    $phone_number = $_POST["phone_number"];
    $email_address = $_POST["email_address"];
    $linkedin_profile_url = $_POST["linkedin_profile_url"];
    $work_experience = $_POST["work_experience"];
    $organization_experience = $_POST["organization_experience"];
    $education = $_POST["education"];
    $skill = $_POST["skill"];
    $certificates = $_POST["certificates"];
    $reference = $_POST["reference"];
    $describe_urself = $_POST["describe_urself"];

    $stmt = $conn->prepare("INSERT INTO cv_data (name, address, phone_number, email_address, linkedin_profile_url, work_experience, organization_experience, education, skill, certificates, reference, describe_urself) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssss", $name, $address, $phone_number, $email_address, $linkedin_profile_url, $work_experience, $organization_experience, $education, $skill, $certificates, $reference, $describe_urself);
    $stmt->execute();
    $stmt->close();
    header("Location: admin-dashboard.php");
} elseif (isset($_POST["edit_cv"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $address = $_POST["address"];
    $phone_number = $_POST["phone_number"];
    $email_address = $_POST["email_address"];
    $linkedin_profile_url = $_POST["linkedin_profile_url"];
    $work_experience = $_POST["work_experience"];
    $organization_experience = $_POST["organization_experience"];
    $education = $_POST["education"];
    $skill = $_POST["skill"];
    $certificates = $_POST["certificates"];
    $reference = $_POST["reference"];
    $describe_urself = $_POST["describe_urself"];

    $stmt = $conn->prepare("UPDATE cv_data SET name = ?, address = ?, phone_number = ?, email_address = ?, linkedin_profile_url = ?, work_experience = ?, organization_experience = ?, education = ?, skill = ?, certificates = ?, reference = ?, describe_urself = ? WHERE id = ?");
    $stmt->bind_param("ssssssssssssi", $name, $address, $phone_number, $email_address, $linkedin_profile_url, $work_experience, $organization_experience, $education, $skill, $certificates, $reference, $describe_urself, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin-dashboard.php");
} elseif (isset($_POST["delete_cv"])) {
    $id = $_POST["id"];

    $stmt = $conn->prepare("DELETE FROM cv_data WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin-dashboard.php");
} elseif (isset($_POST["download_cv"])) {
    $id = $_POST["id"];
    header("Location: generate_pdf.php?id=" . $id);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>9AM - Admin Dashboard</title>
    <link rel="icon" href="img/9AM.svg" type="image/x-icon">
    <link rel="stylesheet" href="admin.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        /* General Styles */
body {
    font-family: 'Open Sans', sans-serif;
    margin: 0;
    background-color: #F0F0F0;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-decoration: none;
}

.navbar {
    position: sticky;
    top: 0;
    z-index: 2;
    background: #FF8A08;
    padding-right: 5%;
    padding-left: 5%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: white;
}

.navdiv {
    display: flex;
    justify-content: space-between;
    width: 100%;
}

.navlogo img {
    max-height: 50px;
}

.navbutton {
    display: flex;
    align-items: center;
}

.navbutton a {
    color: #FF8A08;
    text-decoration: none;
}

.button-logout, .button-back {
    margin-left: 10px;
    background-color: white;
    color: #FF8A08;
    border: none;
    padding: 0.5rem 1rem;
    cursor: pointer;
    border-radius: 3px;
    font-weight: bold;
}

.button-logout:hover, .button-back:hover {
    background-color: #F0F0F0;
}

.dashboard {
    width: 80%;
    margin-top: 2rem;
}

h1 {
    text-align: center;
    margin-bottom: 2rem;
}

.dashboard-content {
    background-color: white;
    padding: 2rem;
    border-radius: 10px;
    margin-bottom: 2rem;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    margin-top: 0;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1rem;
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 0.5rem;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

button, input[type="submit"], input[type="button"], input[type='date'] {
    border: none;
    padding: 0.5rem 1rem;
    cursor: pointer;
    border-radius: 3px;
    font-weight: bold;
}

button.edit, input[type="submit"].edit, input[type="button"].edit, input[type='date'].edit{
    background-color: #4CAF50;
    color: white;
}

button.delete, input[type="submit"].delete, input[type="button"].delete, input[type='date'].delete {
    background-color: #d9534f;
    color: white;
}

button.add, input[type="submit"].add, input[type="button"].add, input[type='date'].add {
    background-color: white;
    color: #FF8A08;
    border: 2px solid #FF8A08;
}

button.save, input[type="submit"].save, input[type="button"].save, input[type='date'].save {
    background-color: #4CAF50;
    color: white;
}

button.file, input[type="submit"].file, input[type="button"].file, input[type='date'].file {
    background-color: #FF8A08;
    color: white;
}

button.logout, input[type="submit"].logout, input[type="button"].logout, input[type='date'].logout {
    background-color: white;
    color: #FF8A08;
    border: 2px solid #FF8A08;
}

button:hover, input[type="submit"]:hover, input[type="button"]:hover, input[type='date']:hover {
    opacity: 0.8;
}

form {
    margin-top: 1rem;
}

form h3 {
    margin-top: 0;
}

label {
    display: block;
    margin: 0.5rem 0 0.2rem;
}

input[type="text"], input[type="password"], input[type="file"], textarea, input[type='date']{
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 3px;
    margin-bottom: 1rem;
}

input[type="checkbox"] {
    margin-right: 0.5rem;
}

img {
    border-radius: 3px;
    max-width: 100%;
    height: auto;
}

.dashboard-content img {
    max-width: 200px;
}

iframe {
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-top: 2rem;
    height: 480px;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    width: 99%; 
    
}

/* Responsive Styles */
@media (max-width: 768px) {
    .dashboard {
        width: 95%;
    }

    table, th, td {
        font-size: 0.9rem;
    }
}

    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navdiv">
            <div class="navlogo"><a href="#"><img draggable="false" src="9AM-white.svg" alt="Logo 9AM"></a></div>
            <ul class="navbutton">
                <button class="button-logout logout"><a href="logout.php">Logout</a></button>
                <button class="button-back logout"><a href="index.php#">Back to Dashboard</a></button>
            </ul>
        </div>
    </nav>

    <iframe src="index.php" ></iframe>

    <section class="dashboard">
        <h1>Admin Dashboard</h1>
        
        <!-- Users Section -->
        <div class="dashboard-content">
            <h2>Users</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Is Admin</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
                <?php while ($user = $resultUsers->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['is_admin'] ? 'Yes' : 'No'); ?></td>
                    <td><img src="img/user/<?php echo htmlspecialchars($user['image']); ?>" alt="<?php echo htmlspecialchars($user['username']); ?>" width="50"></td>
                    <td>
                        <button class="edit" onclick="editUser('<?php echo $user['user_id']; ?>', '<?php echo $user['email']; ?>', '<?php echo $user['username']; ?>', <?php echo $user['is_admin']; ?>, 'img/user/<?php echo $user['image']; ?>')">Edit</button>
                        <form action="admin-dashboard.php" method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $user['user_id']; ?>">
                            <input class="delete" type="submit" name="delete_user" value="Delete">
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </table>
            <form id="userForm" action="admin-dashboard.php" method="post" enctype="multipart/form-data">
                <h3>Add/Edit User</h3>
                <input type="hidden" name="id" id="user_id">
                <img id="user_image_preview">
                <label for="email">Email:</label>
                <input type="text" name="email" id="user_email" required>
                <label for="username">Username:</label>
                <input type="text" name="username" id="user_username" maxlength="16" required>
                <label for="password">Password:</label>
                <input type="password" name="password" id="user_password">
                <label for="is_admin">Is Admin:</label>
                <input type="checkbox" name="is_admin" id="user_is_admin">
                <label for="image">Profile Image:</label>
                <input class="file" type="file" name="image" id="user_image">
                <input class="add" type="submit" name="add_user" value="Add User">
                <input class="save" type="submit" name="edit_user" value="Save Changes">
            </form>
        </div>
<!-- Testimonials Section -->
<div class="dashboard-content">
    <h2>Testimonials</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Role</th>
            <th>Content</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        <?php while ($testimonial = $resultTestimonials->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($testimonial['id']); ?></td>
            <td><?php echo htmlspecialchars($testimonial['name']); ?></td>
            <td><?php echo htmlspecialchars($testimonial['role']); ?></td>
            <td><?php echo htmlspecialchars($testimonial['content']); ?></td>
            <td><img src="img/testi/<?php echo htmlspecialchars($testimonial['image']); ?>" alt="<?php echo htmlspecialchars($testimonial['name']); ?>" width="50"></td>
            <td>
                <button class="edit" onclick="editTestimonial('<?php echo $testimonial['id']; ?>', '<?php echo $testimonial['name']; ?>', '<?php echo $testimonial['role']; ?>', '<?php echo $testimonial['content']; ?>', 'img/testi/<?php echo $testimonial['image']; ?>')">Edit</button>
                <form action="admin-dashboard.php" method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $testimonial['id']; ?>">
                    <input class="delete" type="submit" name="delete_testimonial" value="Delete">
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
    <form id="testimonialForm" action="admin-dashboard.php" method="post" enctype="multipart/form-data">
        <h3>Add/Edit Testimonial</h3>
        <input type="hidden" name="id" id="testimonial_id">
        <img id="testimonial_image_preview">
        <label for="name">Name:</label>
        <input type="text" name="name" id="testimonial_name" maxlength="16" required>
        <label for="role">Role:</label>
        <input type="text" name="role" id="testimonial_role" maxlength="16" required>
        <label for="content">Content:</label>
        <textarea name="content" id="testimonial_content" required></textarea>
        <label for="image">Image:</label>
        <input class="file" type="file" name="image" id="testimonial_image">
        <input class="add" type="submit" name="add_testimonial" value="Add Testimonial">
        <input class="save" type="submit" name="edit_testimonial" value="Save Changes">
    </form>
</div>



<!-- Carousel Images Section -->
<div class="dashboard-content">
    <h2>Carousel Images</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Alt Text</th>
            <th>Preview</th>
            <th>Actions</th>
        </tr>
        <?php while ($carousel = $resultCarousel->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($carousel['id']); ?></td>
            <td><?php echo htmlspecialchars($carousel['image']); ?></td>
            <td><?php echo htmlspecialchars($carousel['alt_text']); ?></td>
            <td><img src="img/carousel/<?php echo htmlspecialchars($carousel['image']); ?>" alt="<?php echo htmlspecialchars($carousel['alt_text']); ?>" width="50"></td>
            <td>
                <button class="edit" onclick="editCarousel('<?php echo $carousel['id']; ?>', '<?php echo $carousel['alt_text']; ?>', 'img/carousel/<?php echo $carousel['image']; ?>')">Edit</button>
                <form action="admin-dashboard.php" method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $carousel['id']; ?>">
                    <input class="delete" type="submit" name="delete_carousel" value="Delete">
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
    <form id="carouselForm" action="admin-dashboard.php" method="post" enctype="multipart/form-data">
        <h3>Add/Edit Carousel Image</h3>
        <img id="carousel_image_preview">
        <input type="hidden" name="id" id="carousel_id">
        <label for="image">Image:</label>
        <input class="file" type="file" name="image" id="carousel_image">
        <label for="alt_text">Alt Text:</label>
        <input type="text" name="alt_text" id="carousel_alt_text" required>
        <input class="add" type="submit" name="add_carousel" value="Add Carousel Image">
        <input class="save" type="submit" name="edit_carousel" value="Save Changes">
    </form>
</div>

<!-- News Section -->
<div class="dashboard-content">
    <h2>News</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Content</th>
            <th>Upload Date</th>
            <th>Thumbnail</th>
            <th>Actions</th>
        </tr>
        <?php while ($news = $resultNews->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($news['id']); ?></td>
            <td><?php echo htmlspecialchars($news['judul_berita']); ?></td>
            <td><?php echo htmlspecialchars(substr($news['isi_berita'], 0, 100)) . '...'; ?></td>
            <td><?php echo htmlspecialchars($news['tanggal_upload']); ?></td>
            <td><img src="img/blog/<?php echo htmlspecialchars($news['thumbnail']); ?>" alt="<?php echo htmlspecialchars($news['judul_berita']); ?>" width="50"></td>
            <td>
                <button class="edit" onclick="editNews('<?php echo $news['id']; ?>', '<?php echo addslashes(htmlspecialchars($news['judul_berita'])); ?>', '<?php echo addslashes(htmlspecialchars($news['isi_berita'])); ?>', '<?php echo $news['tanggal_upload']; ?>', 'img/blog/<?php echo $news['thumbnail']; ?>')">Edit</button>
                <form action="admin-dashboard.php" method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $news['id']; ?>">
                    <input class="delete" type="submit" name="delete_news" value="Delete">
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
    <form id="newsForm" action="admin-dashboard.php" method="post" enctype="multipart/form-data">
        <h3>Add/Edit News</h3>
        <input type="hidden" name="id" id="news_id">
        <img id="news_thumbnail_preview">
        <label for="judul_berita">Title:</label>
        <input type="text" name="judul_berita" id="news_judul_berita" required>
        <label for="isi_berita">Content:</label>
        <textarea name="isi_berita" id="news_isi_berita" required></textarea>
        <label for="tanggal_upload">Upload Date:</label>
        <input type="date" name="tanggal_upload" id="news_tanggal_upload" required>
        <label for="thumbnail">Thumbnail:</label>
        <input class="file" type="file" name="thumbnail" id="news_thumbnail">
        <input class="add" type="submit" name="add_news" value="Add News">
        <input class="save" type="submit" name="edit_news" value="Save Changes">
    </form>
</div>

<!-- Jobs Section -->
<div class="dashboard-content">
    <h2>Job Postings</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Company</th>
            <th>Location</th>
            <th>Salary</th>
            <th>Work Type</th>
            <th>Work Time</th>
            <th>Level</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        <?php while ($job = $resultJobs->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($job['id']); ?></td>
            <td><?php echo htmlspecialchars($job['job_title']); ?></td>
            <td><?php echo htmlspecialchars($job['company']); ?></td>
            <td><?php echo htmlspecialchars($job['location']); ?></td>
            <td><?php echo htmlspecialchars($job['salary']); ?></td>
            <td><?php echo htmlspecialchars($job['work_type']); ?></td>
            <td><?php echo htmlspecialchars($job['work_time']); ?></td>
            <td><?php echo htmlspecialchars($job['level']); ?></td>
            <td><img src="img/loker/<?php echo htmlspecialchars($job['image_url']); ?>" alt="<?php echo htmlspecialchars($job['job_title']); ?>" width="50"></td>
            <td>
                <button class="edit" onclick="editJob('<?php echo $job['id']; ?>', '<?php echo htmlspecialchars(addslashes($job['job_title'])); ?>', '<?php echo htmlspecialchars(addslashes($job['company'])); ?>', '<?php echo htmlspecialchars(addslashes($job['location'])); ?>', '<?php echo htmlspecialchars(addslashes($job['salary'])); ?>', '<?php echo htmlspecialchars(addslashes($job['work_type'])); ?>', '<?php echo htmlspecialchars(addslashes($job['work_time'])); ?>', '<?php echo htmlspecialchars(addslashes($job['level'])); ?>', 'img/loker/<?php echo htmlspecialchars(addslashes($job['image_url'])); ?>', '<?php echo htmlspecialchars(addslashes($job['about_us'])); ?>', '<?php echo htmlspecialchars(addslashes($job['opport'])); ?>', '<?php echo htmlspecialchars(addslashes($job['responsibilities'])); ?>', '<?php echo htmlspecialchars(addslashes($job['essential'])); ?>', '<?php echo htmlspecialchars(addslashes($job['url'])); ?>')">Edit</button>
                <form action="admin-dashboard.php" method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $job['id']; ?>">
                    <input class="delete" type="submit" name="delete_job" value="Delete">
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
    <form id="jobForm" action="admin-dashboard.php" method="post" enctype="multipart/form-data">
        <h3>Add/Edit Job Posting</h3>
        <input type="hidden" name="id" id="job_id">
        <img id="job_image_preview">
        <label for="job_title">Job Title:</label>
        <input type="text" name="job_title" id="job_title" required>
        <label for="company">Company:</label>
        <input type="text" name="company" id="company" required>
        <label for="location">Location:</label>
        <input type="text" name="location" id="location" required>
        <label for="salary">Salary:</label>
        <input type="text" name="salary" id="salary" required>
        <label for="work_type">Work Type:</label>
        <input type="text" name="work_type" id="work_type" required>
        <label for="work_time">Work Time:</label>
        <input type="text" name="work_time" id="work_time" required>
        <label for="level">Level:</label>
        <input type="text" name="level" id="level" required>
        <label for="image_url">Image:</label>
        <input class="file" type="file" name="image_url" id="job_image">
        <label for="about_us">About Us:</label>
        <textarea name="about_us" id="about_us" required></textarea>
        <label for="opport">Opportunities:</label>
        <textarea name="opport" id="opport" required></textarea>
        <label for="responsibilities">Responsibilities:</label>
        <textarea name="responsibilities" id="responsibilities" required></textarea>
        <label for="essential">Essential:</label>
        <textarea name="essential" id="essential" required></textarea>
        <label for="url">URL:</label>
        <input type="text" name="url" id="url" required>
        <input class="add" type="submit" name="add_job" value="Add Job">
        <input class="save" type="submit" name="edit_job" value="Save Changes">
    </form>
</div>


<!-- CV Section -->
<div class="dashboard-content">
    <h2>CVs</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Address</th>
            <th>Phone Number</th>
            <th>Email Address</th>
            <th>LinkedIn Profile</th>
            <th>Actions</th>
        </tr>
        <?php while ($cv = $resultCVs->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($cv['id']); ?></td>
            <td><?php echo htmlspecialchars($cv['name']); ?></td>
            <td><?php echo htmlspecialchars($cv['address']); ?></td>
            <td><?php echo htmlspecialchars($cv['phone_number']); ?></td>
            <td><?php echo htmlspecialchars($cv['email_address']); ?></td>
            <td><a href="<?php echo htmlspecialchars($cv['linkedin_profile_url']); ?>" target="_blank"><?php echo htmlspecialchars($cv['linkedin_profile_url']); ?></a></td>
            <td>
                <button class="edit" onclick="editCV('<?php echo $cv['id']; ?>', '<?php echo addslashes(htmlspecialchars($cv['name'])); ?>', '<?php echo addslashes(htmlspecialchars($cv['address'])); ?>', '<?php echo $cv['phone_number']; ?>', '<?php echo $cv['email_address']; ?>', '<?php echo addslashes(htmlspecialchars($cv['linkedin_profile_url'])); ?>', '<?php echo addslashes(htmlspecialchars($cv['work_experience'])); ?>', '<?php echo addslashes(htmlspecialchars($cv['organization_experience'])); ?>', '<?php echo addslashes(htmlspecialchars($cv['education'])); ?>', '<?php echo addslashes(htmlspecialchars($cv['skill'])); ?>', '<?php echo addslashes(htmlspecialchars($cv['certificates'])); ?>', '<?php echo addslashes(htmlspecialchars($cv['reference'])); ?>', '<?php echo addslashes(htmlspecialchars($cv['describe_urself'])); ?>')">Edit</button>
                <form action="admin-dashboard.php" method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $cv['id']; ?>">
                    <input class="delete" type="submit" name="delete_cv" value="Delete">
                </form>
                <form action="admin-dashboard.php" method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $cv['id']; ?>">
                    <input class="file" type="submit" name="download_cv" value="Download PDF">
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
    <form id="cvForm" action="admin-dashboard.php" method="post" enctype="multipart/form-data">
        <h3>Add/Edit CV</h3>
        <input type="hidden" name="id" id="cv_id">
        <label for="name">Name:</label>
        <input type="text" name="name" id="cv_name" required>
        <label for="address">Address:</label>
        <input type="text" name="address" id="cv_address" required>
        <label for="phone_number">Phone Number:</label>
        <input type="tel" name="phone_number" id="cv_phone_number" required>
        <label for="email_address">Email Address:</label>
        <input type="email" name="email_address" id="cv_email_address" required>
        <label for="linkedin_profile_url">LinkedIn Profile URL:</label>
        <input type="url" name="linkedin_profile_url" id="cv_linkedin_profile_url" required>
        <label for="work_experience">Work Experience:</label>
        <textarea name="work_experience" id="cv_work_experience" required></textarea>
        <label for="organization_experience">Organization Experience:</label>
        <textarea name="organization_experience" id="cv_organization_experience" required></textarea>
        <label for="education">Education:</label>
        <textarea name="education" id="cv_education" required></textarea>
        <label for="skill">Skills:</label>
        <textarea name="skill" id="cv_skill" required></textarea>
        <label for="certificates">Certificates:</label>
        <textarea name="certificates" id="cv_certificates" required></textarea>
        <label for="reference">References:</label>
        <textarea name="reference" id="cv_reference" required></textarea>
        <label for="describe_urself">Describe Yourself:</label>
        <textarea name="describe_urself" id="cv_describe_urself" required></textarea>
        <input class="add" type="submit" name="add_cv" value="Add CV">
        <input class="save" type="submit" name="edit_cv" value="Save Changes">
    </form>
</div>

    <script>
        function editUser(id, email, username, is_admin, image) {
            document.getElementById('user_id').value = id;
            document.getElementById('user_email').value = email;
            document.getElementById('user_username').value = username;
            document.getElementById('user_is_admin').checked = is_admin;
            document.getElementById('user_image_preview').src = image;
        }

        function editTestimonial(id, name, role, content, image) {
            document.getElementById('testimonial_id').value = id;
            document.getElementById('testimonial_name').value = name;
            document.getElementById('testimonial_role').value = role;
            document.getElementById('testimonial_content').value = content;
            document.getElementById('testimonial_image_preview').src = image;
        }

        function editCarousel(id, alt_text, image) {
        document.getElementById('carousel_id').value = id;
        document.getElementById('carousel_image').value = '';
        document.getElementById('carousel_alt_text').value = alt_text;
        document.getElementById('carousel_image_preview').src = image;
        }   
        
        function editNews(id, title, content, uploadDate, thumbnail) {
        document.getElementById('news_id').value = id;
        document.getElementById('news_judul_berita').value = title;
        document.getElementById('news_isi_berita').value = content;
        document.getElementById('news_tanggal_upload').value = uploadDate;
        document.getElementById('news_thumbnail_preview').src = thumbnail;
        }
        
        
        function editJob(id, title, company, location, salary, workType, workTime, level, image, aboutUs, opport, responsibilities, essential, url) {
        document.getElementById('job_id').value = id;
        document.getElementById('job_title').value = title;
        document.getElementById('company').value = company;
        document.getElementById('location').value = location;
        document.getElementById('salary').value = salary;
        document.getElementById('work_type').value = workType;
        document.getElementById('work_time').value = workTime;
        document.getElementById('level').value = level;
        document.getElementById('job_image_preview').src = image;
        document.getElementById('about_us').value = aboutUs;
        document.getElementById('opport').value = opport;
        document.getElementById('responsibilities').value = responsibilities;
        document.getElementById('essential').value = essential;
        document.getElementById('url').value = url;
    }

    function editCV(id, name, address, phone_number, email_address, linkedin_profile_url, work_experience, organization_experience, education, skill, certificates, reference, describe_urself) {
        document.getElementById('cv_id').value = id;
        document.getElementById('cv_name').value = name;
        document.getElementById('cv_address').value = address;
        document.getElementById('cv_phone_number').value = phone_number;
        document.getElementById('cv_email_address').value = email_address;
        document.getElementById('cv_linkedin_profile_url').value = linkedin_profile_url;
        document.getElementById('cv_work_experience').value = work_experience;
        document.getElementById('cv_organization_experience').value = organization_experience;
        document.getElementById('cv_education').value = education;
        document.getElementById('cv_skill').value = skill;
        document.getElementById('cv_certificates').value = certificates;
        document.getElementById('cv_reference').value = reference;
        document.getElementById('cv_describe_urself').value = describe_urself;
    }
    </script>
</body>
</html>

