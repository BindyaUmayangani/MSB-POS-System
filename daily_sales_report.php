<?php
require('fpdf/fpdf.php'); // Include the FPDF library

// Database connection
$serverName = "DILEE_CONVOLP\SQLEXPRESS";
$connectionOptions = [
    "Database" => "MSB_POS"
];
$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    die(json_encode(["error" => "Database connection failed.", "details" => sqlsrv_errors()], JSON_PRETTY_PRINT));
}

try {
    // Fetch sales data for today
    $salesQuery = "SELECT SalesID, SalesDate, TotalAmount, CashierID, CustomerID, Discount 
                   FROM Sales
                   WHERE CAST(SalesDate AS DATE) = CAST(GETDATE() AS DATE)";
    $salesStmt = sqlsrv_query($conn, $salesQuery);

    if ($salesStmt === false) {
        throw new Exception("Error fetching sales data.");
    }

    $sales = [];
    $totalRevenue = 0;
    $totalDiscount = 0;
    $totalSalesCount = 0;

    while ($row = sqlsrv_fetch_array($salesStmt, SQLSRV_FETCH_ASSOC)) {
        $sales[] = $row;
        $totalRevenue += $row['TotalAmount'];
        $totalDiscount += $row['Discount'];
        $totalSalesCount++;
    }

    if (empty($sales)) {
        die(json_encode(["message" => "No sales data found for today."], JSON_PRETTY_PRINT));
    }

    // Create the PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // Report Title
    $pdf->Cell(0, 10, 'MSB POS - Daily Sales Report', 0, 1, 'C');
    $pdf->Ln(10);

    // Summary Section
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, "Date: " . date('Y-m-d'), 0, 1);
    $pdf->Cell(0, 10, "Total Revenue: Rs. " . number_format($totalRevenue, 2), 0, 1);
    $pdf->Cell(0, 10, "Total Discount: Rs. " . number_format($totalDiscount, 2), 0, 1);
    $pdf->Cell(0, 10, "Total Sales Count: $totalSalesCount", 0, 1);
    $pdf->Ln(10);

    // Sales Table Header
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(20, 10, 'Sale ID', 1);
    $pdf->Cell(40, 10, 'Sales Date', 1);
    $pdf->Cell(40, 10, 'Total Amount', 1);
    $pdf->Cell(25, 10, 'Cashier ID', 1);
    $pdf->Cell(25, 10, 'Customer ID', 1);
    $pdf->Cell(25, 10, 'Discount', 1);
    $pdf->Ln();

    // Sales Table Data
    $pdf->SetFont('Arial', '', 12);
    foreach ($sales as $sale) {
        $pdf->Cell(20, 10, $sale['SalesID'], 1);
        $pdf->Cell(40, 10, $sale['SalesDate']->format('Y-m-d H:i:s'), 1);
        $pdf->Cell(40, 10, number_format($sale['TotalAmount'], 2), 1);
        $pdf->Cell(25, 10, $sale['CashierID'], 1);
        $pdf->Cell(25, 10, $sale['CustomerID'], 1);
        $pdf->Cell(25, 10, number_format($sale['Discount'], 2), 1);
        $pdf->Ln();
    }

    // Output the PDF
    $fileName = 'Daily_Sales_Report_' . date('Y-m-d') . '.pdf';
    $pdf->Output('D', $fileName); // 'D' forces download
    exit;
} catch (Exception $e) {
    die(json_encode(["error" => $e->getMessage()], JSON_PRETTY_PRINT));
}
?>
