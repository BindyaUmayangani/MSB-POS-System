<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.html');
    exit;
}

$serverName = "DILEE_CONVOLP\SQLEXPRESS";
$connectionOptions = [
    "Database" => "MSB_POS"
];

$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Get form data
$productID = $_POST['productID'];
$productName = $_POST['productName'];
$quantity = $_POST['quantity'];
$price = $_POST['price'];
$category = $_POST['category'];

// Update product query
$sql = "UPDATE Product SET ProductName = ?, Quantity = ?, Price = ?, Category = ? WHERE ProductID = ?";
$params = array($productName, $quantity, $price, $category, $productID);

// Execute query
$stmt = sqlsrv_query($conn, $sql, $params);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

header('Location: products.php'); // Redirect to the products page after updating
exit;
?>