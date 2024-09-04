<?php
$success = "";
$error = "";


$sql_teacher = "SELECT * FROM teachers WHERE is_active = 1";
$qr_teacher = mysqli_query($conn, $sql_teacher);

$sql_branch = "SELECT * FROM branchs WHERE is_active = 1";
$qr_branch = mysqli_query($conn, $sql_branch);

?>

<html>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">เพิ่มข้อมูลวิชา</h2>
                    <form action="./functions/teachers.php" method="post" id="editSubjectForm">
                        <div class="my-4">
                            <div class="form-group mb-2">
                                <label for="code">รหัสวิชา</label>
                                <input type="text" class="form-control" id="code" name="code">
                            </div>
                            <div class="form-group mb-2">
                                <label for="name">ชื่อวิชา</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                            <div class="form-group mb-2">
                                <label for="branch">สาขา</label>
                                <select class="form-control" id="branch" name="branch">
                                    <?php
                                    while ($row = mysqli_fetch_assoc($qr_branch)) {
                                    ?>
                                        <option value="<?= $row["id"] ?>"><?= $row["name"] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between my-4">
                            <button type="button" class="btn btn-primary" id="saveButton">บันทึก</button>
                            <a class="btn btn-danger deleteButton" href="./main.php?page=list-subjects">ยกเลิก</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-5">

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Import ข้อมูลจากไฟล์ CSV</h2>
                    <form enctype="multipart/form-data" class="d-flex align-items-center" id="importForm">
                        <div class="input-group">
                            <input type="file" class="form-control" id="fileInput" name="file" accept=".csv">
                            <button type="button" class="btn btn-primary" id="import">Import</button>
                        </div>
                    </form>
                </div>
            </div>
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
                type: "POST",
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

    document.getElementById('import').addEventListener('click', async function() {
        const form = document.getElementById('importForm');
        const formData = new FormData();
        const fileInput = document.getElementById('fileInput').files[0];

        if (!fileInput) {
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด!',
                text: 'โปรดเลือกไฟล์ CSV ก่อนที่จะอัปโหลด',
            });
            return;
        }

        formData.append('file', fileInput);

        // Show the SweetAlert confirmation dialog
        const result = await Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: 'ต้องการอัปโหลดไฟล์ CSV นี้?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่, อัปโหลดเลย!',
            cancelButtonText: 'ยกเลิก'
        });

        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                url: "./functions/csv-subject.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        Swal.fire(
                            'อัปโหลดสำเร็จ!',
                            'ไฟล์ CSV ได้ถูกอัปโหลดแล้ว.',
                            'success'
                        ).then(() => {
                            window.location.replace('/Checkin_OnlineTRU/user/main.php?page=list-subjects');
                        });
                    } else {
                        Swal.fire(
                            'เกิดข้อผิดพลาด!',
                            response.message || 'ไม่สามารถอัปโหลดไฟล์ CSV ได้.',
                            'error'
                        );
                    }
                },
                error: function(response) {
                    console.log(response);
                    Swal.fire(
                        'เกิดข้อผิดพลาด!',
                        'ไม่สามารถเพิ่มข้อมูลได้: ' + response.responseJSON.message,
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