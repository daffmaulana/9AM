<?php
session_start();
define('INCLUDE_CHECK', true);

if (isset($_SESSION["email"])) {
    include "navbar-in.php";
} else {
    header("Location: login.php");
}
// Include the database connection file
require 'config.php';

// SQL query to retrieve the latest data from the database
$sql = "SELECT * FROM cv_data ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <title>9AM - CV Builder</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CV View</title>
        <style>
            .container {
                width: 50%;
                margin: 40px auto;
                padding: 40px;
                background-color: #ffffff;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
            }
            .header {
                text-align: center;
                margin-bottom: 30px;
            }
            .header h1 {
                margin-bottom: 10px;
            }
            .header p {
                color: #FF8A08;
            }
            .section {
                margin-bottom: 30px;
                padding-bottom: 20px;
                border-bottom: 1px solid #ecf0f1;
            }
            .section:last-child {
                border-bottom: none;
            }
            .section-title {
                font-weight: bold;
                font-size: 1.2em;
                margin-bottom: 15px;
            }
            .details {
                line-height: 1.6;
            }
            a {
                color: #FF8A08;
                text-decoration: none;
            }
            a:hover {
                text-decoration: underline;
            }
            .generate-pdf {
                display: inline-block;
                padding: 12px 24px;
                background-color: #FF8A08;
                color: white;
                text-decoration: none;
                border-radius: 4px;
                transition: background-color 0.3s ease;
            }
            .generate-pdf:hover {
                background-color: #c76c08;
                text-decoration: none;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1><?php echo $row['name']; ?></h1>
                <p><?php echo $row['phone_number']; ?> | <?php echo $row['email_address']; ?> | <a href="<?php echo $row['linkedin_profile_url']; ?>">LinkedIn</a></p>
            </div>
            <div class="section">
                <div class="section-title">About Me</div>
                <div class="details"><?php echo nl2br($row['describe_urself']); ?></div>
            </div>
            <div class="section">
                <div class="section-title">Education</div>
                <div class="details"><?php echo nl2br($row['education']); ?></div>
            </div>
            <div class="section">
                <div class="section-title">Organization Experiences</div>
                <div class="details"><?php echo nl2br($row['organization_experience']); ?></div>
            </div>
            <div class="section">
                <div class="section-title">Work Experiences</div>
                <div class="details"><?php echo nl2br($row['work_experience']); ?></div>
            </div>
            <div class="section">
                <div class="section-title">Certifications</div>
                <div class="details"><?php echo nl2br($row['certificates']); ?></div>
            </div>
            <div class="section">
                <div class="section-title">Skills</div>
                <div class="details"><?php echo nl2br($row['skill']); ?></div>
            </div>
            <div class="section">
                <div class="section-title">References</div>
                <div class="details"><?php echo nl2br($row['reference']); ?></div>
            </div>
            <div style="text-align: center;">
                <a href="generate_pdf.php" class="generate-pdf" target="_blank">Download PDF</a>
            </div>
        </div>
    <?php
    include "footer.php";
    ?>
    </body>
    </html>
    <?php
} else {
    echo "No CV data found.";
}

// Close the database connection
$conn->close();
?>