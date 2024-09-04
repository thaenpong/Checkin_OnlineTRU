<?php

$sql = "SELECT subjects.*, 
        branchs.name AS branchName
        FROM subjects 
        INNER JOIN branchs ON subjects.branch_id = branchs.id
        WHERE subjects.is_active = 1";

$result = mysqli_query($conn, $sql);
?>
<html>
<div class="my-4">
    <h1>รายชื่อวิชาที่เปิดสอน</h1>
</div>

<div class="mb-3 d-flex justify-content-between align-items-center">
    <a href="./main.php?page=add-subject" class="btn btn-success">เพิ่มวิชาใหม่</a>


</div>
<div>
    <div class="card p-2">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">ลำดับ</th>
                    <th scope="col">รหัส</th>
                    <th scope="col">ชื่อ</th>
                    <th scope="col">สาขา</th>
                    <th scope="col">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Output data of each row
                $count = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <tr>
                        <th scope="row"><?php echo $count++; ?></th>
                        <td><?php echo $row['code']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['branchName']; ?></td>
                        <td>
                            <a href="./main.php?page=edit-subject&id=<?php echo $row['id']; ?>" class="btn btn-primary">แก้ไข</a>
                            <button class="btn btn-danger deleteButton" data-id="<?= $row['id']; ?>">ยกเลิก</button>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    // Use class selector since IDs should be unique
    const deleteButtons = document.querySelectorAll('.deleteButton');

    deleteButtons.forEach(button => {
        button.addEventListener('click', async function() {
            const id = this.getAttribute('data-id');

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
                    const response = await fetch('./functions/subjects.php', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `subject_id=${id}`
                    });

                    if (response.status == 200) {
                        Swal.fire(
                            'ลบข้อมูลเรียบร้อย!',
                            'ข้อมูลของคุณถูกลบแล้ว.',
                            'success'
                        ).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire(
                            'เกิดข้อผิดพลาด!',
                            'ไม่สามารถลบข้อมูลได้.',
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
    });
</script>

</html>