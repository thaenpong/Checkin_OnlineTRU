<?php
include("connect.php");
session_start();

if (isset($_SESSION['user']['id'])) {
    header("Location: /Checkin_OnlineTRU/user/main.php");
}

if (isset($_POST['submit'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql = "SELECT users.*,
        teachers.name AS teacherName,
        teachers.id AS teahcerId,
        teachers.is_active AS isActive
        FROM users 
        INNER JOIN teachers ON users.teacher = teachers.id 
        WHERE users.username = '$username' 
        AND users.password = '$password'
        AND teachers.is_active = 1";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 0) {
        echo "<script>window.location.href = '/Checkin_OnlineTRU/user/?error=notfound';</script>";
    } else {
        $data = mysqli_fetch_assoc($result);
        $_SESSION['user']["id"] = $data['id'];
        $_SESSION['user']['teacherId'] = $data["teahcerId"];
        $_SESSION['user']['username'] = $data['username'];
        $_SESSION['user']['name'] = $data['teacherName'];
        $_SESSION['user']['role'] = $data['role'];

        echo "<script>window.location.href = '/Checkin_OnlineTRU/user/main.php';</script>";
    }
}
