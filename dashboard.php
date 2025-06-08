<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Redirect to login page if not logged in
    header('Location: login.html');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MSB POS - Dashboard</title>
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
                    <a href="dashboard.html" class="block py-3 px-7 flex items-center gap-2 text-[#b3b3b3] hover:text-white nav-link">
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
            <div class="flex-1 p-10">
                <div class="flex justify-end mb-6">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                <a class="bg-[#2c2c2c] p-5 rounded-lg flex flex-col items-center" href="products.php">
                <img alt="Products icon" class="mb-3" height="100" src="products.png" width="100"/>
                <span>
                Products
                </span>
                </a>
                <a class="bg-[#2c2c2c] p-5 rounded-lg flex flex-col items-center" href="customers.php">
                <img alt="Customers icon" class="mb-3" height="100" src="customers.png" width="100"/>
                <span>
                Customers
                </span>
                </a>
                <a class="bg-[#2c2c2c] p-5 rounded-lg flex flex-col items-center" href="sales.php">
                <img alt="Sales icon" class="mb-3" height="100" src="sales.png" width="100"/>
                <span>
                Sales
                </span>
                </a>
                <a class="bg-[#2c2c2c] p-5 rounded-lg flex flex-col items-center" href="discount.php">
                <img alt="Discounts icon" class="mb-3" height="100" src="discounts.png" width="100"/>
                <span>
                Discounts
                </span>
                </a>
                <a class="bg-[#2c2c2c] p-5 rounded-lg flex flex-col items-center" href="employees.php">
                <img alt="Employees icon" class="mb-3" height="100" src="employees.png" width="100"/>
                <span>
                Employees
                </span>
                </a>
                <a class="bg-[#2c2c2c] p-5 rounded-lg flex flex-col items-center" href="add_invoice.php">
                <img alt="Invoice icon" class="mb-3" height="100" src="invoices.png" width="100"/>
                <span>
                Invoice
                </span>
                </a>
                <a class="bg-[#2c2c2c] p-5 rounded-lg flex flex-col items-center" href="report_generate.php">
                <img alt="Reports icon" class="mb-3" height="100" src="reports.png" width="100"/>
                <span>
                Reports
                </span>
                </a>
                </div>
                </div>
            </div>
        </div>

    <script>

    //drop down menu for logout
        document.getElementById('profileButton').addEventListener('click', function() {
            var dropdownMenu = document.getElementById('dropdownMenu');
            dropdownMenu.classList.toggle('hidden');
        });

        document.getElementById('logoutButton').addEventListener('click', function() {
            alert('Logging out...');
            // Add your logout logic here
        });
    </script>
</body>
</html>