<?php
// Start the session
session_start();

// Check if the user is logged in (optional, based on your application logic)
// Replace this condition with your specific login validation if needed
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.html'); // Redirect to login page if not logged in
    exit;
}

// Redirect to customer.php
header('Location: customers.php');
exit; // Ensure no further code is executed
?>
