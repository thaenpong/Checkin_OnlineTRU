<?php
$id = $_GET["id"];
$subject = $_GET["subject"];
$year = $_GET["year"];

$sql = "SELECT students.*,
            years.name AS yearName,
            branchs.name AS branchName
            FROM students 
            INNER JOIN branchs ON branchs.id = students.branch_id
            INNER JOIN years ON years.id = students.year_id
            WHERE students.id = '$id'";

$qr = mysqli_query($conn, $sql);
$rs = mysqli_fetch_assoc($qr);

$sql_session = "SELECT *
                FROM sessions
                WHERE sessions.year_id = '$year'
                AND sessions.subject_id = '$subject'
                ORDER BY created_date ASC";
$qr_session = mysqli_query($conn, $sql_session);

$sql_subject = "SELECT *
                FROM subjects
                WHERE id = '$subject'";
$qr_subject = mysqli_query($conn, $sql_subject);
$rs_subject = mysqli_fetch_assoc($qr_subject);

?>
<html>
<div class="my-4">
    <h1>แก้ไขการเข้าเรียนนักศึกษา</h1>
</div>
<div class="my-2">
    <h3>วิชา : <?= $rs_subject["name"] ?></h3>
</div>
<div class="my-3">
    <div class="card p-2">
        <p>รหัสนักศึกษา : <?= $rs["code"] ?></p>
        <p>นักศึกษา : <?= $rs["name"] ?></p>
        <p>สาขา : <?= $rs["branchName"] ?></p>
        <p>ปีการศึกษา : <?= $rs["yearName"] ?></p>
    </div>
</div>
<div class="my-3">
    <div class="card p-2">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">วีค</th>
                    <th scope="col">วันที่</th>
                    <th scope="col">เวลา</th>
                    <th scope="col">เช็คชื่อ</th>
                    <th scope="col">แก้ไข</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $num = 1;
                while ($row = mysqli_fetch_assoc($qr_session)) {

                    // Convert the created_date to a DateTime object
                    $created_date = new DateTime($row["created_date"]);
                    // Format the date to dd-mm-yyyy
                    $formatted_date = $created_date->format('d-m-Y');


                    // Convert the created_time to a DateTime object
                    $created_time = new DateTime($row["created_time"]);
                    // Format the time to hh:mm
                    $formatted_time = $created_time->format('H:i');

                    $sessionId = $row["id"];
                    $sql_checkin = "SELECT * FROM checkin WHERE sessions_id = '$sessionId' AND student_id = '$id'";
                    $qr_checkin = mysqli_query($conn, $sql_checkin);
                    $rs_checkin = mysqli_fetch_assoc($qr_checkin);

                    $formatted_time_checkin = "";
                    if ($rs_checkin) {
                        $created_time = new DateTime($rs_checkin["created_at"]);
                        $formatted_time_checkin = $created_time->format('d-m-y H:i');
                    }

                ?>
                    <tr>
                        <th scope="row"><?= $num ?></th>
                        <td><?= $formatted_date ?></td>
                        <td><?= $formatted_time ?></td>
                        <td><?= isset($rs_checkin["id"]) ? $formatted_time_checkin : '⛌' ?></td>
                        <td><?= isset($rs_checkin["id"]) ? '<button class="btn btn-warning" id="cancel" data-checkin-id="' . $rs_checkin["id"] . '">ยกเลิก</button>' : '<button class="btn btn-success" id="checkin" data-session-id="' . $sessionId . '" data-student-id="' . $id . '">เช็คชื่อ</button>' ?></td>
                    </tr>
                <?php
                    $num += 1;
                } ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('#checkin').forEach(button => {
            button.addEventListener('click', async function() {
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


        // Event listener for the cancel button
        document.querySelectorAll('#cancel').forEach(button => {
            button.addEventListener('click', async function() {
                const checkinId = this.getAttribute('data-checkin-id'); // Assuming this is the check-in ID

                const result = await Swal.fire({
                    title: 'คุณแน่ใจหรือไม่?',
                    text: 'ต้องการยกเลิกการเช็คชื่อ?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ใช่, ยกเลิกเลย!',
                    cancelButtonText: 'กลับ'
                });

                if (result.isConfirmed) {
                    try {
                        // If confirmed, send the DELETE request
                        const response = await fetch('./functions/checkin.php', {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `id=${checkinId}`
                        });

                        if (response.status == 200) {
                            Swal.fire(
                                'ยกเลิกสำเร็จ!',
                                'การเช็คชื่อถูกยกเลิกแล้ว.',
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire(
                                'เกิดข้อผิดพลาด!',
                                response.message || 'ไม่สามารถยกเลิกได้.',
                                'error'
                            );
                        }
                    } catch (error) {
                        Swal.fire(
                            'เกิดข้อผิดพลาด!',
                            'ไม่สามารถยกเลิกได้.',
                            'error'
                        );
                    }
                }
            });
        });
    });
</script>


</html>