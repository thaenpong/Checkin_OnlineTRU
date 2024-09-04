<?php

$sql = "SELECT * FROM years WHERE is_active = 1";

$result = mysqli_query($conn, $sql);
?>
<html>
<div class="my-4">
    <h1>รายชื่อปีการศึกษา</h1>
</div>

<div class="mb-3">
    <a href="./main.php?page=add-year" class="btn btn-success">เพิ่มปีการศึกษา</a>
</div>

<div>
    <div class="card p-2">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">ลำดับ</th>
                    <th scope="col">รหัส</th>
                    <th scope="col">ปีการศึกษา</th>
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
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td>
                            <a href="./main.php?page=edit-year&id=<?php echo $row['id']; ?>" class="btn btn-primary">แก้ไข</a>
                            <button class="btn btn-danger deleteButton" data-id="<?= $row["id"] ?>">ลบ</button>
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
                    const response = await fetch('./functions/year.php', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `id=${id}`
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