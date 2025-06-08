<?php
// Database connection configuration
$serverName = "DILEE_CONVOLP\SQLEXPRESS"; // Replace with your server name
$connectionOptions = [
    "Database" => "MSB_POS",        // Replace with your database name
    "TrustServerCertificate" => true
];

// Establish the connection
$conn = sqlsrv_connect($serverName, $connectionOptions);

// Check if connection was successful
if ($conn === false) {
    die(json_encode(["success" => false, "message" => "Database connection failed: " . print_r(sqlsrv_errors(), true)]));
}

// Determine the action to perform
$action = $_GET['action'] ?? '';

if ($action === 'fetch') {
    // Fetch all customers
    $sql = "SELECT * FROM Customers";
    $stmt = sqlsrv_query($conn, $sql);

    if ($stmt === false) {
        echo json_encode(["success" => false, "message" => "Failed to fetch customers."]);
    } else {
        $customers = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $customers[] = $row;
        }
        echo json_encode($customers);
    }
} elseif ($action === 'add') {
    // Add a new customer
    $input = json_decode(file_get_contents('php://input'), true);
    $sql = "INSERT INTO Customers (customer_name, contact_no, email) VALUES (?, ?, ?)";
    $params = [$input['customer_name'], $input['contact_no'], $input['email']];
    $stmt = sqlsrv_query($conn, $sql, $params);

    echo json_encode(["success" => $stmt !== false]);
} elseif ($action === 'edit') {
    // Edit an existing customer
    $input = json_decode(file_get_contents('php://input'), true);
    $sql = "UPDATE Customers SET customer_name = ?, contact_no = ?, email = ? WHERE customer_id = ?";
    $params = [$input['customer_name'], $input['contact_no'], $input['email'], $input['customer_id']];
    $stmt = sqlsrv_query($conn, $sql, $params);

    echo json_encode(["success" => $stmt !== false]);
} elseif ($action === 'delete') {
    // Delete a customer
    $customerId = $_GET['id'] ?? 0;
    $sql = "DELETE FROM Customers WHERE customer_id = ?";
    $stmt = sqlsrv_query($conn, $sql, [$customerId]);

    echo json_encode(["success" => $stmt !== false]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid action."]);
}

// Close the connection
sqlsrv_close($conn);
?>
