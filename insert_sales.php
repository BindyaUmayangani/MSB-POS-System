<?php

include 'config.php';


header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");


$response = array();


$conn = sqlsrv_connect($serverName, $connectionOptions);


if ($conn === false) {
    $response = array(
        "success" => false,
        "message" => "Connection failed",
        "error" => sqlsrv_errors()
    );
} else {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      
        $salesDate = $_POST['salesDate'] ?? null;
        $totalAmount = $_POST['totalAmount'] ?? null;
        $cashierId = $_POST['cashierId'] ?? null;
        $customerId = $_POST['customerId'] ?? null;
        $discount = $_POST['discount'] ?? null;

        
        $missingFields = [];
        if (!$salesDate) $missingFields[] = 'salesDate';
        if (!$totalAmount) $missingFields[] = 'totalAmount';
        if (!$cashierId) $missingFields[] = 'cashierId';
        if (!$customerId) $missingFields[] = 'customerId';
        if (!$discount) $missingFields[] = 'discount';

  
        if (count($missingFields) > 0) {
            $response = array(
                "success" => false,
                "message" => "The following fields are required: " . implode(', ', $missingFields)
            );
        } else {
       
            if (!is_numeric($totalAmount) || !is_numeric($discount)) {
                $response = array(
                    "success" => false,
                    "message" => "Total Amount and Discount must be numeric."
                );
            } elseif (!validateDate($salesDate)) {
                $response = array(
                    "success" => false,
                    "message" => "Sales Date is not in a valid format. Please use YYYY-MM-DD."
                );
            } else {
             
                $sql = "INSERT INTO Sales (SalesDate, TotalAmount, CashierID, CustomerID, Discount)
                        VALUES (?, ?, ?, ?, ?)";

                $stmt = sqlsrv_prepare($conn, $sql, array(&$salesDate, &$totalAmount, &$cashierId, &$customerId, &$discount));

                if ($stmt === false) {
            
                    $response = array(
                        "success" => false,
                        "message" => "Failed to prepare SQL query",
                        "error" => sqlsrv_errors()
                    );
                } else {

                    error_log("SQL Query: $sql"); 
                    error_log("Parameters: salesDate=$salesDate, totalAmount=$totalAmount, cashierId=$cashierId, customerId=$customerId, discount=$discount");
              
                    $result = sqlsrv_execute($stmt);

                    if ($result === false) {

                        $errors = sqlsrv_errors(); 
                        $errorMessages = []; 
                        foreach ($errors as $error) { 
                            $errorMessages[] = [ 
                                "SQLSTATE" => $error['SQLSTATE'], 
                                "Code" => $error['code'], 
                                "Message" => $error['message'] 
                            ]; 
                        }
        
                        $response = array(
                            "success" => false,
                            "message" => $errorMessages,
                            "error" => $errorMessages
                        );
                    } else {
                      
                        $response = array(
                            "success" => true,
                            "message" => "Record successfully inserted"
                        );
                    }

           
                    sqlsrv_free_stmt($stmt);
                }
            }
        }
    } else {
        $response = array(
            "success" => false,
            "message" => "Invalid request method. Only POST requests are allowed."
        );
    }
}


sqlsrv_close($conn);


echo json_encode($response);


function validateDate($date) {
    $format = 'Y-m-d';
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}
?>
