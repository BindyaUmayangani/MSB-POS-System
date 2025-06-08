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
    // Fetch all employees
    $sql = "SELECT * FROM Employees"; // Adjust table name as needed
    $stmt = sqlsrv_query($conn, $sql);

    if ($stmt === false) {
        echo json_encode(["success" => false, "message" => "Failed to fetch employees."]);
    } else {
        $employees = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $employees[] = $row;
        }
        echo json_encode($employees);
    }
} elseif ($action === 'add') {
    // Add a new employee
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate required fields
    if (empty($input['employee_name']) || empty($input['nic']) || empty($input['contact_no']) || empty($input['email'])) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    $sql = "INSERT INTO Employees (employee_name, nic, contact_no, email) VALUES (?, ?, ?, ?)";
    $params = [$input['employee_name'], $input['nic'], $input['contact_no'], $input['email']];
    $stmt = sqlsrv_query($conn, $sql, $params);

    echo json_encode(["success" => $stmt !== false]);
} elseif ($action === 'edit') {
    // Edit an existing employee
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate required fields
    if (empty($input['employee_id']) || empty($input['employee_name']) || empty($input['nic']) || empty($input['contact_no']) || empty($input['email'])) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    $sql = "UPDATE Employees SET employee_name = ?, nic = ?, contact_no = ?, email = ? WHERE employee_id = ?";
    $params = [$input['employee_name'], $input['nic'], $input['contact_no'], $input['email'], $input['employee_id']];
    $stmt = sqlsrv_query($conn, $sql, $params);

    echo json_encode(["success" => $stmt !== false]);
} elseif ($action === 'delete') {
    // Delete an employee
    $employeeId = $_GET['id'] ?? 0;

    if (!$employeeId) {
        echo json_encode(["success" => false, "message" => "Invalid employee ID."]);
        exit;
    }

    $sql = "DELETE FROM Employees WHERE employee_id = ?";
    $stmt = sqlsrv_query($conn, $sql, [$employeeId]);

    echo json_encode(["success" => $stmt !== false]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid action."]);
}

// Close the connection
sqlsrv_close($conn);
?>
