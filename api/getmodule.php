<?php

// getmodule.php - This is the file accessed by script.js

// 1. Include the database connection file
require_once '../connection.php';

header('Content-Type: application/json');

// Check if role_id is provided via POST
if (!isset($_POST['role_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Role ID not provided.']);
    exit;
}

$role_id = (int) $_POST['role_id'];

try {
    // 2. Instantiate the connection class
    $db = new SQLConnection();

    // 3. Call the dedicated method to get the modules
    $modules = $db->getModulesForRole($role_id);

    if ($modules !== null) {
        // Success: modules is an array of objects
        echo json_encode(['success' => true, 'modules' => $modules]);
    } else {
        // Covers cases where connection fails or query execution errors (returns null)
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Error fetching modules from database. Check server logs.']);
    }
} catch (Exception $e) {
    // Catches exceptions like a PDO connection failure from connection.php
    http_response_code(500);
    error_log('Module fetching error: '.$e->getMessage());
    echo json_encode(['success' => false, 'error' => 'An unexpected server error occurred.']);
}
