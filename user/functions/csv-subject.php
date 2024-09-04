<?php
include "connect.php"; // Ensure your database connection is included

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['file'])) {
    try {
        $file = $_FILES['file']['tmp_name'];
        $handle = fopen($file, "r");

        // Skip the first row if it contains headers
        fgetcsv($handle);

        if ($handle !== false) {

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $code = mysqli_real_escape_string($conn, $data[0]);
                $name = mysqli_real_escape_string($conn, $data[1]);
                $branch = mysqli_real_escape_string($conn, $data[2]);

                $sql_insert_subject = "INSERT INTO subjects (code, name, branch_id) VALUES ('$code', '$name', '$branch')";
                $result_subject = mysqli_query($conn, $sql_insert_subject);

                if (!$result_subject) {
                    ob_clean();
                    header('Content-Type: application/json');
                    http_response_code(400);
                    echo json_encode(['message' => 'ไม่สามารถบันทึกข้อมูลได้']);
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
