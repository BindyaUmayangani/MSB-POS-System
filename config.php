<?php

$serverName = "DILEE_CONVOLP\SQLEXPRESS"; // Replace with your server name
$dbName = "MSB_POS"; // Database name
$username = ""; // Leave empty for integrated security
$password = ""; // Leave empty for integrated security


$connectionOptions = array(
    "TrustServerCertificate" => true,
    "Database" => $dbName, // Initial database
    "UID" => $username, // Use integrated security
    "PWD" => $password // Use integrated security
);


function getDbConnection($connectionOptions) {
    $conn = sqlsrv_connect($GLOBALS['serverName'], $connectionOptions);
    if ($conn === false) {
        die(json_encode(array(
            "success" => false,
            "error" => sqlsrv_errors()
        )));
    }
    return $conn;
}
?>
