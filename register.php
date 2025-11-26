<?php
session_start();
require_once 'connection.php'; // Include your SQLConnection class

$db = new SQLConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
<<<<<<< HEAD

    $firstname  = trim($_POST['firstname']);
    $middlename = trim($_POST['middlename']); // optional
    $surname    = trim($_POST['surname']);
    $username   = trim($_POST['username']);
    $email      = trim($_POST['email']);
    $password   = trim($_POST['password']);

    // Validation — required fields
    if (empty($firstname) || empty($surname) || empty($username) || empty($email) || empty($password)) {
        echo "<script>alert('Firstname, Surname, Username, Email & Password are required.'); window.history.back();</script>";
=======
    $username = trim($_POST['username']);
    $email = trim($_POST['email']); // Optional: store in database if you want
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
>>>>>>> clurt
        exit;
    }

    // Check if username already exists
    if ($db->CheckUsernameExists($username)) {
        echo "<script>alert('Username already exists! Please choose another.'); window.history.back();</script>";
        exit;
    }

<<<<<<< HEAD
    // NOTE — Currently plaintext based on your setup
    $storedPassword = $password;

    // Save into DB (role_id = 22)
    $roleId = 23;

    // Insert account — assumes method matches param order:
    // AddAccount(firstname, middlename, surname, roleId, username, password, email)
    $added = $db->AddAccount($firstname, $middlename, $surname, $roleId, $username, $storedPassword, $email);

    if ($added) {
        echo "<script>
            alert('Registration Successful!');
            window.location.href = 'login.html';
=======
    // Use plaintext password to match current validateUser
    $storedPassword = $password;

    // Insert into database (using role_id = 1 for patient/user)
    $roleId =   21;
   // $firstname = ""
    $added = $db->AddAccount("N/A", null, "N/A", $roleId, $username, $storedPassword);

    if ($added) {
        // Registration success alert
        echo "<script>
            alert('Registration Successful!');
            window.location.href = 'login.html'; // Redirect to login page
>>>>>>> clurt
        </script>";
        exit;
    } else {
        echo "<script>alert('Registration failed. Please try again.'); window.history.back();</script>";
        exit;
    }
<<<<<<< HEAD

} else {
    header("Location: register.html");
    exit;
}
?>
=======
} else {
    // If accessed directly without POST
    header("Location: register.html");
    exit;
}
?>
>>>>>>> clurt
