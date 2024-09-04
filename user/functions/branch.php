<?php

include "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Ensure all required fields are present and sanitize inputs
        $name = $_POST['name'];
        // Validate inputs (e.g., ensure fields are not empty if required, perform additional validation as needed)

        // Insert new teacher
        $sql_insert_branchs = "INSERT INTO branchs (name) VALUES ('$name')";
        $result_branchs = mysqli_query($conn, $sql_insert_branchs);

        if ($result_branchs) {

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
        $name = $_PATCH['name'];
        $id = $_PATCH['id'];

        // Validate inputs (e.g., ensure IDs are valid, fields are not empty if required)

        // Update subject info
        $sql_update_branch = "UPDATE branchs SET  name = '$name' WHERE id = $id";
        $result_branch = mysqli_query($conn, $sql_update_branch);

        if ($result_branch) {
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

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Get the input data (e.g., teacher ID) from the DELETE request
    parse_str(file_get_contents("php://input"), $input);

    // Check if teacher_id is set in the input data
    if (isset($input['id'])) {
        $id = intval($input['id']);

        // Prepare the SQL statement
        $sql = "UPDATE branchs SET is_active = 0 WHERE id = ?";

        // Create a prepared statement
        if ($stmt = $conn->prepare($sql)) {
            // Bind the teacher_id parameter to the prepared statement
            $stmt->bind_param('i', $id);

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
