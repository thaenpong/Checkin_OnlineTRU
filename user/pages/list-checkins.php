<?php
$role = $_SESSION["user"]["role"];
$sql_years = "SELECT * FROM years WHERE is_active = 1 ORDER BY  id DESC";
$qr_years = mysqli_query($conn, $sql_years);

$sql_subjects = "SELECT * FROM subjects WHERE is_active = 1 ORDER BY id DESC";
$qr_subjects = mysqli_query($conn, $sql_subjects);
?>
<html>
<div class="my-4">
    <h1>รายชื่อเข้าเรียนนักศึกษา</h1>
</div>

<div class="mb-3">
    <select id="subjectSelect" class="form-select" aria-label="Default select example">
        <option value="0" selected>เลือกวิชา</option>
        <?php
        while ($row = mysqli_fetch_assoc($qr_subjects)) {
        ?>
            <option value="<?= $row["id"] ?>"><?= $row["code"] . ' | ' . $row["name"] ?></option>
        <?php
        }
        ?>
    </select>
</div>

<div class="mb-3">
    <select id="yearSelect" class="form-select" aria-label="Default select example">
        <option value="0" selected>เลือกปีการศึกษาของนักศึกษา</option>
        <?php
        while ($row = mysqli_fetch_assoc($qr_years)) {
        ?>
            <option value="<?= $row["id"] ?>"><?= $row["name"] ?></option>
        <?php
        }
        ?>
    </select>
</div>

<div>
    <div class="card p-2">
        <div class="table-responsive">
            <table class="table  table-hover">
                <thead>
                    <tr>
                        <th scope="col">ลำดับ</th>
                        <th scope="col">รหัสนักศึกษา</th>
                        <th scope="col">นักศึกษา</th>
                        <th scope="col">สาขา</th>
                        <th scope="col">ปีการศึกษา</th>
                        <th scope="col">จำนวนคาบเรียน</th>
                        <th scope="col">มา</th>
                        <th scope="col">ขาด</th>
                        <th scope="col">สิทธิสอบ</th>
                        <th scope="col">จัดการ</th>
                    </tr>
                </thead>
                <tbody id="subjectsTableBody">
                    <!-- Data will be inserted here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const subjectSelect = document.getElementById('subjectSelect');
        const yearSelect = document.getElementById('yearSelect');
        const studentsTableBody = document.getElementById('subjectsTableBody');

        const fetchData = () => {
            const subject = subjectSelect.value;
            const year = yearSelect.value;

            fetch(`./functions/session.php?subject=${subject}&year=${year}`)
                .then(response => response.json())
                .then(data => {
                    studentsTableBody.innerHTML = '';

                    if (data.error) {
                        console.error(data.error);
                        return;
                    }

                    let count = 1;
                    data.forEach(row => {
                        const tr = document.createElement('tr');

                        tr.innerHTML = `
                        <th scope="row">${count++}</th>
                        <td>${row.code}</td>
                        <td>${row.name}</td>
                        <td>${row.branchName}</td>
                        <td>${row.yearName}</td>
                        <td>${row.session_count_all}</td>
                        <td>${row.session_count}</td>
                        <td>${row.session_count_all - row.session_count}</td>
                        <td>${row.session_count >= 9  ? `มีสิทธิ์`:`ไม่มีสิทธิ์`}</td>
                        <td>
                        ${<?= $_SESSION['user']['role'] ?>  == 2  ? 
                            `<a href="./main.php?page=edit-checkin&id=${row.id}&subject=${subject}&year=${row.yearId}" class="btn btn-primary" target='_blank'>แก้ไข</a>` :
                            `<button class="btn btn-danger deleteButton" data-id="${row.id}">ยกเลิกลงทะเบียน</button> `
                            }
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
                                text: "ข้อมูลนี้จะถูยกเลิกลงทะเบียนและไม่สามารถกู้คืนได้!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'ใช่, ลบเลย!',
                                cancelButtonText: 'ยกเลิก'
                            });

                            if (result.isConfirmed) {
                                try {
                                    const params = new URLSearchParams();
                                    params.append('student_id', id);
                                    params.append('subject_id', subject);

                                    // If confirmed, send the DELETE request
                                    const response = await fetch('./functions/checkin.php', {
                                        method: 'DELETE',
                                        headers: {
                                            'Content-Type': 'application/x-www-form-urlencoded',
                                        },
                                        body: params
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

        subjectSelect.addEventListener('change', fetchData);
        yearSelect.addEventListener('change', fetchData);

        // Initial fetch
        fetchData();
    });
</script>

</html>