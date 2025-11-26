<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "boncan_db";

$conn = new mysqli($servername, $username, $password, $dbname);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Appointments</title>
<style>
/* your CSS here (keep same CSS as before) */
</style>
</head>

<body>

<div class="dashboard-container">

    <aside class="sidebar">
        <div class="profile">
            <h3>shene</h3>
        </div>
        <nav class="menu">
            <a href="patient_dashboard.html">Home</a>
            <a href="appointment.php" class="active">Appointment</a>
            <a href="patient_record.html">Patient Records</a>
            <a href="service.html">List Of Services</a>
            <a href="transaction_history.html">Transaction History</a>
            <a href="profile.html">Profile / Account Settings</a>
        </nav>
        <a href="logout.php" class="logout">Log Out</a>
    </aside>

    <main class="main-content">
        <h1>Your Appointments</h1>
        <p>Manage your scheduled dental visits below.</p>

        <div class="appointment-list">
        
        <?php
        $sql = "SELECT * FROM appointments ORDER BY appointment_date ASC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "
                <div class='card'>
                    <h3>{$row['service']}</h3>
                    <p><b>Date:</b> {$row['appointment_date']}</p>
                    <p><b>Time:</b> " . date("h:i A", strtotime($row['appointment_time'])) . "</p>
                    <p><b>Name:</b> {$row['first_name']} {$row['last_name']}</p>
                    <p><b>Status:</b> {$row['status']}</p>
                    <button>View Details</button>
                </div>
                ";
            }
        } else {
            echo "<p>No appointments found.</p>";
        }
        ?>

        </div>
    </main>

</div>

</body>
</html>
