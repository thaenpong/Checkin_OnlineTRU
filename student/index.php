<?php
session_start();

if (isset($_SESSION['student']['id'])) {
    header("Location: /Checkin_OnlineTRU/student/main.php");
}

$callback = isset($_GET["callback"]);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>

    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            height: 100vh;
            overflow: hidden;
            justify-content: center;
            align-items: center;
            display: flex;
        }

        .main {
            width: 50%;
            min-width: 380px;
            background: rebeccapurple;
            padding: 20px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.3);
        }

        .title {
            text-align: center;
            margin-bottom: 20px;
        }

        .error {
            color: red;
            text-align: center;
        }
    </style>
    <link rel="stylesheet" href="css/gobalstyle.css">
</head>

<body>
    <div class="main">
        <div class="text-center mb-4">

            <img src="../images/logo.png" alt="logo">

        </div>
        <form action="functions/login.php" method="post">
            <div class="title">
                <h2>เข้าสู่ระบบ นักศึกษา</h2>
            </div>
            <div class="mb-2">
                <label for="exampleFormControlInput1" class="form-label">รหัสนักศึกษา</label>
                <input type="text" name="code" class="form-control">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">รหัสบัตรประชาชน</label>
                <input type="password" name="id_card" class="form-control">
            </div>
            <?php
            if (isset($_GET["error"])) {
                echo "<div class='error mb-2'>ไม่พบชื่อผู้ใช้ในระบบ</div>";
            }
            ?>
            <input type="hidden" name="callback" value="<?= isset($_GET["callback"]) ? $_GET["callback"] : "/Checkin_OnlineTRU/student/main.php" ?>">
            <div class="mb-3">
                <center>
                    <button type="submit" name="submit" class="btn btn-primary btn-lg">เข้าสู่ระบบ</button>
                </center>
            </div>
            <div class="mb-3">
                <center>
                    <a href="../" class="btn btn-warning btn-lg">ย้อนกลับ</a>
                </center>
            </div>
        </form>
    </div>

</body>

</html>