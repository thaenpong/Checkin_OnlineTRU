<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ระบบเช็คชื่อออนไลน์</title>

  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <script src="bootstrap/js/bootstrap.min.js"></script>


  <style>
    /* Reset and basic styles */
    html,
    body {
      height: 100%;
      margin: 0;
      font-family: Arial, sans-serif;
    }

    .container {
      margin-top: 4rem;
    }

    .text-center {
      text-align: center;
    }

    /* Styling for cards */
    .card {
      width: 18rem;
      height: 90%;
    }

    .card img {
      width: 60%;
    }

    .card-body {
      background-position: center center;
      border-top-left-radius: 5px;
      border-top-right-radius: 5px;
      cursor: pointer;
    }

    .card-footer {
      text-align: center;
    }

    /* Background images for cards */
    .card-1 .card-body {
      background-image: url('images/buildingCover.jpg');
    }

    .card-2 .card-body {
      background-image: url('images/teacherCover.jpg');
    }

    .card-3 .card-body {
      background-image: url('images/studentCover.jpg');
    }

    .long-card {
      height: 80vh;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="text-center">
      <h1>ระบบเช็คชื่อออนไลน์</h1>
      <h1>มหาวิทยาลัยธนบุรี</h1>
    </div>

    <div class="container text-center">
      <div class="row" style="height: 80vh;">
        <div class="col">
          <div class="d-flex justify-content-center align-items-center long-card">
            <div class="card card-1" onclick="redirect('user/')">
              <div class="card-body">
                <img src="images/logo.png" class="card-img-top" alt="Registration">
              </div>
              <div class="card-footer">
                <h5 class="card-title">สำหรับฝ่ายทะเบียน</h5>
                <a href="user/" class="btn btn-success">เข้าสู่ระบบ</a>
              </div>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="d-flex justify-content-center align-items-center long-card">
            <div class="card card-2" onclick="redirect('user/')">
              <div class="card-body">
                <img src="images/logo.png" class="card-img-top" alt="Teacher">
              </div>
              <div class="card-footer">
                <h5 class="card-title">สำหรับอาจารย์ที่ปรึกษา</h5>
                <a href="user/" class="btn btn-warning">เข้าสู่ระบบ</a>
              </div>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="d-flex justify-content-center align-items-center long-card">
            <div class="card card-3" onclick="redirect('student/')">
              <div class="card-body">
                <img src="images/logo.png" class="card-img-top" alt="Student">
              </div>
              <div class="card-footer">
                <h5 class="card-title">สำหรับนักศึกษา</h5>
                <a href="student/" class="btn btn-primary">เข้าสู่ระบบ</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <script>
    function redirect(url) {
      location.href = url;
    }
  </script>
</body>



</html>