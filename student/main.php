<?php
session_start();
include("functions/connect.php");

if (!isset($_SESSION['student'])) {
    header("Location: /Checkin_OnlineTRU/student/?callback=" . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = "list-checkin"; // Set a default page for admins
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบเช็คชื่อออนไลน์</title>

    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/sweetalert.js"></script>
    <script src="../js/jquery.js"></script>
    <script src="../js/qrcode.min.js"></script>

</head>

<body>
    <div>
        <?php
        include('navbar.php');
        ?>
    </div>
    <div class="container">
        <?php
        include("pages/" . $page . ".php");
        ?>
    </div>

</body>

</html>