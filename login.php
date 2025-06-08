<?php
session_start(); // Start the session

// Database connection configuration
$serverName = "DILEE_CONVOLP\SQLEXPRESS"; // Your server name
$connectionOptions = [
    "Database" => "MSB_POS",        // Your database name
    "TrustServerCertificate" => true // Enable TrustServerCertificate
];

// Connect to the database
$conn = sqlsrv_connect($serverName, $connectionOptions);

// Check connection
if ($conn === false) {
    die("Database connection failed: " . print_r(sqlsrv_errors(), true));
}

// Initialize error message
$error = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if (empty($username) || empty($password)) {
            $_SESSION['error'] = "Username and password are required!";
            header('Location: login.html');
            exit;
        } else {
            // Query to check user credentials
            $sql = "SELECT * FROM Users WHERE username = ? AND password = ?";
            $params = [$username, $password];
            
            $stmt = sqlsrv_query($conn, $sql, $params);

            if ($stmt === false) {
                die("Query failed: " . print_r(sqlsrv_errors(), true));
            }

            // Check if a user exists
            if (sqlsrv_fetch($stmt)) {
                // Login successful
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $username; // Store username in session
                header('Location: dashboard.php'); // Redirect to customers page
                exit;
            } else {
                // Login failed
                $_SESSION['error'] = "Invalid username or password!";
                header('Location: login.html');
                exit;
            }

            // Free the statement resource
            sqlsrv_free_stmt($stmt);
        }
    } else {
        $_SESSION['error'] = "Form data is missing!";
        header('Location: login.html');
        exit;
    }
}

// Close the database connection
sqlsrv_close($conn);
?>
