<?php

$id = intval($_GET["id"]);

$sql_year = "SELECT * FROM branchs WHERE id = $id";
$qr_year = mysqli_query($conn, $sql_year);
$rs_year = mysqli_fetch_assoc($qr_year);

?>

<html>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2>แก้ไขข้อมูลสาขา</h2>
            <form  id="editBranchForm">
                <div class="my-4">
                    <div class="form-group mb-2">
                        <label for="name">ชื่อสาขา</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= $rs_year["name"] ?>">
                    </div>
                    <input type="hidden" name="id" value="<?= $rs_year["id"] ?>">
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
        const form = document.getElementById('editBranchForm');
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
                url: "./functions/branch.php",
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
                            window.location.replace('/Checkin_OnlineTRU/user/main.php?page=list-branchs');
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