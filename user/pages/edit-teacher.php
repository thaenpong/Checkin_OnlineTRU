<?php
$id = intval($_GET["id"]);
// Fetch teacher and related data
function fetch_teacher_user_data($conn, $id)
{
    $sql = "SELECT teachers.id, teachers.name, teachers.code, teacher_role.name AS teacherRole_name, teacher_role.id AS teacherRole_id 
            FROM teachers 
            LEFT JOIN teacher_role ON teachers.role = teacher_role.id 
            WHERE teachers.id = $id";
    $qr = mysqli_query($conn, $sql);
    $rs = mysqli_fetch_assoc($qr);

    $sql_user = "SELECT users.*, user_role.id AS userRole_id 
                 FROM users 
                 INNER JOIN user_role ON users.role = user_role.id 
                 WHERE users.teacher = $id";
    $qr_user = mysqli_query($conn, $sql_user);
    $rs_user = mysqli_fetch_assoc($qr_user);

    return [$rs, $rs_user];
}

$sql_role = "SELECT * FROM teacher_role";
$qr_role = mysqli_query($conn, $sql_role);

$sql_userRole = "SELECT * FROM user_role";
$qr_UserRole = mysqli_query($conn, $sql_userRole);


list($rs, $rs_user) = fetch_teacher_user_data($conn, $id);
?>

<html>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">

                <div class="card-body">
                    <div class="card-title">
                        <h2>แก้ไขข้อมูลอาจารย์</h2>
                    </div>
                    <form action="./functions/teachers.php" method="post" id="editTeacherForm">
                        <div class="mb-3">
                            <div class="form-group mb-2">
                                <label for="code">รหัส</label>
                                <input type="text" class="form-control" id="code" name="code" value="<?= $rs["code"] ?>">
                            </div>
                            <div class="form-group mb-2">
                                <label for="name">ชื่อ - นามสกุล</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?= $rs["name"] ?>">
                            </div>
                            <div class="form-group mb-2">
                                <label for="role">ตำแหน่ง</label>
                                <select class="form-control" id="role" name="role">
                                    <?php while ($row = mysqli_fetch_assoc($qr_role)) { ?>
                                        <option value="<?= $row["id"] ?>" <?= $rs["teacherRole_id"] == $row["id"] ? 'selected' : '' ?>>
                                            <?= $row["name"] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-group mb-2">
                                <label for="username">ชื่อผู้ใช้</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?= $rs_user["username"] ?>">
                            </div>
                            <div class="form-group mb-2">
                                <label for="password">รหัสผ่าน</label>
                                <input type="password" class="form-control" id="password" name="password" value="<?= $rs_user["password"] ?>">
                            </div>
                            <div class="form-group mb-2">
                                <label for="userRole">หน้าที่</label>
                                <select class="form-control" id="userRole" name="userRole">
                                    <?php while ($row = mysqli_fetch_assoc($qr_UserRole)) { ?>
                                        <option value="<?= $row["id"] ?>" <?= $rs_user["userRole_id"] == $row["id"] ? 'selected' : '' ?>>
                                            <?= $row["name"] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?= $rs["id"] ?>">
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-primary" id="saveButton">บันทึก</button>
                            <button type="button" class="btn btn-danger" id="deleteButton" value="<?= $rs['id']; ?>" <?php if ($rs["id"] == $_SESSION['user']['teacherId']) echo 'disabled'; ?>>
                                ลบข้อมูล
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.getElementById('deleteButton').addEventListener('click', async function() {
        const id = this.value;

        // Show the SweetAlert confirmation dialog
        const result = await Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: "ข้อมูลนี้จะถูกลบและไม่สามารถกู้คืนได้!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่, ลบเลย!',
            cancelButtonText: 'ยกเลิก'
        });

        if (result.isConfirmed) {
            try {
                // If confirmed, send the DELETE request
                const response = await fetch('./functions/teachers.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `teacher_id=${id}`
                });

                if (response.status == 200) {
                    Swal.fire(
                        'ลบข้อมูลเรียบร้อย!',
                        'ข้อมูลของคุณถูกลบแล้ว.',
                        'success'
                    ).then(() => {
                        window.location.replace('/Checkin_OnlineTRU/user/main.php?page=list-teachers');
                    });
                } else {
                    Swal.fire(
                        'เกิดข้อผิดพลาด!',
                        data.error || 'ไม่สามารถลบข้อมูลได้.',
                        'error'
                    );
                }
            } catch (error) {
                console.error('Fetch error:', error);
                Swal.fire(
                    'เกิดข้อผิดพลาด!',
                    'ไม่สามารถลบข้อมูลได้.',
                    'error'
                );
            }
        }
    });

    document.getElementById('saveButton').addEventListener('click', async function() {
        const form = document.getElementById('editTeacherForm');
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
                type: "PATCH",
                url: "./functions/teachers.php",
                contentType: "application/x-www-form-urlencoded",
                data: formDataSerialized, // Send serialized form data
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        Swal.fire(
                            'บันทึกสำเร็จ!',
                            'ข้อมูลของคุณถูกบันทึกแล้ว.',
                            'success'
                        ).then(() => {
                            window.location.replace('/Checkin_OnlineTRU/user/main.php?page=list-teachers');
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
                        'ไม่สามารถแก้ไขข้อมูลได้.',
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