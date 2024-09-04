<?php
include "connect.php"; // Ensure your database connection is included

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['file'])) {
    try {
        $file = $_FILES['file']['tmp_name'];
        $handle = fopen($file, "r");
        
        fgetcsv($handle);

        if ($handle !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                // Sanitize each CSV row data (assuming fields are in a specific order)
                $code = mysqli_real_escape_string($conn, $data[0]);
                $id_card = mysqli_real_escape_string($conn, $data[1]);
                $name = mysqli_real_escape_string($conn, $data[2]);
                $branch = mysqli_real_escape_string($conn, $data[3]);
                $year = mysqli_real_escape_string($conn, $data[4]);

                // Insert into database
                $sql_insert_student = "INSERT INTO students (code, id_card, name, branch_id, year_id) VALUES ('$code', '$id_card', '$name', '$branch', '$year')";
                $result_student = mysqli_query($conn, $sql_insert_student);

                if (!$result_student) {
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

        mysqli_close($conn); // Close database connection
    } catch (Exception $e) {
        ob_clean();
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['message' => "เกิดข้อผิดพลาด: " . $e->getMessage()]);
    }
}
