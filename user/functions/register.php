<?php
include "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Sanitize input to prevent SQL injection
    $year = mysqli_real_escape_string($conn, $_GET['year']);
    $branch = mysqli_real_escape_string($conn, $_GET['branch']);
    $subject = mysqli_real_escape_string($conn, $_GET['subject']);

    $sql = "";

    if ($branch == '0') {
        $sql = "SELECT students.*, 
                branchs.name AS branchName,
                branchs.id AS branchId,
                years.name AS yearName,
                years.id AS yearId,
                CASE 
                    WHEN register.student_id IS NOT NULL THEN true
                    ELSE false
                END AS is_register
                FROM students 
                INNER JOIN years ON students.year_id = years.id
                INNER JOIN branchs ON branchs.id = students.branch_id
                LEFT JOIN register ON students.id = register.student_id 
                AND register.subject_id = '$subject'
                WHERE students.year_id = '$year'
                AND students.is_active = 1";
    } else {
        $sql = "SELECT students.*, 
                branchs.name AS branchName,
                branchs.id AS branchId,
                years.name AS yearName,
                years.id AS yearId,
                CASE 
                    WHEN register.student_id IS NOT NULL THEN true
                    ELSE false
                END AS is_register
                FROM students 
                INNER JOIN years ON students.year_id = years.id
                INNER JOIN branchs ON branchs.id = students.branch_id
                LEFT JOIN register ON students.id = register.student_id 
                AND register.subject_id = '$subject'
                WHERE students.year_id = '$year' 
                AND students.branch_id = '$branch'
                AND students.is_active = 1";
    }

    $result = mysqli_query($conn, $sql);

    if ($result) {
        $data = array();

        while ($row = mysqli_fetch_assoc($result)) {
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
        if (isset($_POST['subject']) && isset($_POST['studentArray'])) {
            $subject = $_POST['subject'];
            $studentIdArray = json_decode($_POST['studentArray'], true);

            if (!is_array($studentIdArray)) {
                throw new Exception('Invalid student array format');
            }

            // Prepare the SQL statement
            $stmt = $conn->prepare("INSERT INTO register (subject_id, student_id) VALUES (?, ?)");

            foreach ($studentIdArray as $studentId) {
                // Bind the parameters and execute the statement
                $stmt->bind_param("ii", $subject, $studentId);

                if (!$stmt->execute()) {
                    throw new Exception("Error inserting data for student ID: $studentId");
                }
            }

            // Close the statement
            $stmt->close();

            // Return JSON response
            ob_clean();
            header('Content-Type: application/json');
            echo json_encode(['success' => 'บันทึกสำเร็จ']);
        } else {
            throw new Exception('Required fields are missing');
        }

        // Close database connection
        mysqli_close($conn);
    } catch (Exception $e) {
        ob_clean();
        http_response_code(400);
        echo json_encode(['message' => "เกิดข้อผิดพลาด: " . $e->getMessage()]);
    }
}
