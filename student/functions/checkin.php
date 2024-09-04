<?php
include "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Ensure all required fields are present and sanitize inputs
        $session = $_POST['session_id'];
        $student = $_POST['student_id'];

        // Check if session is ended
        $sql_check_session = "SELECT end_time FROM sessions WHERE id = '$session' AND end_time <= NOW()";
        $result_check_session = mysqli_query($conn, $sql_check_session);

        if (mysqli_num_rows($result_check_session) > 0) {
            // Session is ended, return error
            ob_clean();
            http_response_code(400);
            echo json_encode(['message' => 'ไม่สามารถเช็คชื่อได้ เนื่องจากเวลาเรียนได้สิ้นสุดแล้ว']);
            exit; // Stop further execution
        }

        // Insert new check-in record
        $sql_insert_checkin = "INSERT INTO checkin (sessions_id, student_id) VALUES ('$session', '$student')";
        $result_checkin = mysqli_query($conn, $sql_insert_checkin);

        if ($result_checkin) {
            // Return JSON response
            ob_clean();
            header('Content-Type: application/json');
            echo json_encode(['success' => 'บันทึกสำเร็จ']);
        } else {
            // Error inserting check-in record
            ob_clean();
            http_response_code(400);
            echo json_encode(['message' => 'ไม่สามารถบันทึกข้อมูลได้']);
        }

        // Close database connection
        mysqli_close($conn);
    } catch (Exception $e) {
        // Exception handling
        ob_clean();
        http_response_code(400);
        echo json_encode(['message' => "เกิดข้อผิดพลาด: " . $e->getMessage()]);
    }
}
