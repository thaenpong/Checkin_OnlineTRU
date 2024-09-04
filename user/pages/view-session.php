<?php
$id = $_GET["id"];

// Query to get end_time
$sql = "SELECT sessions.*,
subjects.name AS subjectName
FROM sessions 
INNER JOIN subjects ON subjects.id = sessions.subject_id
WHERE sessions.id = '$id'";

$qr = mysqli_query($conn, $sql);
$rs = mysqli_fetch_assoc($qr);

if ($rs) {
    // Get the end time from the query result
    $end_time = $rs['end_time'];

    // Get the current server hostname
    $hostname = gethostname();

    // Get the server IP address using the hostname
    $server_ip = gethostbyname($hostname);
    //$server_ip = '9692-118-172-159-84.ngrok-free.app';

    // Construct the URL
    $url = "http://$server_ip/Checkin_OnlineTRU/student/main.php?page=checkin&id=$id";
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <style>
            .container {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                height: 100vh;
                text-align: center;
            }

            #qrcode {
                margin-bottom: 20px;
            }

            #countdown {
                font-size: 20px;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="mt-3">
                <h1 class="text-center">Qr code สำหรับเช็คชื่อเข้าเรียน</h1>
            </div>
            <div class="mb-3">
                <h2 class="text-center">วิชา <?= $rs["subjectName"] ?></h2>
            </div>
            <div id="qrcode"></div>
            <div id="countdown"></div>
        </div>

        <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
        <script>
            // Generate QR code with the URL
            new QRCode(document.getElementById("qrcode"), "<?= $url ?>");

            // Function to update the countdown timer
            function updateCountdown(endTime) {
                const countdownElement = document.getElementById("countdown");
                const endTimeDate = new Date(endTime).getTime();
                const now = new Date().getTime();
                const distance = endTimeDate - now;

                if (distance < 0) {
                    countdownElement.innerHTML = "หมดเวลาเวลาเช็คชื่อ";
                    countdownElement.style.color = "red"; // Set the text color to red
                    return;
                }

                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                countdownElement.innerHTML = minutes + "นาที " + seconds + "วินาที";

                // Update the countdown every second
                setTimeout(updateCountdown, 1000, endTime);
            }

            // Start the countdown timer
            updateCountdown("<?= $end_time ?>");
        </script>
    </body>

    </html>
<?php
}
?>