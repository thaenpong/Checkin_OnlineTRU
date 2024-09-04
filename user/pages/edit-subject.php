<?php

$id = intval($_GET["id"]);

$sql_suject = "SELECT subjects.*, 
        branchs.name AS branchName,
        branchs.id AS branchId
        FROM subjects 
        INNER JOIN branchs ON subjects.branch_id = branchs.id
        WHERE subjects.id = $id";
$qr_subject = mysqli_query($conn, $sql_suject);
$rs_subject = mysqli_fetch_assoc($qr_subject);

$sql_teacher = "SELECT * FROM teachers WHERE is_active = 1";
$qr_teacher = mysqli_query($conn, $sql_teacher);

$sql_branch = "SELECT * FROM branchs WHERE is_active = 1";
$qr_branch = mysqli_query($conn, $sql_branch);

?>

<html>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2>แก้ไขข้อมูลวิชา</h2>
            <form action="./functions/subjects.php" method="post" id="editSubjectForm">
                <div class="my-4">
                    <div class="form-group mb-2">
                        <label for="code">รหัสวิชา</label>
                        <input type="text" class="form-control" id="code" name="code" value="<?= $rs_subject["code"] ?>">
                    </div>
                    <div class="form-group mb-2">
                        <label for="name">ชื่อวิชา</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= $rs_subject["name"] ?>">
                    </div>
                    <div class="form-group mb-2">
                        <label for="role">สาขา</label>
                        <select class="form-control" id="branch" name="branch">
                            <?php
                            while ($row = mysqli_fetch_assoc($qr_branch)) {
                            ?>
                                <option value="<?= $row["id"] ?>" <?= $rs_subject["branchId"] == $row["id"] ? 'selected' : '' ?>><?= $row["name"] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <input type="hidden" name="id" value="<?= $rs_subject["id"] ?>">
                </div>
                <div class="d-flex justify-content-between my-4">
                    <button type="button" class="btn btn-primary" id="saveButton">บันทึก</button>
                    <!--  <a class="btn btn-danger deleteButton" href="./main.php?page=list-teachers">ยกเลิก</a> -->

                </div>

            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('saveButton').addEventListener('click', async function() {
        const form = document.getElementById('editSubjectForm');
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
                url: "./functions/subjects.php",
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
                            window.location.replace('/Checkin_OnlineTRU/user/main.php?page=list-subjects');
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