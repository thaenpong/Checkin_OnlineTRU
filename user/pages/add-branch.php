<html>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">เพิ่มข้อมูลสาขา</h2>
                    <form  id="addBranchForm">
                        <div class="my-4">
                            <div class="form-group mb-2">
                                <label for="name">ชื่อสาขา</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between my-4">
                            <button type="button" class="btn btn-primary" id="saveButton">บันทึก</button>
                            <a class="btn btn-danger deleteButton" href="./main.php?page=list-branchs">ยกเลิก</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>


<script>
    document.getElementById('saveButton').addEventListener('click', async function() {
        const form = document.getElementById('addBranchForm');
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