<?php
session_start(); // Start the session

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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoiceID = $_POST['invoiceID'];
    $invoiceNumber = $_POST['invoiceNumber'];
    $invoiceDate = $_POST['invoiceDate'];
    $totalAmount = $_POST['totalAmount'];
    $discountAmount = $_POST['discountAmount'];
    $productID = $_POST['productID'];

    // Insert the invoice into the database
    $sql = "INSERT INTO Invoice (InvoiceID, InvoiceNumber, InvoiceDate, TotalAmount, DiscountAmount, ProductID) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $params = [$invoiceID, $invoiceNumber, $invoiceDate, $totalAmount, $discountAmount, $productID];

    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $sql = "SELECT * FROM Invoice ORDER BY InvoiceDate DESC";
    $invoices = sqlsrv_query($conn, $sql);
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MSB POS - Invoice</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #1a1a1a;
            color: #ffffff;
            display: flex;
        }

        .sidebar {
            width: 250px;
            background-color: #242424;
            padding: 20px;
            height: 100vh; /* Ensure it spans the full viewport height */
            position: fixed; /* Keep it fixed as you scroll */
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 40px;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li {
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            color: #888;
            cursor: pointer;
        }

        .sidebar-menu li i {
            margin-right: 10px;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            margin-left: 250px; /* Adjust for the width of the sidebar */
        }

        .top-nav {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 20px;
            background-color: #1a1a1c;
        }

        .top-nav i {
            font-size: 20px;
            margin-left: 20px;
            cursor: pointer;
        }

        .user-icon {
            width: 40px;
            height: 40px;
            background-color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 20px;
        }

        .user-icon i {
            color: #242424;
        }

        .content-area {
            flex: 1;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .add-invoice-btn {
            background-color: #8e44ad;
            color: white;
            border: none;
            padding: 5px 20px;
            border-radius: 20px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
	    height: 40px;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-table th {
            background-color: #333;
            padding: 15px;
            text-align: center;
            font-weight: normal;
        }

        .invoice-table tr:nth-child(even) {
            background-color: #2a2a2a;
        }

        .invoice-table td {
            padding: 15px;
        }

        .action-icons {
            display: flex;
            gap: 15px;
        }

        .action-icons i.fa-pen {
            color: #3498db;
        }

        .action-icons i.fa-trash {
            color: #e74c3c;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .pagination button {
            border: 1;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
        }

        hr {
            border: none;
            border-top: 1px solid #858585;
            margin: 0;
        }

        .nav-link {
            border-radius: 9px;
        }

        .colorButton {
            cursor: pointer;
        }

        .colorButton:hover {
            background-color: #9050a3;
        }

        #sidebar a:hover {
            background-color: #9050a3;
            color: white;
        }

        .active-link {
            background-color: #9050a3;
            color: white;
        }

        hr {
            border: none;
            border-top: 1px solid #85858580;
            margin: 0;
            width: calc(100% - px);
        }

        .hidden {
            display: none;
        }


        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(107, 106, 106, 0.25);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal1-content {
            background: #242424;
            padding: 20px;
            border-radius: 10px;
            width: 400px;
            color: white;
            position: relative;
        }

        .modal2-content {
            background: #242424;
            padding: 20px;
            border-radius: 10px;
            color: white;
            position: relative;
        }

        .modal3-content {
            background: #242424;
            padding: 20px;
            border-radius: 10px;
            width: 400px;
            color: white;
            position: relative;
        }

        .modal-content h2 {
            margin-bottom: 20px;
            font-size: 22px;
            text-align: center;
        }

        .modal-content form {
            display: flex;
            flex-direction: column;
        }

        .modal-content input {
            margin-bottom: 15px;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            background: #333;
            color: white;
        }

        .modal-content .btn-group {
            display: flex;
            justify-content: space-between;
        }

        .modal-content button {
            background: #8e44ad;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
        }

        .modal-content button.cancel-btn {
            background: #e74c3c;
        }

        .modal-content .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: transparent;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
        }

        .modal-content button.cancel-btn {
            background-color: #3d3d3d;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
        }


    </style>
</head>
<body>
    <div class="sidebar">
        <center>
            <h1 class="text-2xl font-bold mb-6 py-14">MSB POS</h1>
        </center>
        <nav id="sidebar">
            <ul>
                <li>
                    <a href="dashboard.php" class="block py-3 px-7 flex items-center gap-2 text-[#b3b3b3] hover:text-white nav-link">
                        <i class="fas fa-th-large"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="products.php" class="block py-3 px-7 flex items-center gap-2 text-[#b3b3b3] hover:text-white nav-link">
                        <i class="fas fa-cubes"></i> Products
                    </a>
                </li>
                <li>
                    <a href="customers.php" class="block py-3 px-7 flex items-center gap-2 text-[#b3b3b3] hover:text-white nav-link">
                        <i class="fas fa-users"></i> Customers
                    </a>
                </li>
                <li>
                    <a href="sales.php" class="block py-3 px-7 flex items-center gap-2 text-[#b3b3b3] hover:text-white nav-link">
                        <i class="fas fa-file-invoice-dollar"></i> Sales
                    </a>
                </li>
                <li>
                    <a href="discount.php" class="block py-3 px-7 flex items-center gap-2 text-[#b3b3b3] hover:text-white nav-link">
                        <i class="fas fa-tags"></i> Discount
                    </a>
                </li>
                <li>
                    <a href="employees.php" class="block py-3 px-7 flex items-center gap-2 text-[#b3b3b3] hover:text-white nav-link">
                        <i class="fas fa-users"></i> Employees
                    </a>
                </li>
                <li>
                    <a href="add_invoice.php" class="block py-3 px-7 flex items-center gap-2 text-[#b3b3b3] hover:text-white nav-link">
                        <i class="fas fa-file-invoice"></i> Products Invoice
                    </a>
                </li>
                <li>
                    <a href="report_generate.php" class="block py-3 px-7 flex items-center gap-2 text-[#b3b3b3] hover:text-white nav-link">
                        <i class="fas fa-file-alt"></i> Reports
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <div class="main-content">
	    <div class="relative">
            <div class="top-nav flex items-center space-x-4 relative">
                <i class="far fa-bell text-white"></i>
                    <button id="profileButton" class="w-10 h-10 rounded-full bg-gray-400 text-white flex items-center justify-center"><img alt="User profile picture" class="w-10 h-10 rounded-full" height="40" src="admin.jpg" width="40" /></button>
                        <div id="dropdownMenu" class="absolute right-0 mt-20 w-55  bg-[#EF4444]  rounded-lg shadow-lg hidden">
                            <div class="p-1">
                                <a href="log.html" id="logoutButton" class="block text-white hover:bg-[#f55863] p-0 rounded text-1xl">User Logout</a>
                            </div>
                        </div>
            </div>    
        </div>
        <hr>
        <div class="content-area">
            <div class="header">
		        <div class="flex items-center">
                    <h2 class="text-2xl mb-1 text-left">Invoice</h2>
		        </div>
                    <button class="add-invoice-btn">
                        <i class="fas fa-plus"></i> Sales Invoice
                    </button>
            </div>
        <table class="invoice-table w-full text-center text-[#b3b3b3]">
            <thead>
                <tr>
                    <th class="py-3 px-4">Invoice ID</th>
                    <th class="py-3 px-4">Sales ID</th>
                    <th class="py-3 px-4">Product ID</th>
                    <th class="py-3 px-4">Invoice Date</th>
                    <th class="py-3 px-4">Total Amount</th>
                    <th class="py-3 px-4">Discount Amount</th>
                </tr>
            </thead>
            <tbody id="invoiceTable">
                
             
            </tbody>
        </table>
                <div class="pagination">
                    <button class="w-10 h-10 flex items-center justify-center border border-gray-500 text-white hover:bg-[#2b71fc] pagination-button" data-page="prev">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="w-10 h-10 flex items-center justify-center border border-gray-500 text-white pagination-button" data-page="1">
                        1
                    </button>
                    <button class="w-10 h-10 flex items-center justify-center border border-gray-500 text-white pagination-button" data-page="2">
                        2
                    </button>
                    <button class="w-10 h-10 flex items-center justify-center border border-gray-500 text-white pagination-button" data-page="3">
                        3
                    </button>
                    <button class="w-10 h-10 flex items-center justify-center border border-gray-500 text-white hover:bg-[#2b71fc] pagination-button" data-page="next">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>


<!-- Add invoice Modal -->
<div class="modal" id="addInvoiceModal">
    <div class="modal1-content p-10" style="width: 800px;">
        <div class="flex items-center mb-5">
            <i class="fas fa-file-invoice text-white mr-3"></i>
            <h1 class="text-1xl">ADD NEW INVOICE</h1>
        </div>
        <hr class="mb-4">
        <form id="addInvoiceForm">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div class="mb-5">
                        <label for="invoiceId" class="block mb-2">INVOICE ID</label>
                        <input type="text" id="invoiceId" placeholder="Invoice ID" class="w-full p-2 bg-[#333333] text-white rounded-lg" required>
                    </div>
                    <div class="mb-5">
                        <label for="invoiceOn" class="block mb-2">INVOICE ON</label>
                        <input type="date" id="invoiceOn" class="w-full p-2 bg-[#333333] text-white rounded-lg" required>
                    </div>
                    <div class="mb-5">
                        <label for="discount" class="block mb-2">DISCOUNT</label>
                        <input type="number" id="discount" placeholder="0.00" class="w-full p-2 bg-[#333333] text-white rounded-lg" required>
                    </div>
                </div>
                <div>
                    <div class="mb-5">
                        <label for="invoiceNumber" class="block mb-2">INVOICE NUMBER</label>
                        <input type="text" id="invoiceNumber" placeholder="Invoice Number" class="w-full p-2 bg-[#333333] text-white rounded-lg" required>
                    </div>
                    <div class="mb-5">
                        <label for="totalAmount" class="block mb-2">TOTAL AMOUNT</label>
                        <input type="text" id="totalAmount" placeholder="Total Amount" class="w-full p-2 bg-[#333333] text-white rounded-lg" required>
                    </div>
                </div>
                <div>
                <div class="mb-5">
                    <label for="productId" class="block mb-2">PRODUCT ID</label>
                    <input type="text" id="productId" placeholder="Product ID" class="w-full p-2 bg-[#333333] text-white rounded-lg" required>
                </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-4">
                <button type="button" class="cancel-btn px-6 py-1 rounded-lg border border-[#5e5d5d] text-white hover:bg-[#3d3d3d] hover:text-[#a75beb]" onclick="toggleInvoiceModal(false)">Cancel</button>
                <button type="submit" class="bg-purple-600 hover:bg-[#a75beb] text-white px-6 py-1 rounded-lg">Save</button>
            </div>
        </form>
    </div>
</div>

    <script>
// JavaScript for Add Sales invoice modal Handling
    const addInvoiceBtn = document.querySelector('.add-invoice-btn');
    const addInvoiceModal = document.getElementById('addInvoiceModal');
    const addInvoiceForm = document.getElementById('addInvoiceForm');
    const invoiceTableBody = document.querySelector('.invoice-table tbody');

    function toggleInvoiceModal(show) {
        addInvoiceModal.style.display = show ? 'flex' : 'none';
    }

    addInvoiceBtn.addEventListener('click', () => toggleInvoiceModal(true));

    addInvoiceForm.addEventListener('submit', (event) => {
    event.preventDefault();

    const invoiceId = document.getElementById('invoiceId').value;
    const invoiceOn = document.getElementById('invoiceOn').value;
    const discount = parseFloat(document.getElementById('discount').value).toFixed(2);
    const invoiceNumber = document.getElementById('invoiceNumber').value;
    const totalAmount = parseFloat(document.getElementById('totalAmount').value).toFixed(2);
    const productId = document.getElementById('productId').value;

    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td class="py-3 px-4">${invoiceId}</td>
        <td class="py-3 px-4">${invoiceNumber}</td>
        <td class="py-3 px-4">${productId}</td>
        <td class="py-3 px-4">${invoiceOn}</td>
        <td class="py-3 px-4">${totalAmount}</td>
        <td class="py-3 px-4">${discount}</td>
    `;

    invoiceTableBody.appendChild(newRow);

    alert('Invoice added successfully!');

    addInvoiceForm.reset();
    toggleInvoiceModal(false);
});



        // Drop down menu for logout
        document.getElementById('profileButton').addEventListener('click', function() {
            var dropdownMenu = document.getElementById('dropdownMenu');
            dropdownMenu.classList.toggle('hidden');
        });

        document.getElementById('logoutButton').addEventListener('click', function() {
            alert('Logging out...');
            // Add your logout logic here
        });

    // JavaScript for Pagination Handling
        document.addEventListener('DOMContentLoaded', () => {
            const buttons = document.querySelectorAll('.pagination-button');
            let currentPage = 1;

            const updatePagination = (newPage) => {
                buttons.forEach(btn => btn.classList.remove('bg-purple-600'));
                document.querySelector(`.pagination-button[data-page="${newPage}"]`).classList.add('bg-purple-600');
                currentPage = newPage;
            };

            buttons.forEach(button => {
                button.addEventListener('click', () => {
                    const page = button.getAttribute('data-page');
                    if (page === 'prev' && currentPage > 1) {
                        updatePagination(currentPage - 1);
                    } else if (page === 'next' && currentPage < 5) {
                        updatePagination(currentPage + 1);
                    } else if (!isNaN(page)) {
                        updatePagination(parseInt(page));
                    }
                });
            });

            // Initialize the first page as active
            updatePagination(currentPage);
        });
    </script>
</body>
</html>