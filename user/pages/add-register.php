<?php
$sql_years = "SELECT * FROM years WHERE is_active = 1 ORDER BY id DESC ";
$qr_years = mysqli_query($conn, $sql_years);

$sql_subjects = "SELECT * FROM subjects  WHERE is_active = 1 ORDER BY id DESC";
$qr_subjects = mysqli_query($conn, $sql_subjects);

$sql_branch = "SELECT * FROM branchs WHERE is_active = 1 ORDER BY id DESC";
$qr_branch = mysqli_query($conn, $sql_branch);
?>
<html>
<div class="my-4">
    <h1>ลงทะเบียนวิชาเรียน</h1>
</div>


<div class="mb-3">
    <select id="branchSelect" class="form-select" aria-label="Default select example">
        <option value="0" selected>สาขาทั้งหมด</option>
        <?php
        while ($row = mysqli_fetch_assoc($qr_branch)) {
        ?>
            <option value="<?= $row["id"] ?>"><?= $row["name"] ?></option>
        <?php
        }
        ?>
    </select>
</div>

<div class="mb-3">
    <select id="yearSelect" class="form-select" aria-label="Default select example">
        <option value="0" selected>เลือกปีการศึกษา</option>
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
    <br>
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

<div>
    <div class="card p-2">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">ลำดับ</th>
                    <th scope="col">รหัสนักศึกษา</th>
                    <th scope="col">นักศึกษา</th>
                    <th scope="col">ปีการศึกษา</th>
                    <th scope="col">สาขา</th>
                    <th scope="col">เลือก</th>
                </tr>
            </thead>
            <tbody id="subjectsTableBody">
                <!-- Data will be inserted here -->
            </tbody>
        </table>

    </div>
    <div>
        <div class="text-end my-3">
            <button class="btn btn-primary" id="save">บันทึก</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const subjectSelect = document.getElementById('subjectSelect');
        const yearSelect = document.getElementById('yearSelect');
        const branchSelect = document.getElementById('branchSelect');
        const studentsTableBody = document.getElementById('subjectsTableBody');
        const saveButton = document.getElementById('save');
        let selectedRows = [];

        const fetchData = () => {
            const subject = subjectSelect.value;
            const year = yearSelect.value;
            const branch = branchSelect.value;
            selectedRows = [];

            if (subject != 0) {
                fetch(`./functions/register.php?year=${year}&branch=${branch}&subject=${subject}`)
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
                        <td>${row.yearName}</td>
                        <td>${row.branchName}</td>
                        <td>
                        ${row.is_register == 1 
                            ? '<button class="btn btn-secondary" disabled>ลงทะเบียนแล้ว</button>' 
                            : `<button class="btn btn-warning select-btn" data-id="${row.id}">เลือก</button>`}
                        </td>
                    `;

                            studentsTableBody.appendChild(tr);
                        });

                        document.querySelectorAll('.select-btn').forEach(button => {
                            button.addEventListener('click', () => {
                                const rowId = button.getAttribute('data-id');
                                if (button.classList.contains('btn-warning')) {
                                    button.classList.remove('btn-warning');
                                    button.classList.add('btn-danger');
                                    button.textContent = 'ยกเลิก';
                                    selectedRows.push(rowId);
                                } else {
                                    button.classList.remove('btn-danger');
                                    button.classList.add('btn-warning');
                                    button.textContent = 'เลือก';
                                    selectedRows = selectedRows.filter(id => id !== rowId);
                                }
                            });
                        });
                    })
                    .catch(error => console.error('Error fetching data:', error));
            }
        };

        yearSelect.addEventListener('change', fetchData);
        branchSelect.addEventListener('change', fetchData);
        subjectSelect.addEventListener('change', fetchData);

        saveButton.addEventListener('click', async () => {
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
                const selectedSubject = subjectSelect.value;
                const formData = new URLSearchParams();
                formData.append('studentArray', JSON.stringify(selectedRows));
                formData.append('subject', selectedSubject);

                $.ajax({
                    type: "POST",
                    url: "./functions/register.php",
                    contentType: "application/x-www-form-urlencoded",
                    data: formData.toString(), // Send serialized form data
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'บันทึกสำเร็จ!',
                                'ข้อมูลของคุณถูกบันทึกแล้ว.',
                                'success'
                            ).then(() => {
                                window.location.reload();
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

        // Initial fetch
        fetchData();
    });
</script>

</html>