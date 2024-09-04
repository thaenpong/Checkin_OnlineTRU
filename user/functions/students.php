<?php
include "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    // Sanitize input to prevent SQL injection
    $branch = mysqli_real_escape_string($conn, $_GET['branch']);
    $year = mysqli_real_escape_string($conn, $_GET['year']); // Corrected from $_GET['branch'] to $_GET['year']

    $sql = "SELECT students.*, 
    branchs.name AS branchName,
    branchs.id AS branchId,
    years.name AS yearName,
    years.id AS yearId
    FROM students 
    INNER JOIN branchs ON students.branch_id = branchs.id
    INNER JOIN years ON students.year_id = years.id
    WHERE students.branch_id = '$branch' AND
    students.year_id = '$year'AND
    students.is_active = 1";

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
        $code = $_POST['code'];
        $id_card = $_POST['id_card'];
        $name = $_POST['name'];
        $branch = $_POST['branch'];
        $year = $_POST['year'];
        // Validate inputs (e.g., ensure fields are not empty if required, perform additional validation as needed)

        // Insert new teacher
        $sql_insert_student = "INSERT INTO students (code, id_card, name, branch_id, year_id) VALUES ('$code', '$id_card', '$name', '$branch', '$year')";
        $result_student = mysqli_query($conn, $sql_insert_student);

        if ($result_student) {
            // Set success message
            $success = "บันทึกสำเร็จ";
            $error = ""; // Clear any previous errors
            // Return JSON response
            ob_clean();
            header('Content-Type: application/json');
            echo json_encode(['success' => 'บันทึกสำเร็จ']);
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

if ($_SERVER["REQUEST_METHOD"] == "PATCH") {
    parse_str(file_get_contents("php://input"), $_PATCH); // Parse the raw data as PATCH parameters

    try {
        // Ensure all required fields are present and sanitize inputs
        $id = $_PATCH['id'];
        $code = $_PATCH['code'];
        $id_card = $_PATCH['id_card'];
        $name = $_PATCH['name'];
        $branch = $_PATCH['branch'];
        $year = $_PATCH['year'];

        // Validate inputs (e.g., ensure IDs are valid, fields are not empty if required)

        // Update subject info
        $sql_update_student = "UPDATE students SET code = '$code', id_card = '$id_card', name = '$name', branch_id = '$branch', year_id = '$year' WHERE id = $id";
        $result_student = mysqli_query($conn, $sql_update_student);

        if ($result_student) {
            // Set success message
            $success = "บันทึกสำเร็จ";
            $error = ""; // Clear any previous errors
            // Return JSON response
            header('Content-Type: application/json');
            ob_clean();
            echo json_encode(['success' => 'บันทึกสำเร็จ',]);
        } else {
            ob_clean();
            http_response_code(400);
            echo json_encode(['message' => "error : $sql"]);
        }

        // Close database connection
        mysqli_close($conn);
    } catch (Exception $e) {
        //ob_clean();
        http_response_code(400);
        echo json_encode(['message' => "error : $e",]);
    }
}

// Check if the request method is DELETE
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Get the input data (e.g., teacher ID) from the DELETE request
    parse_str(file_get_contents("php://input"), $input);

    // Check if teacher_id is set in the input data
    if (isset($input['student_id'])) {
        $student_id = intval($input['student_id']);

        // Prepare the SQL statement
        $sql = "UPDATE students SET is_active = 0 WHERE id = ?";

        // Create a prepared statement
        if ($stmt = $conn->prepare($sql)) {
            // Bind the teacher_id parameter to the prepared statement
            $stmt->bind_param('i', $student_id);

            // Execute the statement
            if ($stmt->execute()) {
                // Send a success response
                ob_clean();
                echo json_encode(['success' => true]);
            } else {
                // Send an error response
                ob_clean();
                echo json_encode(['success' => false, 'error' => 'Unable to execute the query.']);
            }

            // Close the statement
            $stmt->close();
        } else {
            // Send an error response
            ob_clean();
            echo json_encode(['success' => false, 'error' => 'Unable to prepare the query.']);
        }
    } else {
        // Send an error response
        ob_clean();
        echo json_encode(['success' => false, 'error' => 'Teacher ID is required.']);
    }
}