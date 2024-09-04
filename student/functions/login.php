<?php
session_start();
include("connect.php");

if (isset($_POST['submit'])) {

    $code = $_POST['code'];
    $id_card = $_POST['id_card'];
    $callback_url = isset($_POST['callback']) != null ? urldecode($_POST['callback']) : '/Checkin_OnlineTRU/student/main.php'; // Default redirect URL

    $sql = "SELECT 
            students.*, 
            branchs.name AS branchName, 
            branchs.id AS branchId, 
            years.id AS yearId, 
            years.name AS yearName
        FROM 
            students
        INNER JOIN 
            branchs ON students.branch_id = branchs.id
        INNER JOIN 
            years ON students.year_id = years.id
        WHERE 
            students.code = '$code' 
            AND students.id_card = '$id_card'
            AND students.is_active = 1";
    
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
        // User not found, redirect back to login with error
        header("Location: /Checkin_OnlineTRU/student/?error=notfound");
        exit;
    } else {
        // User found, set session variables and redirect to callback URL or default
        $data = mysqli_fetch_assoc($result);
        $_SESSION['student']["id"] = $data['id'];
        $_SESSION['student']['code'] = $data["code"];
        $_SESSION['student']['id_card'] = $data['id_card'];
        $_SESSION['student']['name'] = $data['name'];
        $_SESSION['student']['branchId'] = $data['branchId'];
        $_SESSION['student']['branchName'] = $data['branchName'];
        $_SESSION['student']['yearId'] = $data['yearId'];
        $_SESSION['student']['yearName'] = $data['yearName'];

        header("Location: $callback_url");
        exit;
    }
}
?>
