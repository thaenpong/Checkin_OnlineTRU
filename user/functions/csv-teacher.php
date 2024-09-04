<?php
include "connect.php"; // Ensure your database connection is included

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['file'])) {
    try {
        $file = $_FILES['file']['tmp_name'];
        $handle = fopen($file, "r");

        // Skip the first row if it contains headers
        $header = fgetcsv($handle);

        if ($handle !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Sanitize each CSV row data
                $code = mysqli_real_escape_string($conn, $data[0]);
                $name = mysqli_real_escape_string($conn, $data[1]);
                $role = mysqli_real_escape_string($conn, $data[2]);
                $username = mysqli_real_escape_string($conn, $data[3]);
                $password = mysqli_real_escape_string($conn, $data[4]);
                $userRole = mysqli_real_escape_string($conn, $data[5]);

                // Insert new teacher
                $sql_insert_teacher = "INSERT INTO teachers (code, name, role) VALUES ('$code', '$name', '$role')";
                $result_teacher = mysqli_query($conn, $sql_insert_teacher);

                if (!$result_teacher) {
                    ob_clean();
                    header('Content-Type: application/json');
                    http_response_code(400);
                    echo json_encode(['message' => 'ไม่สามารถบันทึกข้อมูลครูได้']);
                    exit;
                }

                // Get the ID of the newly inserted teacher
                $teacher_id = mysqli_insert_id($conn);

                // Insert corresponding user
                $sql_insert_user = "INSERT INTO users (username, password, role, teacher) VALUES ('$username', '$password', '$userRole', '$teacher_id')";
                $result_user = mysqli_query($conn, $sql_insert_user);

                if (!$result_user) {
                    ob_clean();
                    header('Content-Type: application/json');
                    http_response_code(400);
                    echo json_encode(['message' => 'ไม่สามารถบันทึกข้อมูลผู้ใช้ได้']);
                    exit;
                }
            }

            ob_clean();
            fclose($handle);
            header('Content-Type: application/json');
            echo json_encode(['success' => 'บันทึกสำเร็จ']);
        } else {
            ob_clean();
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'ไม่สามารถอ่านไฟล์ CSV']);
        }
    } catch (Exception $e) {
        ob_clean();
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['message' => "เกิดข้อผิดพลาด: " . $e->getMessage()]);
    }
}
