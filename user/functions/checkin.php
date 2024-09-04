<?php
include "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Ensure all required fields are present and sanitize inputs
        $session = $_POST['session_id'];
        $student = $_POST['student_id'];
        // Validate inputs (e.g., ensure fields are not empty if required, perform additional validation as needed)

        // Insert new teacher
        $sql_insert_checkin = "INSERT INTO checkin (sessions_id, student_id) VALUES ('$session', '$student')";
        $result_checkin = mysqli_query($conn, $sql_insert_checkin);

        if ($result_checkin) {
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


if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    try {
        // Parse the DELETE request body
        parse_str(file_get_contents("php://input"), $_DELETE);

        // Ensure all required fields are present and sanitize inputs
        if (!isset($_DELETE['id'])) {
            throw new Exception('Missing required parameter: id');
        }

        $id = intval($_DELETE['id']); // Sanitize input

        // Delete check-in record
        $sql_delete_checkin = "DELETE FROM checkin WHERE id = $id";
        $result_checkin = mysqli_query($conn, $sql_delete_checkin);

        if ($result_checkin) {
            // Return JSON response
            ob_clean();
            header('Content-Type: application/json');
            echo json_encode(['success' => 'บันทึกสำเร็จ', 'id' => $id]);
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

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Get the input data from the DELETE request
    parse_str(file_get_contents("php://input"), $input);

    // Check if both student_id and subject_id are set in the input data
    if (isset($input['student_id']) && isset($input['subject_id'])) {
        $student_id = intval($input['student_id']);
        $subject_id = intval($input['subject_id']);

        // Make sure the connection is established
        if ($conn) {
            // Start a transaction
            $conn->begin_transaction();

            try {
                // Get the register IDs of the rows to be deleted
                $register_ids = [];
                $sql_get_register_ids = "SELECT id FROM register WHERE student_id = ? AND subject_id = ?";
                $stmt_get_register_ids = $conn->prepare($sql_get_register_ids);
                $stmt_get_register_ids->bind_param('ii', $student_id, $subject_id);
                $stmt_get_register_ids->execute();
                $result = $stmt_get_register_ids->get_result();
                while ($row = $result->fetch_assoc()) {
                    $register_ids[] = $row['id'];
                }
                $stmt_get_register_ids->close();

                if (!empty($register_ids)) {
                    // Prepare the SQL statement to delete from register table
                    $sql_register = "DELETE FROM register WHERE student_id = ? AND subject_id = ?";
                    $stmt_register = $conn->prepare($sql_register);
                    $stmt_register->bind_param('ii', $student_id, $subject_id);

                    // Execute the statement
                    if ($stmt_register->execute()) {
                        // Prepare the SQL statement to delete from checkin table
                        $sql_checkin = "DELETE FROM checkin WHERE id IN (" . implode(',', $register_ids) . ") AND student_id = ?";
                        $stmt_checkin = $conn->prepare($sql_checkin);
                        $stmt_checkin->bind_param('i', $student_id);

                        // Execute the statement
                        if ($stmt_checkin->execute()) {
                            // Commit the transaction
                            $conn->commit();

                            // Send a success response
                            ob_clean();
                            http_response_code(200);
                            echo json_encode(['success' => true]);
                        } else {
                            throw new Exception('Unable to execute the checkin delete query.');
                        }

                        // Close the statement
                        $stmt_checkin->close();
                    } else {
                        throw new Exception('Unable to execute the register delete query.');
                    }

                    // Close the statement
                    $stmt_register->close();
                } else {
                    throw new Exception('No register entries found to delete.');
                }
            } catch (Exception $e) {
                // Rollback the transaction
                $conn->rollback();

                // Send an error response
                ob_clean();
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
        } else {
            // Send an error response if the connection is not established
            ob_clean();
            echo json_encode(['success' => false, 'error' => 'Database connection failed.']);
        }
    } else {
        // Send an error response if the required parameters are missing
        ob_clean();
        echo json_encode(['success' => false, 'error' => 'Student ID and Subject ID are required.']);
    }
}
