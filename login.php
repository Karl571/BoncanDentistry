<?php

// 1. Include the necessary class file
// This assumes the connection.php file is correctly configured for MySQL and contains the SQLConnection class.
require_once 'connection.php';

// Set the header to indicate we are returning JSON data
header('Content-Type: application/json');

// 2. Check for required POST data
if (empty($_POST['username']) || empty($_POST['password'])) {
    http_response_code(400); // Bad Request
    echo json_encode([
        'success' => false,
        'message' => 'Please enter both username and password.',
    ]);
    exit;
}

// 3. Get and sanitize input
$username = trim($_POST['username']);
$password = trim($_POST['password']);

try {
    // 4. Instantiate the SQLConnection class
    $db = new SQLConnection();

    // 5. Call the ValidateUser method (mimics C# db.ValidateUser)
    $userDetails = $db->validateUser($username, $password);

    if ($userDetails !== null) {
        // Login Successful

        // In a real web app, you would start a session here
        session_start();
        $_SESSION['user_id'] = $username; // Use something unique like a user ID
        $_SESSION['full_name'] = $userDetails['FullName'];
        $_SESSION['role_id'] = $userDetails['RoleId'];
        $_SESSION['role_name'] = $userDetails['RoleName'];

        // 6. Return success and user details
        echo json_encode([
            'success' => true,
            'message' => 'Login Successful!',
            'user' => [
                'FullName' => $userDetails['FullName'],
                'RoleId' => $userDetails['RoleId'],
                'RoleName' => $userDetails['RoleName'],
            ],
        ]);
    } else {
        // Invalid credentials or inactive account
        http_response_code(401); // Unauthorized
        echo json_encode([
            'success' => false,
            'message' => 'Invalid username or password, or account is inactive.',
        ]);
    }
} catch (Exception $e) {
    // Application or Database error (mimics C# catch block)
    http_response_code(500); // Internal Server Error
    error_log('Login application error: '.$e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'An application error occurred. Please try again later.',
    ]);
}
