<?php
// bookappointment.php
header("Content-Type: text/html; charset=utf-8");

// Adjust path if your connection.php is in a different folder
require_once 'connection.php'; // contains $conn (mysqli) 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = trim($_POST['first_name'] ?? '');
    $last  = trim($_POST['last_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $service = trim($_POST['service'] ?? '');
    $date = trim($_POST['appointment_date'] ?? '');
    $time = trim($_POST['appointment_time'] ?? '');

    // Basic validation
    if (!$first || !$last || !$service || !$date || !$time) {
        $_SESSION['appt_error'] = "Please fill all required fields.";
        header("Location: bookappointment.html");
        exit;
    }

    // Prepare and insert
    $stmt = $conn->prepare("
        INSERT INTO appointment_tbl 
        (first_name, last_name, phone, email, service, appointment_date, appointment_time)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssssss", $first, $last, $phone, $email, $service, $date, $time);

    if ($stmt->execute()) {
        // Redirect back with success message (or show simple message)
        header("Location: bookappointment.html?success=1");
        exit;
    } else {
        // For debugging: echo $stmt->error;
        header("Location: bookappointment.html?error=1");
        exit;
    }
}
?>
