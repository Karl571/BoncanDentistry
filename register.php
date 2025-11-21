<?php
session_start();
require_once 'connection.php'; // Include your SQLConnection class

$db = new SQLConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']); // Optional: store in database if you want
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit;
    }

    // Check if username already exists
    if ($db->CheckUsernameExists($username)) {
        echo "<script>alert('Username already exists! Please choose another.'); window.history.back();</script>";
        exit;
    }

    // Use plaintext password to match current validateUser
    $storedPassword = $password;

    // Insert into database (using role_id = 1 for patient/user)
    $roleId =   22;
   // $firstname = ""
    $added = $db->AddAccount("N/A", null, "N/A", $roleId, $username, $storedPassword);

    if ($added) {
        // Registration success alert
        echo "<script>
            alert('Registration Successful!');
            window.location.href = 'login.html'; // Redirect to login page
        </script>";
        exit;
    } else {
        echo "<script>alert('Registration failed. Please try again.'); window.history.back();</script>";
        exit;
    }
} else {
    // If accessed directly without POST
    header("Location: register.html");
    exit;
}
?>
