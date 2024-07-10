<?php
// Include the database connection file
// $conn = mysqli_connect("localhost", "root", "", "cv_builder");
require 'config.php';

// Include the DOMPDF library
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

// Check if the "Generate PDF" button was clicked
if (isset($_POST['generate_pdf'])) {
    // Redirect to generate_pdf.php
    header("Location: generate_pdf.php");
    exit();
} else {
    // Get form data
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];
    $email_address = $_POST['email_address'];
    $linkedin_profile_url = $_POST['linkedin_profile_url'];
    $work_experience = $_POST['work_experience'];
    $organization_experience = $_POST['organization_experience'];
    $education = $_POST['education'];
    $skill = $_POST['skill'];
    $certificates = $_POST['certificates'];
    $reference = $_POST['reference'];
    $describe_urself = $_POST['describe_urself'];

    // SQL query to insert data into the database
    $sql = "INSERT INTO cv_data (name, address, phone_number, email_address, linkedin_profile_url, work_experience, organization_experience, education, skill, certificates, reference, describe_urself)
    VALUES ('$name', '$address', '$phone_number', '$email_address', '$linkedin_profile_url', '$work_experience', '$organization_experience', '$education', '$skill', '$certificates', '$reference', '$describe_urself')";

    if ($conn->query($sql) === TRUE) {
        // Redirect to view_cv.php
        header("Location: view_cv.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>