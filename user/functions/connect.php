<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

</html>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "checkin_online_tru";
$port = 3306;

// Create connection
try {
    $conn = mysqli_connect($servername, $username, $password, $database, $port);
} catch (\Throwable $th) {
    echo "<script>
           Swal.fire({
            icon: 'error',
            title: 'มีบางอย่างผิดพลาด?',
            confirmButtonText: 'ตกลง',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/nb_restaurant/';
            }
        });
          </script>";
}
?>