<?php

include('config.php');

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

$response = array();

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 7;
$offset = ($page - 1) * $limit;

$conn = getDbConnection($connectionOptions);

$dbName = "MSB_POS";

$sqlCheckDB = "IF NOT EXISTS (SELECT name FROM sys.databases WHERE name = 'MSB_POS') 
               CREATE DATABASE [$dbName]";
$params = array($dbName);
$stmt = sqlsrv_query($conn, $sqlCheckDB, $params);

if ($stmt === false) {
    $response['success'] = false;
    $response['error'] = sqlsrv_errors();
    echo json_encode($response);
    exit();
}

sqlsrv_close($conn);

$connectionOptions["Database"] = $dbName;
$conn = getDbConnection($connectionOptions);

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
    $response['success'] = false;
    $response['error'] = sqlsrv_errors();
    echo json_encode($response);
    exit();
}

$sqlCount = "SELECT COUNT(*) AS totalCount FROM Sales";
$stmtCount = sqlsrv_query($conn, $sqlCount);
$rowCount = sqlsrv_fetch_array($stmtCount, SQLSRV_FETCH_ASSOC);
$totalCount = $rowCount['totalCount'];

$sql = "SELECT SalesID, SalesDate, TotalAmount, CashierID, CustomerID, Discount 
        FROM Sales
        ORDER BY SalesID
        OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";
$params = array($offset, $limit);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt !== false) {
    $salesData = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $salesData[] = $row;
    }

    $totalPages = ceil($totalCount / $limit);

    $response['success'] = true;
    $response['data'] = $salesData;
    $response['totalPages'] = $totalPages;
    $response['currentPage'] = $page;
    echo json_encode($response);
} else {
    $response['success'] = false;
    $response['error'] = sqlsrv_errors();
    echo json_encode($response);
}

sqlsrv_free_stmt($stmt);
sqlsrv_free_stmt($stmtCount);
sqlsrv_close($conn);
?>
