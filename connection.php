<?php

$serverName = "DILEE_CONVOLP\SQLEXPRESS";
$database = "MSB_POS";
$uid ="";
$pass ="";

$connection = [
    "Database" => $database,
    "Uid" => $uid,
    "PWD"=> $pass

];

$conn = sqlsrv_connect($serverName, $connection);
if (!$conn) 
 die (print_r(sqlsrv_errors(), true));
else
echo"Connection established";


?>