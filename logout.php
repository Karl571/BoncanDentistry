<?php

// Filename: logout.php

// Start the session (required to access session data)
session_start();

// Unset all of the session variables
$_SESSION = [];

// Destroy the session cookie
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}

// Finally, destroy the session
session_destroy();

// Redirect back to the login page
header('Location: index.php');
exit;
