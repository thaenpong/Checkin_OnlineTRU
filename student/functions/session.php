<?php
include "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    try {
        $subject = $_GET["subject"];
        $year = $_GET["year"];
        $student_id = $_GET["student_id"];

        // Fetch sessions
        $sql_session = "SELECT * FROM sessions WHERE year_id = '$year' AND subject_id = '$subject'";
        $qr_session = mysqli_query($conn, $sql_session);


        $data = [];
        while ($row = mysqli_fetch_assoc($qr_session)) {
            // Format date and time
            $created_date = new DateTime($row["created_date"]);
            $formatted_date = $created_date->format('d-m-Y');

            $created_time = new DateTime($row["created_time"]);
            $formatted_time = $created_time->format('H:i');

            $sessionId = $row["id"];
            $sql_checkin = "SELECT * FROM checkin WHERE sessions_id = '$sessionId' AND student_id = '$student_id'";
            $qr_checkin = mysqli_query($conn, $sql_checkin);
            $rs_checkin = mysqli_fetch_assoc($qr_checkin);

            $formatted_time_checkin = "";
            if ($rs_checkin) {
                $checkin_time = new DateTime($rs_checkin["created_at"]);
                $formatted_time_checkin = $checkin_time->format('d-m-Y H:i');
            }

            // Collect data
            $data[] = [
                'session_id' => $sessionId,
                'formatted_date' => $formatted_date,
                'formatted_time' => $formatted_time,
                'formatted_time_checkin' => $formatted_time_checkin
            ];
        }

        // Return data as JSON
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode($data);
    } catch (Exception $e) {
        // Clean output buffer and return error message
        ob_clean();
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Error executing query: ' . $e->getMessage()]);
    }
}

