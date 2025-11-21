<?php

// Filename: account_api.php - Handles all Account Maintenance AJAX requests

require_once '../connection.php'; // Includes your SQLConnection class

header('Content-Type: application/json');

// Check for required action
if (!isset($_POST['action'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'No action specified.']);
    exit;
}

$action = $_POST['action'];

try {
    $db = new SQLConnection();
    $result = ['success' => false, 'message' => 'Invalid action.'];

    switch ($action) {
        case 'get_roles':
            // Corresponds to db.GetRoles()
            $roles = $db->GetRoles();
            if ($roles !== null) {
                $result = ['success' => true, 'data' => $roles];
            } else {
                throw new Exception('Failed to fetch roles from database.');
            }
            break;

        case 'get_accounts_by_status':
            // Corresponds to db.GetAccountsByStatus("Active" / "Archived")
            $status = $_POST['status'] ?? 'Active';
            $accounts = $db->GetAccountsByStatus($status);
            $result = ['success' => true, 'accounts' => $accounts];
            break;

        case 'check_username':
            // === NEW ACTION ADDED HERE ===
            if (empty($_POST['username'])) {
                throw new Exception('Username not provided for check.');
            }
            $username = $_POST['username'];
            // Corresponds to db.CheckUsernameExists(...)
            $exists = $db->CheckUsernameExists($username);
            // Return success and the boolean result
            $result = ['success' => true, 'exists' => $exists];
            break;
            // =============================

        case 'add_account':
            // Corresponds to db.AddAccount(...)
            $success = $db->AddAccount(
                $_POST['firstname'],
                $_POST['middlename'] ?? null,
                $_POST['surname'],
                (int) $_POST['roleId'],
                $_POST['username'],
                $_POST['password']
            );
            $result = ['success' => $success, 'message' => $success ? 'Account Added Successfully!' : 'Failed to add account.'];
            break;

        case 'update_account_status':
            // Corresponds to db.UpdateAccountStatus(accountId, newStatus)
            $success = $db->UpdateAccountStatus((int) $_POST['accountId'], $_POST['status']);
            $result = ['success' => $success, 'message' => $success ? 'Account Status Updated' : 'Failed to update status.'];
            break;

        default:
            http_response_code(400);
            $result = ['success' => false, 'error' => "Unknown action: $action"];
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    error_log("API Error: $action - ".$e->getMessage());
    $result = ['success' => false, 'error' => 'A server error occurred: '.$e->getMessage()];
}

echo json_encode($result);
