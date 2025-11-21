<?php

// Filename: role_api.php - Handles Role Maintenance AJAX requests

// The connection.php file is located in the project root. Use the correct relative path.
require_once 'connection.php';

header('Content-Type: application/json');

// --- Output Buffering for Clean JSON Response (CRITICAL) ---
ob_start();

if (!isset($_POST['action'])) {
    http_response_code(400);
    ob_clean();
    echo json_encode(['success' => false, 'error' => 'No action specified.']);
    exit;
}

$action = $_POST['action'];

try {
    $db = new SQLConnection();
    $result = ['success' => false, 'message' => 'Invalid action.'];

    switch ($action) {
        case 'get_modules':
            // Corresponds to LoadModules()
            $modules = $db->GetModules();
            if ($modules !== null) {
                $result = ['success' => true, 'data' => $modules];
            } else {
                throw new Exception('Failed to fetch modules from database.');
            }
            break;

        case 'save_role':
            // Corresponds to btnSave_Click_1()
            $roleName = $_POST['roleName'] ?? '';
            $moduleIds = isset($_POST['moduleIds']) ? json_decode($_POST['moduleIds'], true) : [];

            if (empty($roleName) || empty($moduleIds)) {
                $result = ['success' => false, 'message' => 'Role name and modules are required.'];
            } else {
                $success = $db->AddRoleAndAccess($roleName, $moduleIds);
                $result = ['success' => $success, 'message' => $success ? 'Role and access saved successfully!' : 'Failed to save role and access to the database.'];
            }
            break;

        default:
            http_response_code(400);
            $result = ['success' => false, 'error' => "Unknown action: $action"];
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    error_log("API Error: $action - ".$e->getMessage());
    $result = ['success' => false, 'error' => 'Server Error: '.$e->getMessage()];
}

ob_clean();
echo json_encode($result);
ob_end_flush();
