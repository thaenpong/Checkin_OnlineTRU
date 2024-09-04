<?php

include 'connect.php';

// Check if the request method is DELETE
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Get the input data (e.g., teacher ID) from the DELETE request
    parse_str(file_get_contents("php://input"), $input);

    // Check if teacher_id is set in the input data
    if (isset($input['teacher_id'])) {
        $teacher_id = intval($input['teacher_id']);

        // Prepare the SQL statement
        $sql = "UPDATE teachers SET is_active = 0 WHERE id = ?";

        // Create a prepared statement
        if ($stmt = $conn->prepare($sql)) {
            // Bind the teacher_id parameter to the prepared statement
            $stmt->bind_param('i', $teacher_id);

            // Execute the statement
            if ($stmt->execute()) {
                // Send a success response
                echo json_encode(['success' => true]);
            } else {
                // Send an error response
                echo json_encode(['success' => false, 'error' => 'Unable to execute the query.']);
            }

            // Close the statement
            $stmt->close();
        } else {
            // Send an error response
            echo json_encode(['success' => false, 'error' => 'Unable to prepare the query.']);
        }
    } else {
        // Send an error response
        echo json_encode(['success' => false, 'error' => 'Teacher ID is required.']);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "PATCH") {
    parse_str(file_get_contents("php://input"), $_PATCH); // Parse the raw data as PATCH parameters

    try {
        // Ensure all required fields are present and sanitize inputs
        $id = $_PATCH['id'];
        $code = $_PATCH['code'];
        $name = $_PATCH['name'];
        $role = $_PATCH['role'];

        $username = $_PATCH['username'];
        $password = $_PATCH['password'];
        $userRole = $_PATCH['userRole'];

        // Validate inputs (e.g., ensure IDs are valid, fields are not empty if required)

        // Update teacher info
        $sql_update_teacher = "UPDATE teachers SET code = '$code', name = '$name', role = '$role' WHERE id = $id";
        $result_teacher = mysqli_query($conn, $sql_update_teacher);

        // Update user info
        $sql_update_user = "UPDATE users SET username = '$username', password = '$password', role ='$userRole' WHERE teacher = $id";
        $result_user = mysqli_query($conn, $sql_update_user);

        if ($result_teacher && $result_user) {
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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Ensure all required fields are present and sanitize inputs
        $code = $_POST['code'];
        $name = $_POST['name'];
        $role = $_POST['role'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $userRole = $_POST['userRole'];

        // Validate inputs (e.g., ensure fields are not empty if required, perform additional validation as needed)

        // Insert new teacher
        $sql_insert_teacher = "INSERT INTO teachers (code, name, role) VALUES ('$code', '$name', '$role')";
        $result_teacher = mysqli_query($conn, $sql_insert_teacher);

        // Get the ID of the newly inserted teacher
        $teacher_id = mysqli_insert_id($conn);

        // Insert corresponding user
        $sql_insert_user = "INSERT INTO users (username, password, role, teacher) VALUES ('$username', '$password', '$userRole', '$teacher_id')";
        $result_user = mysqli_query($conn, $sql_insert_user);

        if ($result_teacher && $result_user) {
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
