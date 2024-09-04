<?php
$id = $_GET["id"];


$sql = "SELECT students.*, 
branchs.name AS branchName,
branchs.id AS branchId,
years.name AS yearName,
years.id AS yearId
FROM students 
INNER JOIN branchs ON students.branch_id = branchs.id
INNER JOIN years ON students.year_id = years.id
WHERE students.id = $id";

$result = mysqli_query($conn, $sql);

$rs = mysqli_fetch_assoc($result);

$sql_year = "SELECT * FROM years WHERE is_active = 1 ORDER BY id DESC";
$qr_year = mysqli_query($conn, $sql_year);

$sql_branch = "SELECT * FROM branchs WHERE is_active = 1 ORDER BY id DESC";
$qr_branch = mysqli_query($conn, $sql_branch);

?>

<html>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2>แก้ไขข้อมูลนักศึกษา</h2>
            <form action="./functions/teachers.php" method="post" id="addStudentForm">
                <div class="my-4">
                    <div class="form-group mb-2">
                        <label for="code">รหัสนักศึกษา</label>
                        <input type="text" class="form-control" id="code" name="code" value="<?=$rs["code"]?>">
                    </div>
                    <div class="form-group mb-2">
                        <label for="name">รหัสบัตรประชาชน</label>
                        <input type="text" class="form-control" id="id_card" name="id_card" value="<?=$rs["id_card"]?>">
                    </div>
                    <div class="form-group mb-2">
                        <label for="name">ชื่อ</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?=$rs["name"]?>">
                    </div>
                    <div class="form-group mb-2">
                        <label for="role">สาขา</label>
                        <select class="form-control" id="branch" name="branch">
                            <?php
                            while ($row = mysqli_fetch_assoc($qr_branch)) {
                            ?>
                                <option value="<?= $row["id"] ?>" <?= $rs["branchId"] == $row["id"] ? 'selected' : '' ?>><?= $row["name"] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="role">ปีการศึกษา</label>
                        <select class="form-control" id="year" name="year">
                            <?php
                            while ($row = mysqli_fetch_assoc($qr_year)) {
                            ?>
                                <option value="<?= $row["id"] ?>" <?= $rs["yearId"] == $row["id"] ? 'selected' : '' ?> ><?= $row["name"] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <input type="hidden" name="id" value="<?= $rs["id"] ?>">
                </div>
                <div class="d-flex justify-content-between my-4">
                    <button type="button" class="btn btn-primary" id="saveButton">บันทึก</button>
                    <a class="btn btn-danger deleteButton" href="./main.php?page=list-students">ยกเลิก</a>

                </div>

            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('saveButton').addEventListener('click', async function() {
        const form = document.getElementById('addStudentForm');
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
                url: "./functions/students.php",
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
                            window.location.replace('/Checkin_OnlineTRU/user/main.php?page=list-students');
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