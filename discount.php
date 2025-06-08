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
    <title>MSB POS - Customers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Add styles as needed */
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
                    <a href="discount.php" class="block py-3 px-7 flex items-center gap-2 text-[#b3b3b3] hover:text-white nav-link active-link">
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
                <div id="dropdownMenu" class="absolute right-2 mt-20 w-55  bg-[#EF4444]  rounded-lg shadow-lg hidden">
                    <div class="p-1">
                        <a href="logout.php" id="logoutButton" class="block text-white hover:bg-[#f55863] p-0 rounded text-1xl">Logout</a>
                    </div>
                </div>
        </div>
        <hr>
        <div class="content-area">
            <div class="header">
                <h2 class="text-2xl mb-1 text-left">Discount</h2>
                <button class="add-customer-btn" id="addDiscountBtn">
                    <i class="fas fa-plus"></i> Add Discount
                </button>
            </div>
            <table class="customers-table w-full text-center text-[#b3b3b3]">
                <thead>
                    <tr>
                        <th class="py-3 px-4">DISCOUNT ID</th>
                        <th class="py-3 px-4">DISCOUNT TYPE</th>
                        <th class="py-3 px-4">DISCOUNT RATE</th>
                        <th class="py-3 px-4"></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>

            </table>
            <div class="pagination">
                <button><i class="fas fa-chevron-left"></i></button>
                <button class="active">1</button>
                <button>2</button>
                <button>3</button>

                <button><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </div>

    
    <!-- Modal to Add Discount -->
    <div id="discountModal" class="hidden fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-75">
        <div class="bg-black text-white p-6 rounded-lg shadow-lg w-full max-w-md flex flex-col items-center">
            <div class="w-full">
                <div class="flex items-center mb-8">
                    <i class="fas fa-users text-xl mr-2"></i>
                    <h2 class="text-lg font-semibold">ADD NEW DISCOUNT</h2>
                </div>
                <hr class="border-gray-600 mb-4">
                <form id="addDiscountForm">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Discount Type</label>
                        <div class="flex items-center mt-2 space-x-8">
                            <div class="flex items-center">
                                <input type="radio" id="permanent" name="discountType" class="form-radio text-gray-800 h-4 w-4">
                                <label for="permanent" class="ml-2 text-sm">Permanent</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="temporary" name="discountType" class="form-radio text-gray-800 h-4 w-4">
                                <label for="temporary" class="ml-2 text-sm">Temporary</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="discountRate" class="block text-sm font-medium mb-2">Discount Rate</label>
                        <input type="text" id="discountRate" name="discountRate" placeholder="Discount Rate" class="w-full px-3 py-2 bg-gray-800 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>

                    <div class="flex justify-end space-x-4 mt-6">
                        <button type="button" class="w-24 px-4 py-2 bg-black text-white border border-white rounded-md hover:bg-gray-800" onclick="discountModal.classList.add('hidden')">Cancel</button>
                        <button type="submit" class="w-24 px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-500">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
<!-- Modal for delete confirmation -->
<div id="deleteModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
    <div class="bg-black text-white rounded-lg shadow-lg p-6 w-full max-w-md">
        <div class="flex items-center mb-4">
            <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
            <h2 class="text-red-500 font-semibold">DELETE CONFIRMATION</h2>
        </div>
        <hr class="border-gray-600 mb-4">
        <p class="mb-6">Are you sure you want to delete this Discount?</p>
        <div class="flex justify-end space-x-4">
            <button class="px-4 py-2 border border-gray-500 rounded text-white cancel-btn" onclick="closeModal()">Cancel</button>
            <button class="px-4 py-2 bg-red-500 text-white rounded" onclick="confirmDelete()">Delete</button>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', fetchDiscounts);


// Get modal and button elements
const discountModal = document.getElementById('discountModal');
const addDiscountBtn = document.getElementById('addDiscountBtn');
const deleteModal = document.getElementById('deleteModal');
let deleteDiscountId = null;

// Show modal when the "Add Discount" button is clicked
addDiscountBtn.addEventListener('click', function() {
    discountModal.classList.remove('hidden');
});

// Base API URL
const apiUrl = 'discount_api.php'; // Replace with your PHP API file path

// Fetch and display discounts
async function fetchDiscounts() {
    const response = await fetch(apiUrl + '?action=fetch');
    const discounts = await response.json();

    const tableBody = document.querySelector('.customers-table tbody');
    tableBody.innerHTML = ''; // Clear existing rows

    discounts.forEach((discount) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="py-3 px-4">${discount.discountID}</td>
            <td class="py-3 px-4">${discount.discount_type}</td>
            <td class="py-3 px-4">${discount.discount_rate}%</td>
            <td class="py-3 px-4">
                <i class="fas fa-trash text-red-500 delete-btn" data-id="${discount.discountID}"></i>
            </td>
        `;
        tableBody.appendChild(row);
    });

    // Attach event listeners for delete buttons
    attachDeleteListeners();
}

// Add discount
const addDiscountForm = document.getElementById('addDiscountForm');
addDiscountForm.addEventListener('submit', async (event) => {
    event.preventDefault();

    const discountType = document.querySelector('input[name="discountType"]:checked').id;
    const discountRate = document.getElementById('discountRate').value;

    console.log('Discount Type:', discountType); // Debugging
    console.log('Discount Rate:', discountRate); // Debugging

    try {
        const response = await fetch(apiUrl + '?action=add', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ discount_type: discountType, discount_rate: discountRate }),
        });

        const result = await response.json();
        if (result.success) {
            // alert('Discount added successfully!');
            fetchDiscounts(); // Refresh the table
            discountModal.classList.add('hidden'); // Close the modal
            addDiscountForm.reset();
        } else {
            console.error('Error:', result.errors); // Debugging
            alert('Failed to add discount! ' + result.message + '\nErrors: ' + JSON.stringify(result.errors));
        }
    } catch (error) {
        console.error('Fetch Error:', error); // Debugging
        alert('Failed to add discount due to network or server error!');
    }
});

// Attach event listeners for delete buttons
function attachDeleteListeners() {
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach((button) => {
        button.addEventListener('click', function () {
            deleteDiscountId = this.getAttribute('data-id'); // Store the discount ID
            openDeleteModal();
        });
    });
}

// Open the delete confirmation modal
function openDeleteModal() {
    deleteModal.style.display = 'flex';
}

// Close the delete confirmation modal
function closeModal() {
    deleteModal.style.display = 'none';
    deleteDiscountId = null; // Reset the discount ID
}

// Confirm the delete action
async function confirmDelete() {
    if (!deleteDiscountId) return; // Ensure there's a discount ID to delete

    try {
        const response = await fetch(apiUrl + `?action=delete&id=${deleteDiscountId}`, {
            method: 'POST',
        });

        const result = await response.json();
        if (result.success) {
            closeModal();
            fetchDiscounts(); // Refresh the discount table
        } else {
            console.error('Delete failed:', result.errors);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}



// Close the modal when clicked outside of it
discountModal.addEventListener('click', function(event) { 
    if (event.target === discountModal) { 
        discountModal.classList.add('hidden'); 
        addDiscountForm.reset(); // Reset the form 
    } 
});

document.querySelector('button[onclick="discountModal.classList.add(\'hidden\')"]').addEventListener('click', function() { 
    addDiscountForm.reset(); // Reset the form 
});


</script>

</body>
</html>