<?php

$sql_year = "SELECT * FROM years WHERE is_active = 1 ORDER BY id DESC";
$qr_year = mysqli_query($conn, $sql_year);

$sql_subject = "SELECT * FROM subjects WHERE is_active = 1 ORDER BY id DESC";
$qr_subject = mysqli_query($conn, $sql_subject);

?>

<html>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center">
                <h2>สร้าง Qr Code เช็คชื่อ</h2>
            </div>
            <form action="./functions/teachers.php" method="post" id="addSession">
                <div class="my-4">
                    <div class="form-group mb-2">
                        <label for="role">ชื่อวิชา</label>
                        <select class="form-control" id="subject" name="subject">
                            <?php
                            while ($row = mysqli_fetch_assoc($qr_subject)) {
                            ?>
                                <option value="<?= $row["id"] ?>"><?= $row["name"] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="role">ปีการศึกษา ของนักศึกษา</label>
                        <select class="form-control" id="year" name="year">
                            <?php
                            while ($row = mysqli_fetch_assoc($qr_year)) {
                            ?>
                                <option value="<?= $row["id"] ?>"><?= $row["name"] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <input type="hidden" value="<?= $_SESSION['user']['teacherId'] ?>" name="teacher" id="teacher">
                </div>
                <div class="text-center my-4">
                    <div class="mb-2">
                        <button type="button" class="btn btn-primary" id="saveButton">บันทึก</button>
                    </div>
                    <div class="mb-2">
                        <a class="btn btn-danger deleteButton" href="./main.php?page=list-students">ยกเลิก</a>
                    </div>

                </div>

            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('saveButton').addEventListener('click', async function() {
        const form = document.getElementById('addSession');
        const formData = new FormData(form);

        // Show the SweetAlert confirmation dialog
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
            // Convert FormData to URL-encoded string
            const formDataSerialized = $(form).serialize();

            $.ajax({
                type: "POST",
                url: "./functions/session.php",
                contentType: "application/x-www-form-urlencoded",
                data: formDataSerialized, // Send serialized form data
                success: function(response) {
                    if (response.success) {
                        const id = response.id;
                        Swal.fire(
                            'บันทึกสำเร็จ!',
                            'ข้อมูลของคุณถูกบันทึกแล้ว.',
                            'success'
                        ).then(() => {
                            window.location.replace(`/Checkin_OnlineTRU/user/main.php?page=view-session&id=${id}`);
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
</script>


</html>

<?php
mysqli_close($conn);
?>