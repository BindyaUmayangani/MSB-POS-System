<?php
$serverName = "DILEE_CONVOLP\SQLEXPRESS";
$username = ""; ///daganndooo methnata OR blank thiynna 
$password = ""; /////daganndooo methnata  OR blank thiynna 
$dbName = "MSB_POS";

$conn = sqlsrv_connect($serverName, array(
    "Database" => $dbName,
    "UID" => $username,
    "PWD" => $password,
    "TrustServerCertificate" => true
));

if ($conn === false) {
    die(json_encode(array(
        "success" => false,
        "error" => sqlsrv_errors()
    )));
}

$sqlCreateDB = "IF NOT EXISTS (SELECT name FROM sys.databases WHERE name = ?) 
                CREATE DATABASE ?";
$params = array($dbName, $dbName);
$stmt = sqlsrv_query($conn, $sqlCreateDB, $params);

if ($stmt === false) {
    die(json_encode(array(
        "success" => false,
        "error" => sqlsrv_errors()
    )));
}

echo "Database '$dbName' created or already exists.\n";

sqlsrv_query($conn, "USE $dbName");

$sqlCreateTable = "IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='Sales' AND xtype='U') 
                   CREATE TABLE Sales (
                       SalesID INT IDENTITY(1,1) PRIMARY KEY,
                       SalesDate DATETIME NOT NULL,
                       TotalAmount DECIMAL(10, 2) NOT NULL,
                       CashierID INT NOT NULL,
                       CustomerID INT NOT NULL,
                       Discount DECIMAL(10, 2) NOT NULL
                   )";

$stmt = sqlsrv_query($conn, $sqlCreateTable);

if ($stmt === false) {
    die(json_encode(array(
        "success" => false,
        "error" => sqlsrv_errors()
    )));
}

echo "Table 'Sales' created successfully.\n";

$sqlInsertData = "INSERT INTO Sales (SalesDate, TotalAmount, CashierID, CustomerID, Discount) 
                  VALUES 
                  ('2025-01-03 10:00:00', 150.00, 1, 101, 10),
                  ('2025-01-04 11:30:00', 200.00, 2, 102, 5),
                  ('2025-01-05 14:00:00', 300.00, 1, 103, 20)";

$stmt = sqlsrv_query($conn, $sqlInsertData);

if ($stmt === false) {
    die(json_encode(array(
        "success" => false,
        "error" => sqlsrv_errors()
    )));
}

echo "Sample data inserted into 'Sales' table.\n";

sqlsrv_close($conn);
?>
