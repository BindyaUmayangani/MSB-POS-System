<?php
// Database connection configuration
$serverName = "DILEE_CONVOLP\SQLEXPRESS"; // Replace with your server name
$connectionOptions = [
    "Database" => "MSB_POS", // Replace with your database name
    "TrustServerCertificate" => true,
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
    // Fetch all discounts
    $sql = "SELECT * FROM discount";
    $stmt = sqlsrv_query($conn, $sql);

    if ($stmt === false) {
        echo json_encode(["success" => false, "message" => "Failed to fetch discounts."]);
    } else {
        $discounts = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $discounts[] = $row;
        }
        echo json_encode($discounts);
    }
}elseif ($action === 'add') {
    // Add a new discount
    $input = json_decode(file_get_contents('php://input'), true);
    $sql = "INSERT INTO discount (discount_type, discount_rate) VALUES (?, ?)";
    $params = [$input['discount_type'], $input['discount_rate']];
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        $errors = sqlsrv_errors();
        echo json_encode(["success" => false, "message" => "Failed to insert discount", "errors" => $errors]);
    } else {
        echo json_encode(["success" => true]);
    }


} elseif ($action === 'delete') {
    // Delete a discount
    $discountID = $_GET['id'] ?? 0;
    $sql = "DELETE FROM discount WHERE discountID = ?";
    $stmt = sqlsrv_query($conn, $sql, [$discountID]);

    echo json_encode(["success" => $stmt !== false]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid action."]);
}

// Close the connection
sqlsrv_close($conn);
?>



