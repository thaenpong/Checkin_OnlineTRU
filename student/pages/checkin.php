<?php
$id = $_GET["id"];
$student_id = $_SESSION['student']["id"];
$student_year = $_SESSION['student']["yearId"];

// Check the SQL query structure and variable handling
$sql = "SELECT sessions.*,
        subjects.name AS subjectName,
        subjects.id AS subjectId,
        years.name AS yearName, 
        years.id AS yearId
        FROM sessions 
        INNER JOIN subjects ON subjects.id = sessions.subject_id
        INNER JOIN years ON years.id = sessions.year_id
        WHERE sessions.id = '$id'";

$qr = mysqli_query($conn, $sql);
$rs = mysqli_fetch_assoc($qr);


if ($rs) {

    $sql_register = "SELECT * FROM register WHERE student_id = $student_id AND subject_id = " . $rs["subjectId"];
    $qr_register = mysqli_query($conn, $sql_register);

    // Check if there's a registration record
    if ($qr_register && mysqli_num_rows($qr_register) > 0) {
        $is_register = true;
    } else {
        $is_register = false;
    }


    $sql_check_session = "SELECT end_time, year_id FROM sessions WHERE id = '$id' AND end_time <= NOW()";
    $result_check_session = mysqli_query($conn, $sql_check_session);

    if ($result_check_session && mysqli_num_rows($result_check_session) > 0) {
        $is_ended = true; // Session is ended and year matches student's year
    } else {
        $is_ended = false; // Session is not ended
    }


    $sql_check_year = "SELECT * FROM sessions WHERE id = '$id' AND year_id != '$student_year'";
    $result_check_year = mysqli_query($conn, $sql_check_year);
    $incorrect_year = mysqli_num_rows($result_check_year) > 0;

    // Query to check if there's a check-in record
    $sql_checkin = "SELECT * FROM checkin WHERE student_id = $student_id AND sessions_id = $id";
    $qr_checkin = mysqli_query($conn, $sql_checkin);

    // Check if there's a check-in record
    if ($qr_checkin && mysqli_num_rows($qr_checkin) > 0) {
        $rs_checkin = mysqli_fetch_assoc($qr_checkin);
        // Format the check-in time
        $formatted_checkin_time = (new DateTime($rs_checkin["created_at"]))->format('d-m-Y H:i');
    } else {
        $rs_checkin = null; // Set $rs_checkin to null if no record found
        $formatted_checkin_time = null; // Set formatted time to null
    }

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>เช็คเวลาเข้าเรียน</title>
        <!-- Include necessary CSS and JavaScript libraries here -->
        <link rel="stylesheet" href="path/to/bootstrap.min.css">
        <script src="path/to/jquery.min.js"></script>
        <script src="path/to/sweetalert2.min.js"></script>
        <link rel="stylesheet" href="path/to/sweetalert2.min.css">
    </head>

    <div class="my-4">
        <h1 class="text-center">เช็คเวลาเข้าเรียน</h1>
    </div>
    <div class="card mb-3 p-2">
        <div class="my-3">
            <h4 class="text-center my-3"><?= $rs["subjectName"] ?></h4>
            <h4 class="text-center my-3">วันที่ : <?= (new DateTime($rs["created_date"]))->format('d-m-Y') ?></h4>
            <h4 class="text-center my-3">สำหรับ นักศึกษาปีการศึกษา : <?= $rs["yearName"] ?></h4>
        </div>
        <div class="my-3 text-center">
            <?php if ($formatted_checkin_time) : ?>
                <h4 class="text-center">เช็คชื่อแล้ว เวลา <?= $formatted_checkin_time ?></h4>
            <?php elseif ($is_register == false) : ?>
                <h4 class="text-center">คุณไม่ได้ลงทะเบียนเรียนวิชานี้</h4>
            <?php elseif ($incorrect_year) : ?>
                <h4 class="text-center">ปีการศึกษาไม่ถูกต้อง</h4>
                <h4>ปีการศึกษาของคุณคือ <?= $_SESSION["student"]["yearName"]?></h4>
            <?php elseif ($is_ended == true) : ?>
                <h4 class="text-center">ไม่สามารถเช็คชื่อได้ เนื่องจากเวลาเรียนได้สิ้นสุดแล้ว</h4>
            <?php else : ?>
                <button class="btn btn-success" id="checkin" data-session-id="<?= $id ?>" data-student-id="<?= $student_id ?>">เช็คชื่อ</button>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('#checkin').addEventListener('click', async function() {
                const sessionId = this.getAttribute('data-session-id');
                const studentId = this.getAttribute('data-student-id');

                const result = await Swal.fire({
                    title: 'คุณแน่ใจหรือไม่?',
                    text: 'ต้องการบันทึกข้อมูลนี้?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ใช่, บันทึกเลย!',
                    cancelButtonText: 'ยกเลิก'
                });

                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "./functions/checkin.php",
                        data: {
                            session_id: sessionId,
                            student_id: studentId
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.success) {
                                Swal.fire(
                                    'บันทึกสำเร็จ!',
                                    'ข้อมูลของคุณถูกบันทึกแล้ว.',
                                    'success'
                                ).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'เกิดข้อผิดพลาด!',
                                    response.message || 'ไม่สามารถบันทึกข้อมูลได้.',
                                    'error'
                                );
                            }
                        },
                        error: function() {
                            Swal.fire(
                                'เกิดข้อผิดพลาด!',
                                'ไม่สามารถเพิ่มข้อมูลได้.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    </script>

    </html>
<?php
}
?>