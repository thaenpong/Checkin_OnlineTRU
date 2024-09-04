<?php
$sql_year = "SELECT * FROM years WHERE is_active = 1 ORDER BY id DESC ";
$qr_year = mysqli_query($conn, $sql_year);

$sql_branch = "SELECT * FROM branchs WHERE is_active = 1 ORDER BY id DESC ";
$qr_branch = mysqli_query($conn, $sql_branch);
?>
<html>

<head>
    <!-- Include SweetAlert2 CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="my-4">
        <h1>รายชื่อนักศึกษา</h1>
    </div>

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <a href="./main.php?page=add-students" class="btn btn-success me-2">เพิ่มนักศึกษา</a>
    </div>

    <div class="mb-3">
        <select id="branchSelect" class="form-select" aria-label="Default select example">
            <option value="0" selected>เลือกสาขา</option>
            <?php while ($row = mysqli_fetch_assoc($qr_branch)) { ?>
                <option value="<?= $row["id"] ?>"><?= $row["name"] ?></option>
            <?php } ?>
        </select>
    </div>

    <div class="mb-3">
        <select id="yearSelect" class="form-select" aria-label="Default select example">
            <option value="0" selected>เลือกปีการศึกษา</option>
            <?php while ($row = mysqli_fetch_assoc($qr_year)) { ?>
                <option value="<?= $row["id"] ?>"><?= $row["name"] ?></option>
            <?php } ?>
        </select>
    </div>

    <div>
        <div class="card p-2">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">ลำดับ</th>
                        <th scope="col">รหัสนักศึกษา</th>
                        <th scope="col">รหัสบัตรประชาชน</th>
                        <th scope="col">ชื่อ</th>
                        <th scope="col">สาขา</th>
                        <th scope="col">จัดการ</th>
                    </tr>
                </thead>
                <tbody id="studentsTableBody">
                    <!-- Data will be inserted here -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const branchSelect = document.getElementById('branchSelect');
            const yearSelect = document.getElementById('yearSelect');
            const studentsTableBody = document.getElementById('studentsTableBody');

            const fetchData = () => {
                const branch = branchSelect.value;
                const year = yearSelect.value;

                fetch(`./functions/students.php?branch=${branch}&year=${year}`)
                    .then(response => response.json())
                    .then(data => {
                        studentsTableBody.innerHTML = '';

                        if (data.error) {
                            console.error(data.error);
                            return;
                        }

                        let count = 1;
                        const userRole = <?= $_SESSION["user"]["role"] ?>;
                        data.forEach(row => {
                            const tr = document.createElement('tr');

                            tr.innerHTML = `
                            <th scope="row">${count++}</th>
                            <td>${row.code}</td>
                            <td>${row.id_card}</td>
                            <td>${row.name}</td>
                            <td>${row.branchName}</td>
                            <td>
                                ${userRole == 1 ? `
                                    <a href="./main.php?page=edit-students&id=${row.id}" class="btn btn-primary" target="_blank">แก้ไข</a> | 
                                    <button class="btn btn-danger deleteButton" data-id="${row.id}">ยกเลิก</button>
                                ` : ''}
                            </td>
                        `;

                            studentsTableBody.appendChild(tr);
                        });

                        // Attach delete button event listeners after rows are added
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
                                        const response = await fetch('./functions/students.php', {
                                            method: 'DELETE',
                                            headers: {
                                                'Content-Type': 'application/x-www-form-urlencoded',
                                            },
                                            body: `student_id=${id}`
                                        });

                                        if (response.status == 200) {
                                            Swal.fire(
                                                'ลบข้อมูลเรียบร้อย!',
                                                'ข้อมูลของคุณถูกลบแล้ว.',
                                                'success'
                                            ).then(() => {
                                                fetchData();
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
                    })
                    .catch(error => console.error('Error fetching data:', error));
            };

            branchSelect.addEventListener('change', fetchData);
            yearSelect.addEventListener('change', fetchData);

            // Initial fetch
            fetchData();
        });
    </script>

</body>

</html>