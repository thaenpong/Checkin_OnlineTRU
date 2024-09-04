<?php
$sql_subjects = "SELECT subjects.* 
                FROM subjects 
                INNER JOIN register ON register.subject_id = subjects.id
                WHERE register.student_id =" . $_SESSION["student"]["id"] .
                " AND subjects.is_active = 1";
$qr_subjects = mysqli_query($conn, $sql_subjects);

?>
<html>
<div class="my-4">
    <h1>เช็คเวลาเข้าเรียน</h1>
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


<div class="my-3">
    <div class="card p-2">
        <p>คาบเรียน : <span id="sessionAll"></span></p>
        <p>เข้าเรียน : <span id="sessionCheckin"></span></p>
        <p>ขาดเรียน : <span id="sessionAbsent"></span></p>
        <p>สิทธิสอบ : <span id="testing"></span></p>
    </div>
</div>


<div>
    <div class="card p-2">

        <div class="table-responsive">
            <table class="table  table-hover">
                <thead>
                    <tr class="text-center">
                        <th scope="col">วีค</th>
                        <th scope="col">วันที่</th>
                        <th scope="col">เวลา</th>
                        <th scope="col" class="text-center">เช็คชื่อ</th>
                    </tr>
                </thead>
                <tbody id="subjectsTableBody" class="text-center">
                    <!-- Data will be inserted here -->
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const subjectSelect = document.getElementById('subjectSelect');
        const studentsTableBody = document.getElementById('subjectsTableBody');

        // Elements for displaying session info
        const sessionAll = document.getElementById('sessionAll');
        const sessionCheckin = document.getElementById('sessionCheckin');
        const sessionAbsent = document.getElementById('sessionAbsent');
        const testing = document.getElementById('testing');

        const fetchData = () => {
            const subject = subjectSelect.value;
            const year = '<?= $_SESSION["student"]["yearId"] ?>';
            const student_id = <?= $_SESSION["student"]["id"] ?>;

            fetch(`./functions/session.php?subject=${subject}&year=${year}&student_id=${student_id}`)
                .then(response => response.json())
                .then(data => {
                    studentsTableBody.innerHTML = '';

                    if (data.error) {
                        console.error(data.error);
                        return;
                    }

                    let count = 1;
                    let checkinCount = 0;

                    data.forEach(row => {
                        const tr = document.createElement('tr');
                        const isRegistered = row.formatted_time_checkin !== "";

                        if (isRegistered) {
                            checkinCount++;
                        }

                        tr.innerHTML = `
                            <td>${count++}</td>
                            <td>${row.formatted_date}</td>
                            <td>${row.formatted_time}</td>
                            <td>
                                ${isRegistered 
                                    ? row.formatted_time_checkin
                                    : '⛌'}
                            </td>
                        `;

                        studentsTableBody.appendChild(tr);
                    });

                    // Update session info
                    const totalSessions = data.length;
                    const absentCount = totalSessions - checkinCount;

                    sessionAll.textContent = totalSessions;
                    sessionCheckin.textContent = checkinCount;
                    sessionAbsent.textContent = absentCount;
                    testing.textContent = checkinCount >= 8 ? 'มีสิทธิ์' : 'ไม่มีสิทธิ์';
                })
                .catch(error => console.error('Error fetching data:', error));
        };

        subjectSelect.addEventListener('change', fetchData);

        // Initial fetch
        fetchData();
    });
</script>



</html>