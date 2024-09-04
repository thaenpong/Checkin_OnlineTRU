<?php
include "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    // Sanitize input to prevent SQL injection
    $subject = $_GET["subject"];
    $year = $_GET["year"];

    /* $sql = "SELECT sessions.*, 
    subjects.name AS subjectName,
    years.name AS yearName
    FROM sessions 
    INNER JOIN subjects ON sessions.subject_id = subjects.id
    INNER JOIN years ON sessions.year_id = years.id
    WHERE sessions.subject_id = '$subject' AND
    sessions.year_id = '$year'"; */
    $sql = "SELECT students.*,
            years.name AS yearName,
            years.id AS yearId,
            branchs.name AS branchName
            FROM students 
            INNER JOIN branchs ON branchs.id = students.branch_id
            INNER JOIN years ON years.id = students.year_id
            WHERE students.id IN (
                    SELECT student_id 
                    FROM register 
                    WHERE subject_id = '$subject'
                )
            AND students.is_active = 1
            AND years.is_active = 1";

    if ($year != '0') {
        $sql = "SELECT students.*,
            years.name AS yearName,
            years.id AS yearId,
            branchs.name AS branchName
            FROM students 
            INNER JOIN branchs ON branchs.id = students.branch_id
            INNER JOIN years ON years.id = students.year_id
            WHERE students.id IN (
                    SELECT student_id 
                    FROM register 
                    WHERE subject_id = '$subject'
                )
            AND students.year_id = '$year'
            AND students.is_active = 1
            AND years.is_active = 1";
    }
    $result = mysqli_query($conn, $sql);



    if ($result) {
        $data = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $student_id = $row['id']; // Assuming 'rowid' is the column name for student_id
            $year_id = $row['year_id']; // Assuming 'rowyear' is the column name for year_id

            $sql_count = "SELECT COUNT(checkin.id) AS session_count
                            FROM checkin
                            INNER JOIN sessions ON checkin.sessions_id = sessions.id
                            WHERE checkin.student_id = $student_id
                            AND sessions.year_id = $year_id
                            AND sessions.subject_id = $subject";

            $sql_count_all = "SELECT COUNT(id) AS session_count_all 
                                FROM sessions
                                WHERE subject_id = $subject 
                                AND year_id = $year_id";

            // Assuming you execute these queries with your MySQL connection
            $result_count = mysqli_query($conn, $sql_count);
            $result_count_all = mysqli_query($conn, $sql_count_all);

            // Fetching counts from the results
            $count_row = mysqli_fetch_assoc($result_count);
            $count_row_all = mysqli_fetch_assoc($result_count_all);

            // Adding counts to the data array
            $row['session_count'] = $count_row['session_count'];
            $row['session_count_all'] = $count_row_all['session_count_all'];

            // Adding the row with counts to the data array
            $data[] = $row;
        }

        // Return the data as JSON
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {

        ob_clean();
        // If there is an error with the query, return an error message
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Error executing query: ' . mysqli_error($conn)));
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Ensure all required fields are present and sanitize inputs
        $subject = $_POST['subject'];
        $year = $_POST['year'];
        $teacher = $_POST['teacher'];
        // Validate inputs (e.g., ensure fields are not empty if required, perform additional validation as needed)

        // Insert new teacher
        $sql_insert_session = "INSERT INTO sessions (subject_id, year_id, teacher_id) VALUES ('$subject', '$year', '$teacher')";
        $result_session = mysqli_query($conn, $sql_insert_session);

        if ($result_session) {
            $session_id = mysqli_insert_id($conn);

            // Return JSON response
            ob_clean();
            header('Content-Type: application/json');
            echo json_encode(['success' => 'บันทึกสำเร็จ', 'id' => $session_id]);
        } else {
            ob_clean();
            http_response_code(400);
            echo json_encode(['message' => 'ไม่สามารถบันทึกข้อมูลได้']);
        }

        // Close database connection
        mysqli_close($conn);
    } catch (Exception $e) {

        ob_clean();
        http_response_code(400);
        echo json_encode(['message' => "เกิดข้อผิดพลาด: " . $e->getMessage()]);
    }
}
