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

// Get the ProductID from the URL query string
$productID = isset($_GET['id']) ? $_GET['id'] : null;
if ($productID === null) {
    // Handle the case where no ID is provided (e.g., redirect back or show an error)
    die("Product ID is required.");
}

// Query to fetch product data based on ProductID
$sql = "SELECT * FROM Product WHERE ProductID = ?";
$params = array($productID);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Fetch the product data
$productData = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
if ($productData === false) {
    die("Product not found.");
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

    
    .widget-upload {
      width: 100px;
      height: 100px;
      background-color: #3d3d3d;
      border-radius: 8px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      margin-bottom: 30px;
      cursor: pointer;
    }

    .widget-upload i {
      font-size: 24px;
      margin-bottom: 5px;
    }

    .widget-upload img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 8px;
    }

    .form-group {
      display: flex;
      gap: 20px;
      margin-bottom: 20px;
    }

    .input-group {
      flex: 1;
    }

    .input-label {
      display: block;
      margin-bottom: 8px;
      color: #b3b3b3;
      font-weight: 300;
    }

    .form-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

    .form-row input, .form-row label {
        flex: 1;

     }

    .form-row label {
        text-align: left;
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
        <div class="top-nav flex items-center space-x-4">
            <i class="far fa-bell text-white"></i>
            <button id="profileButton" class="w-10 h-10 rounded-full bg-gray-400 text-white flex items-center justify-center"><img alt="User profile picture" class="w-10 h-10 rounded-full" height="40" src="https://i.ibb.co/SBbNCJB/image.png" width="40" /></button>
            <div id="dropdownMenu" class="absolute right-2 mt-20 w-55 hidden">Logout Menu</div>
        </div>

           <!-- Main Content -->
    <main class="flex-1 p-8 flex flex-col items-center justify-center">
      <div class="w-full max-w-4xl">
        <h2 class="text-2xl mb-9 text-left py-14" style="margin-bottom: 0px;">Update Product</h2>


        <!-- Form -->
        <form id="product-form" class="space-y-8" action="updateproduct_process.php" method="POST">
            
        <div class="form-row">
            <label for="product-id" class="text-sm mb-2">Product ID:</label>
            <input id="product-id" type="text" placeholder="Product ID" name="productID" class="w-full p-2 rounded-lg bg-[#1a1a1a;] text-[#b3b3b3] placeholder-[#b3b3b3]" value="<?= htmlspecialchars($productData['ProductID']); ?>" 
            style="margin-right: 718px; padding-left: 0px; padding-top:1px; padding-right:8px; padding-bottom:8px; color: white;" 
            readonly
            >
        </div>

          

          <!-- Product Information -->
          <div class="grid grid-cols-2 gap-6">
            
            <div>
              <label for="product-name" class="block text-sm mb-2">Product Name<span class="text-red-500">*</span></label>
              <input 
                id="product-name" 
                name="productName"
                type="text" 
                placeholder="Product Name" 
                class="w-full p-2 rounded-lg bg-[#3d3d3d] text-[#b3b3b3] placeholder-[#b3b3b3]"
                value="<?= htmlspecialchars($productData['ProductName']); ?>"
                required
              >
            </div>
            <div>
              <label for="quantity" class="block text-sm mb-2">Quantity</label>
              <input 
                id="quantity" 
                name="quantity"
                type="number" 
                placeholder="Quantity" 
                class="w-full p-2 rounded-lg bg-[#3d3d3d] text-[#b3b3b3] placeholder-[#b3b3b3]"
                value="<?= htmlspecialchars($productData['Quantity']); ?>"
                >
            </div>
            <div>
              <label for="price" class="block text-sm mb-2">Price<span class="text-red-500">*</span></label>
              <input 
                id="price" 
                name="price"
                type="number" 
                step="0.01" 
                placeholder="0.00" 
                class="w-full p-2 rounded-lg bg-[#3d3d3d] text-[#b3b3b3] placeholder-[#b3b3b3]"
                value="<?= htmlspecialchars($productData['Price']); ?>"
                required
              >
            </div>

            <div>
              <label for="category" class="block text-sm mb-2">Category<span class="text-red-500">*</span></label>
              <select 
                id="category" 
                name="category"
                class="w-full p-2 rounded-lg bg-[#3d3d3d] text-[#b3b3b3]">
                <option value="" disabled selected>Select Category</option>
                <option value="electronics"  <?= ($productData['Category'] == 'electronics') ? 'selected' : ''; ?>>Electronics</option>
                <option value="clothing"  <?= ($productData['Category'] == 'clothing') ? 'selected' : ''; ?>>Clothing</option>
                <option value="home-appliances"  <?= ($productData['Category'] == 'home-appliances') ? 'selected' : ''; ?>>Home Appliances</option>
                <option value="groceries"  <?= ($productData['Category'] == 'groceries') ? 'selected' : ''; ?>>Groceries</option>
                <option value="other"  <?= ($productData['Category'] == 'other') ? 'selected' : ''; ?>>Other</option>
              </select>
            </div>
          </div>
        </form>

        <!-- Buttons -->
        <div class="mt-12 flex justify-end gap-4">
        <a href="products.php">

          <button 
            class="px-6 py-1 rounded-full border border-[#9c27b0] text-[#9c27b0] hover:bg-[#9c27b0] hover:text-white" 
          >
            Cancel
          </button>
          </a>
          <button 
            class="px-7 py-1 rounded-full bg-[#9c27b0] text-white hover:bg-[#9c27b0]" 
            type="submit"
            form="product-form"

          >
            Save
          </button>
        </div>
      </div>
    </main>
  </div>

 
</body>
</html>