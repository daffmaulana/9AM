<?php
session_start();
define('INCLUDE_CHECK', true);

if (isset($_SESSION["email"])) {
    include "navbar-in.php";
} else {
    include "navbar.php";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>9AM - Privacy Policy</title>
    <link rel="icon" href="9AM.svg" type="image/x-icon">
    <link rel="stylesheet" href="home.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .container{
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .privacy-policy {
            padding: 2em;
            background-color: #f0f0f0;
        }

        .privacy-policy .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1em;
        }

        .privacy-policy h1 {
            font-size: 2em;
            margin-bottom: 0.5em;
            color: #FF8A08;
        }

        .privacy-policy p {
            font-size: 1em;
            color: #555;
            margin-bottom: 1em;
            line-height: 1.5;
        }

        .privacy-policy h2 {
            font-size: 1.5em;
            margin-top: 1em;
            color: #333;
        }

        .privacy-policy ul {
            margin-bottom: 1em;
            color: #555;
        }
        a:visited, a:link{
            color: #FF8A08;
        }
    </style>
</head>
<body>
    <!-- PRIVACY POLICY SECTION -->
    <section class="privacy-policy">
        <div class="container">
        <h1>Privacy Policy</h1>

        <p>At 9AM, accessible from 9am, one of our main priorities is the privacy of our visitors. This Privacy Policy document contains types of information that is collected and recorded by 9AM and how we use it.</p>

        <p>If you have additional questions or require more information about our Privacy Policy, do not hesitate to contact us.</p>

        <h2>Log Files</h2>

        <p>9AM follows a standard procedure of using log files. These files log visitors when they visit websites. All hosting companies do this and a part of hosting services' analytics. The information collected by log files include internet protocol (IP) addresses, browser type, Internet Service Provider (ISP), date and time stamp, referring/exit pages, and possibly the number of clicks. These are not linked to any information that is personally identifiable. The purpose of the information is for analyzing trends, administering the site, tracking users' movement on the website, and gathering demographic information. Our Privacy Policy was created with the help of the <a href="https://www.privacypolicyonline.com/privacy-policy-generator/">Privacy Policy Generator</a>.</p>

        <h2>Privacy Policies</h2>

        <P>You may consult this list to find the Privacy Policy for each of the advertising partners of 9AM.</p>

        <p>Third-party ad servers or ad networks uses technologies like cookies, JavaScript, or Web Beacons that are used in their respective advertisements and links that appear on 9AM, which are sent directly to users' browser. They automatically receive your IP address when this occurs. These technologies are used to measure the effectiveness of their advertising campaigns and/or to personalize the advertising content that you see on websites that you visit.</p>

        <p>Note that 9AM has no access to or control over these cookies that are used by third-party advertisers.</p>

        <h2>Third Party Privacy Policies</h2>

        <p>9AM's Privacy Policy does not apply to other advertisers or websites. Thus, we are advising you to consult the respective Privacy Policies of these third-party ad servers for more detailed information. It may include their practices and instructions about how to opt-out of certain options. </p>

        <p>You can choose to disable cookies through your individual browser options. To know more detailed information about cookie management with specific web browsers, it can be found at the browsers' respective websites. What Are Cookies?</p>

        <h2>Children's Information</h2>

        <p>Another part of our priority is adding protection for children while using the internet. We encourage parents and guardians to observe, participate in, and/or monitor and guide their online activity.</p>

        <p>9AM does not knowingly collect any Personal Identifiable Information from children under the age of 13. If you think that your child provided this kind of information on our website, we strongly encourage you to contact us immediately and we will do our best efforts to promptly remove such information from our records.</p>

        <h2>Online Privacy Policy Only</h2>

        <p>This Privacy Policy applies only to our online activities and is valid for visitors to our website with regards to the information that they shared and/or collect in 9AM. This policy is not applicable to any information collected offline or via channels other than this website.</p>

        <h2>Consent</h2>

        <p>By using our website, you hereby consent to our  <a href="privacypolicy#.php">Privacy Policy</a> and agree to its <a href="termsconditions.php">Terms Conditions</a></p>
        </div>
    </section>
    <!-- FOOTER -->
    <?php
    include "footer.php";
    ?>
</body>
</html>
