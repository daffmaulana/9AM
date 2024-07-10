<?php
session_start();
define('INCLUDE_CHECK', true);
if (!isset($_SESSION["email"])) {
   header("Location: login.php");
   exit();
}
require 'config.php';
$email = $_SESSION["email"];
?>

<?php
require_once 'dompdf\autoload.inc.php';
use Dompdf\Dompdf;

// SQL query to retrieve the latest data from the database
$sql = "SELECT * FROM cv_data ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Create a new DOMPDF instance
    $dompdf = new Dompdf();

    // HTML content for the PDF
    $html = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>CV - ' . $row['name'] . '</title>
        <style>
            body {
                font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
                margin: 0;
                padding: 20px;
                background-color: #ffffff;
                color: #333;
            }
            .container {
                max-width: 800px;
                margin: 0 auto;
            }
            .header {
                text-align: center;
                margin-bottom: 20px;
            }
            .header h1 {
                color: #2c3e50;
                margin-bottom: 5px;
            }
            .header p {
                color: #7f8c8d;
                margin: 0;
            }
            .section {
                margin-bottom: 20px;
                padding-bottom: 15px;
                border-bottom: 1px solid #ecf0f1;
            }
            .section:last-child {
                border-bottom: none;
            }
            .section-title {
                font-weight: bold;
                font-size: 14px;
                color: #2980b9;
                margin-bottom: 10px;
            }
            .details {
                line-height: 1.4;
            }
            a {
                color: #3498db;
                text-decoration: none;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>' . $row['name'] . '</h1>
                <p>' . '<a href="https://wa.me/' . $row['phone_number'] . '">' . $row['phone_number'] . '</a>' . ' | ' . '<a href="mailto:' . $row['email_address'] . '">' . $row['email_address'] . '</a>' . ' | <a href="' . $row['linkedin_profile_url'] . '">LinkedIn</a></p>
            </div>
            <hr>
            <div class="section">
                <div class="section-title">About Me</div>
                <div class="details">' . nl2br($row['describe_urself']) . '</div>
            </div>
            <hr>
            <div class="section">
                <div class="section-title">Education</div>
                <div class="details">' . nl2br($row['education']) . '</div>
            </div>
            <hr>
            <div class="section">
                <div class="section-title">Organization Experiences</div>
                <div class="details">' . nl2br($row['organization_experience']) . '</div>
            </div>
            <hr>
            <div class="section">
                <div class="section-title">Work Experiences</div>
                <div class="details">' . nl2br($row['work_experience']) . '</div>
            </div>
            <hr>
            <div class="section">
                <div class="section-title">Certifications</div>
                <div class="details">' . nl2br($row['certificates']) . '</div>
            </div>
            <hr>
            <div class="section">
                <div class="section-title">Skills</div>
                <div class="details">' . nl2br($row['skill']) . '</div>
            </div>
            <hr>
            <div class="section">
                <div class="section-title">References</div>
                <div class="details">' . nl2br($row['reference']) . '</div>
            </div>
        </div>
    </body>
    </html>
    ';

    // Load the HTML content into DOMPDF
    $dompdf->loadHtml($html);

    // Set paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();

    // Output the generated PDF
    $dompdf->stream('cv_' . $row['name'] . '.pdf', ['Attachment' => false]);
} else {
    echo "No CV data found.";
}

// Close the database connection
$conn->close();
?>