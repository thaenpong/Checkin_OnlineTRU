<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<div>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="./">TRU Online</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <?php
                if ($_SESSION["user"]["role"] == '2') {
                ?>
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                        <li class="nav-item">
                            <a class="nav-link " aria-current="page" href="./main.php?page=list-students">รายชื่อนักศึกษา</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " aria-current="page" href="./main.php?page=add-session">สร้าง Qr Code</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " aria-current="page" href="./main.php?page=list-checkins">รายชื่อเข้าเรียนนักศึกษา</a>
                        </li>
                    </ul>
                <?php
                } else if ($_SESSION["user"]["role"] == '1') {
                ?>
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link " aria-current="page" href="./main.php?page=list-teachers">รายชื่ออาจารย์ </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " aria-current="page" href="./main.php?page=list-subjects">รายชื่อวิชา</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " aria-current="page" href="./main.php?page=list-students">รายชื่อนักศึกษา</a>
                        <li class="nav-item">
                            <a class="nav-link " aria-current="page" href="./main.php?page=add-register">ลงทะเบียนวิชาเรียน</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " aria-current="page" href="./main.php?page=list-checkins">รายชื่อเข้าเรียนนักศึกษา</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                การจัดการ
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="./main.php?page=list-years">ปีการศึกษา</a></li>
                                <li><a class="dropdown-item" href="./main.php?page=list-branchs">สาขา</a></li>
                            </ul>
                        </li>
                    </ul>
                <?php
                }
                ?>
                <span>
                    <span><?= $_SESSION['user']['name'] ?></span>
                    <a class="btn btn-danger ml-2" aria-current="page" href="./functions/logout.php">ออกจากระบบ</a>
                </span>
            </div>
        </div>
    </nav>
</div>

</html>