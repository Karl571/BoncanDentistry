<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "boncan_db";  // your database name

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$firstName = $_POST['first_name'];
$lastName  = $_POST['last_name'];
$phone     = $_POST['phone'];
$email     = $_POST['email'];
$service   = $_POST['service'];
$apptDate  = $_POST['appointment_date'];
$apptTime  = $_POST['appointment_time'];

$sql = "INSERT INTO appointment_tbl 
        (first_name, last_name, phone, email, service, appointment_date, appointment_time, status)
        VALUES
        ('$firstName', '$lastName', '$phone', '$email', '$service', '$apptDate', '$apptTime', 'pending')";

if ($conn->query($sql) === TRUE) {
    echo "
        <script>
            alert('Appointment Successfully Submitted!');
            window.location.href = 'index.php';
        </script>
    ";
} else {
    echo "
        <script>
            alert('Error submitting appointment.');
            window.location.href = 'bookappointment.html';
        </script>
    ";
}

$conn->close();
?>
