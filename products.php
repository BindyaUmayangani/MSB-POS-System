<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Redirect to login page if not logged in
    header('Location: login.html');
    exit;
}

$serverName = "DILEE_CONVOLP\SQLEXPRESS";
$connectionInfo = ["Database" => "MSB_POS"];
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product_id'])) { 
    $deleteProductId = $_POST['delete_product_id']; 
    $deleteSql = "DELETE FROM Product WHERE ProductID = ?"; 
    $deleteStmt = sqlsrv_query($conn, $deleteSql, [$deleteProductId]); 
    
    if ($deleteStmt === false) { 
        die(print_r(sqlsrv_errors(), true)); } 
    }

// Fetch products from the database
$sql = "SELECT ProductID, ProductName, Quantity, Price, Category FROM Product";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MSB POS - Products</title>
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

        .search-bar {
            display: flex;
            align-items: center;
            background-color: #333;
            border-radius: 25px;
            padding: 8px 15px;
            width: 300px;
        }

        .search-bar input {
            background: none;
            border: none;
            color: white;
            margin-left: 10px;
            width: 100%;
            font-family: 'Poppins', sans-serif;
        }

        .add-customer-btn {
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

        .customers-table {
            width: 100%;
            border-collapse: collapse;
        }

        .customers-table th {
            background-color: #333;
            padding: 15px;
            text-align: center;
            font-weight: normal;
        }

        .customers-table tr:nth-child(even) {
            background-color: #2a2a2a;
        }

        .customers-table td {
            padding: 15px;
            text-align: center; 
            vertical-align: middle; 
        }



        .action-icons {
            display: flex;
            justify-content: center; 
            align-items: center;
            gap: 10px;
        }



        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .pagination button {
            background-color: #333;
            border: none;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
        }

        .pagination button.active {
            background-color: #8e44ad;
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

        .modal.show {
            display: flex;
        }

        .hidden {
            display: none;
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
                    <a href="products.php" class="block py-3 px-7 flex items-center gap-2 text-[#b3b3b3] hover:text-white nav-link active-link">
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
        <div class="top-nav flex items-center space-x-4">
            <i class="far fa-bell text-white"></i>
            <button id="profileButton" class="w-10 h-10 rounded-full bg-gray-400 text-white flex items-center justify-center"><img alt="User profile picture" class="w-10 h-10 rounded-full" height="40" src="https://i.ibb.co/SBbNCJB/image.png" width="40" /></button>
            <div id="dropdownMenu" class="absolute right-2 mt-20 w-55 hidden">Logout Menu</div>
        </div>
        <div class="content-area">
            <div class="header">
                <h2 class="text-xl font-bold">Products</h2>
                <button class="add-customer-btn" onclick="window.location.href='addproduct.php'">+ Products</button>
            </div>
            <table class="customers-table">
                <thead>
                    <tr>
                        <th>PRODUCT ID</th>
                        <th>PRODUCT NAME</th>
                        <th>QUANTITY</th>
                        <th>PRICE</th>
                        <th>CATEGORY</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['ProductID']); ?></td>
                            <td><?= htmlspecialchars($row['ProductName']); ?></td>
                            <td><?= htmlspecialchars($row['Quantity']); ?></td>
                            <td><?= htmlspecialchars($row['Price']); ?></td>
                            <td><?= htmlspecialchars($row['Category']); ?></td>
                            <td class="action-icons">
                                <i class="fas fa-pen cursor-pointer text-blue-500" onclick="window.location.href='updateproducts.php?id=<?= $row['ProductID']; ?>'"></i>
                                <i class="fas fa-trash cursor-pointer text-red-500" onclick="showDeletePopup('<?= $row['ProductID']; ?>', '<?= htmlspecialchars($row['ProductName']); ?>', '<?= htmlspecialchars($row['Category']); ?>')"></i>
                                <i class="fa fa-eye cursor-pointer text-white-400" onclick="showViewPopup('<?= $row['ProductID']; ?>', '<?= htmlspecialchars($row['ProductName']); ?>', '<?= htmlspecialchars($row['Price']); ?>', '<?= htmlspecialchars($row['Category']); ?>')"></i>
                                </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="pagination">
                <button class="active">1</button>
                <button>2</button>
                <button>3</button>
            </div>
        </div>
    </div>

    <!-- Modal for View Product Details  -->
    <div id="viewModal" class="modal" style="display: none;">
        <div class="modal-content bg-black text-white rounded-lg shadow-lg p-6 w-[28rem]">
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center">
                    <i class="fas fa-cube text-lg"></i>
                    <span class="ml-2 font-semibold">PRODUCT DETAILS</span>
                </div>
                <i class="fas fa-times cursor-pointer" onclick="closeViewModal()"></i>
            </div>
            <hr class="border-gray-600 mb-4">
            <div class="space-y-4 flex flex-col ">
                <div class="flex w-full">
                    <span class="text-gray-400 w-1/3 text-left">Product ID:</span>
                    <span id="viewProductId" class="ml-2 w-2/3 text-left"></span>
                </div>
                <div class="flex w-full">
                    <span class="text-gray-400 w-1/3 text-left">Product Name:</span>
                    <span id="viewProductName" class="ml-2 w-2/3 text-left"></span>
                </div>
                <div class="flex w-full">
                    <span class="text-gray-400 w-1/3 text-left">Price:</span>
                    <span id="viewProductPrice" class="ml-2 w-2/3 text-left"></span>
                </div>
                <div class="flex w-full">
                    <span class="text-gray-400 w-1/3 text-left">Category:</span>
                    <span id="viewProductCategory" class="ml-2 w-2/3 text-left"></span>
                </div>
                </div>
        </div>
    </div>

    <form id="deleteForm" method="POST" style="display: none;"> 
        <input type="hidden" name="delete_product_id" id="delete_product_id"> 
    </form> 
    <!-- Modal for delete confirmation --> 
    <div id="deleteModal" class="modal" style="display: none;">
    <div class="modal-content bg-black text-white rounded-lg shadow-lg p-6 w-full max-w-md">
        <div class="flex items-center mb-4">
            <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
            <h2 class="text-red-500 font-bold text-lg">DELETE CONFIRMATION</h2>
        </div>
        <hr class="border-gray-600 mb-4">
        <p class="mb-4">Are you sure you want to delete this product?</p>
        <div class="mb-4">
            <div class="flex">
                <span class="text-gray-400 w-1/3">Product ID:</span>
                <span id="deleteProductId" class="w-2/3"></span>
            </div>
            <div class="flex">
                <span class="text-gray-400 w-1/3">Product Name:</span>
                <span id="deleteProductName" class="w-2/3"></span>
            </div>
            <div class="flex">
                <span class="text-gray-400 w-1/3">Category:</span>
                <span id="deleteProductCategory" class="w-2/3"></span>
            </div>
        </div>
        <div class="flex justify-end space-x-4 mb-4">
            <button class="bg-black text-white border border-white py-2 px-6 rounded-lg cancel-btn" onclick="closeModal()">Cancel</button>
            <button class="bg-red-500 text-white py-2 px-6 rounded-lg" onclick="confirmDelete()">Delete</button>
        </div>
    </div>

    <script>

     // Delete COnfirmation   
    let productIdToDelete;

    function showDeletePopup(productId, productName, productCategory) {
        productIdToDelete = productId;
        document.getElementById('deleteProductId').innerText = productId;
        document.getElementById('deleteProductName').innerText = productName;
        document.getElementById('deleteProductCategory').innerText = productCategory;
        document.getElementById('deleteModal').style.display = 'flex';
    }

    function closeModal() {
       document.getElementById('deleteModal').style.display = 'none';
    }

    function confirmDelete() {
        document.getElementById('delete_product_id').value = productIdToDelete;
        document.getElementById('deleteForm').submit();
        closeModal();
    }

    //View Product Details

    function showViewPopup(productId, productName, productPrice, productCategory) {
        document.getElementById('viewProductId').innerText = productId;
        document.getElementById('viewProductName').innerText = productName;
        document.getElementById('viewProductPrice').innerText = productPrice;
        document.getElementById('viewProductCategory').innerText = productCategory;
        document.getElementById('viewModal').style.display = 'flex';
    }

    function closeViewModal() {
        document.getElementById('viewModal').style.display = 'none';
    }

     </script>

</body>
</html>
