<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "boncan_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Appointments</title>

<style>
  /* RESET */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #075550;
    height: 100vh;
    overflow: hidden;
}

/* MAIN LAYOUT */
.dashboard-container {
    display: flex;
    width: 100vw;
    height: 100vh;
}

.sidebar {
    width: 260px;
    background: #073b36;
    color: white;
    padding: 30px 20px;
    display: flex;
    flex-direction: column;
    justify-content: flex-start; /* moves content to top */
}
.logout {
    margin-top: auto;
}



.profile {
    text-align: center;
    margin-bottom: 40px;
}

.avatar {
    font-size: 35px;
    background: #fff;
    color: #073b36;
    height: 55px;
    width: 55px;
    border-radius: 50%;
    margin: auto;
    display: flex;
    align-items: center;
    justify-content: center;
}

.menu a {
    display: block;
    margin: 18px 0;
    text-decoration: none;
    color: white;
    font-size: 16px;
    border-bottom: 1px solid rgba(255,255,255,0.2);
    padding-bottom: 10px;
}

.menu a:hover {
    color: #59d3bd;
}

.logout {
    color: #ffb3b3;
    text-decoration: none;
    font-weight: bold;
}

/* MAIN CONTENT */
.main-content {
    flex: 1;
    background: white;
    padding: 50px;
    overflow-y: auto;
}

.main-content h1 {
    font-size: 25px;
    margin-bottom: 8px;
}

.main-content p {
    color: #444;
    margin-bottom: 20px;
}

/* CARD GRID */
.cards {
    display: flex;
    gap: 25px;
}

/* CARD STYLES */
.card {
    background: #f1f1f1;
    padding: 22px;
    border-radius: 12px;
    width: 30%;
}

.card h3 {
    margin-bottom: 20px;
}

button {
    margin-top: 15px;
    padding: 9px 15px;
    border: none;
    background: #0b5a53;
    color: white;
    cursor: pointer;
    border-radius: 6px;
}

button:hover {
    background: #097065;
}
</style>
</head>
<body>

<div class="dashboard-container">

    <aside class="sidebar">
        <div class="profile"><h3>shene</h3></div>
        <nav class="menu">
            <a href="patient_dashboard.php">Home</a>
            <a href="appointment.php" class="active">Appointment</a>
            <a href="patient_record.php">Patient Records</a>
            <a href="service.php">List Of Services</a>
            <a href="transaction_history.php">Transaction History</a>
            <a href="profile.php">Profile / Account Settings</a>
        </nav>
        <a href="../logout.php" class="logout">Log Out</a>
    </aside>

    <main class="main-content">

        <h1>Your Appointments</h1>
        <p>Manage your scheduled dental visits below.</p>

        <div class="appointment-list">

        <?php
        $sql = "SELECT * FROM appointment_tbl ORDER BY appointment_date ASC";
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
