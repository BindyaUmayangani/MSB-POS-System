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

        .modal-content {
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
        background-color: #3d3d3d; /* Updated color */
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
        font-weight: 500;
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
                    <a href="products.php" class="block py-3 px-7 flex items-center gap-2 text-[#b3b3b3] hover:text-white nav-link">
                        <i class="fas fa-cubes"></i> Products
                    </a>
                </li>
                <li>
                    <a href="customers.php" class="block py-3 px-7 flex items-center gap-2 text-[#b3b3b3] hover:text-white nav-link active-link">
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
                <div id="dropdownMenu" class="absolute right-2 mt-20 w-55  bg-[#EF4444]  rounded-lg shadow-lg hidden">
                    <div class="p-1">
                        <a href="logout.php" id="logoutButton" class="block text-white hover:bg-[#f55863] p-0 rounded text-1xl">Logout</a>
                    </div>
                </div>
        </div>
        <hr>
        <div class="content-area">
            <div class="header">
                <h2 class="text-2xl mb-1 text-left">Customers</h2>
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search...">
                </div>
                <button class="add-customer-btn">
                    <i class="fas fa-plus"></i> Customer
                </button>
            </div>
            <table class="customers-table w-full text-center text-[#b3b3b3]">
                <thead>
                    <tr>
                        <th class="py-3 px-4">CUSTOMER ID</th>
                        <th class="py-3 px-4">CUSTOMER NAME</th>
                        <th class="py-3 px-4">CONTACT</th>
                        <th class="py-3 px-4">EMAIL</th>
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
                <button>4</button>
                <button>5</button>
                <button><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </div>

    <!--Add Customer Modal -->
    <div class="modal" id="addCustomerModal">
        <div class="modal-content">
        <div class="flex items-center mb-5">
            <i class="fas fa-users text-white mr-3"></i>
            <h1 class="text-xl">Add New Customer</h1>
        </div>
            <hr class="mb-4">
            <form id="addCustomerForm">
                <div class="mb-4">
                    <label for="customerName" class="block mb-2">Customer name</label>
                    <input type="text" id="customerName" placeholder="Customer name" class="w-full p-2 bg-gray-700 text-white rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label for="contactNo" class="block mb-2">Contact No</label>
                    <input type="number" id="contactNo" placeholder="Contact No" class="w-full p-2 bg-gray-700 text-white rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block mb-2">Email</label>
                    <input type="email" id="email" placeholder="Email" class="w-full p-2 bg-gray-700 text-white rounded-lg" required>
                </div>
        <div class="mt-6 flex justify-end gap-4"> <!-- Right align the buttons -->
            <button type="button" class="cancel-btn px-6 py-1 rounded-lg border border-[#3d3d3d] text-[#e74c3c] hover:bg-[#3d3d3d] hover:text-white" onclick="toggleModal(false)">Cancel</button>

            <button type="submit" class="bg-purple-600 text-white px-6 py-1 rounded-lg">Save</button>
        </div>
            </form>
        </div>
    </div>

<!-- Delete Confirmation Modal -->
<div id="deleteCustomerModal" class="modal hidden">
    <div class="modal2-content p-10 text-center">
        <div class="flex items-center mb-5">
            <h1 class="text-red-500 text-1xl"><i class="fas fa-exclamation-triangle"></i> DELETE CONFIRMATION</h1>
        </div>
        <hr class="mb-4">
        <p class="text-justify-center">Are you sure you want to delete 
            <span id="customerNameToDelete" class="font-bold text-red-400"></span>?
        </p>
        <div class="mt-6 flex justify-end gap-4">
            <button type="button" class="cancel-btn bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-500" onclick="toggleDeleteModal(false)">Cancel</button>
            <button type="button" id="confirmDeleteBtn" class="bg-red-600 hover:bg-[#e34949] text-white px-6 py-2 rounded-lg">Delete</button>
        </div>
    </div>
</div>


<!-- Update Customer Modal -->
<div id="updateCustomerModal" class="modal hidden">
    <div class="modal-content">
        <div class="flex items-center mb-5">
            <i class="fas fa-users text-white mr-3"></i>
            <h1 class="text-xl">Update Customer</h1>
        </div>
        <hr class="mb-4">
        <form id="updateCustomerForm">
            <div class="mb-4">
                <label for="updateCustomerName" class="block mb-2">Customer Name</label>
                <input type="text" id="updateCustomerName" class="w-full p-2 bg-gray-700 text-white rounded-lg" placeholder="Customer Name" required>
            </div>
            <div class="mb-4">
                <label for="updateContactNo" class="block mb-2">Contact No</label>
                <input type="text" id="updateContactNo" class="w-full p-2 bg-gray-700 text-white rounded-lg" placeholder="Contact No" required>
            </div>
            <div class="mb-4">
                <label for="updateEmail" class="block mb-2">Email</label>
                <input type="email" id="updateEmail" class="w-full p-2 bg-gray-700 text-white rounded-lg" placeholder="Email" required>
            </div>
            <div class="mt-6 flex justify-end gap-4">
                <button type="button" class="cancel-btn px-6 py-2 rounded-lg border border-gray-500 text-white hover:bg-gray-500" onclick="toggleUpdateModal(false)">Cancel</button>
                <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg">Save</button>
            </div>
        </form>
    </div>
</div>







    <script>

// Success notification for login
document.addEventListener("DOMContentLoaded", () => {
    if (sessionStorage.getItem("loginSuccess") === "true") {
        // Create the success notification popup
        const notification = document.createElement("div");
        notification.textContent = "Login successful!";
        notification.style.position = "fixed";
        notification.style.top = "20px";
        notification.style.right = "20px";
        notification.style.backgroundColor = "green";
        notification.style.color = "white";
        notification.style.padding = "10px 20px";
        notification.style.borderRadius = "5px";
        notification.style.zIndex = "1000";
        notification.style.fontFamily = "Arial, sans-serif";

        // Add the notification to the body
        document.body.appendChild(notification);

        // Remove the notification after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);

        // Clear session storage to prevent showing the notification again
        sessionStorage.removeItem("loginSuccess");
    }

    // Initialize the customers table
    fetchCustomers();
});



function showNotification(message, type = "success") {
    const notification = document.createElement("div");
    notification.textContent = message;
    notification.style.position = "fixed";
    notification.style.top = "20px";
    notification.style.right = "20px";
    notification.style.backgroundColor = type === "success" ? "green" : "red";
    notification.style.color = "white";
    notification.style.padding = "10px 20px";
    notification.style.borderRadius = "5px";
    notification.style.zIndex = "1000";
    notification.style.fontFamily = "Poppins, sans-serif";
    notification.style.boxShadow = "0 4px 6px rgba(0, 0, 0, 0.1)";

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}







// Base API URL
const apiUrl = "customer_api.php"; // Replace with your PHP API file path

// Global variable to store all customers
let allCustomers = [];

// Fetch and display customers
async function fetchCustomers() {
    try {
        const response = await fetch(`${apiUrl}?action=fetch`);
        const customers = await response.json();
        allCustomers = customers; // Store customers globally for search
        renderCustomerTable(customers); // Render table
    } catch (error) {
        console.error("Error fetching customers:", error);
    }
}

// Render customer table
function renderCustomerTable(customers) {
    const tableBody = document.querySelector(".customers-table tbody");
    tableBody.innerHTML = ""; // Clear the table

    customers.forEach((customer) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td class="py-3 px-4">${customer.customer_id}</td>
            <td class="py-3 px-4">${customer.customer_name}</td>
            <td class="py-3 px-4">${customer.contact_no}</td>
            <td class="py-3 px-4">${customer.email}</td>
            <td class="py-3 px-4">
                <i class="fas fa-pen text-blue-500 mr-3 edit-btn" data-id="${customer.customer_id}"></i>
                <i class="fas fa-trash text-red-500 delete-btn" data-id="${customer.customer_id}"></i>
            </td>
        `;
        tableBody.appendChild(row);
    });

    attachEventListeners(); // Reattach listeners
}

// Search functionality
document.querySelector(".search-bar input").addEventListener("input", (event) => {
    const searchTerm = event.target.value.toLowerCase();

    const filteredCustomers = allCustomers.filter((customer) =>
        customer.customer_name.toLowerCase().includes(searchTerm) ||
        customer.contact_no.toLowerCase().includes(searchTerm) ||
        customer.email.toLowerCase().includes(searchTerm)
    );

    renderCustomerTable(filteredCustomers); // Update table based on search
});

// Initial load
document.addEventListener("DOMContentLoaded", () => {
    fetchCustomers(); // Fetch and display customers on page load
});


// Attach event listeners for edit and delete buttons
function attachEventListeners() {
    document.querySelectorAll(".edit-btn").forEach((editBtn) => {
        editBtn.addEventListener("click", handleEditCustomer);
    });

    document.querySelectorAll(".delete-btn").forEach((deleteBtn) => {
        deleteBtn.addEventListener("click", handleDeleteCustomer);
    });
}

// Add customer
const addCustomerForm = document.getElementById("addCustomerForm");
addCustomerForm.addEventListener("submit", async (event) => {
    event.preventDefault();

    const customerName = document.getElementById("customerName").value.trim();
    const contactNo = document.getElementById("contactNo").value.trim();
    const email = document.getElementById("email").value.trim();

    // Validate customer name (max 30 characters)
    if (customerName.length > 30) {
        showNotification("Customer name cannot exceed 30 characters!", "error");
        return;
    }

    // Validate phone number (10 digits, numeric)
    if (!/^\d{10}$/.test(contactNo)) {
        showNotification("Contact number must be a valid 10-digit number!", "error");
        return;
    }

    // Proceed with API call
    const response = await fetch(apiUrl + "?action=add", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ customer_name: customerName, contact_no: contactNo, email: email }),
    });

    const result = await response.json();
    if (result.success) {
        showNotification("Customer added successfully!", "success");
        fetchCustomers(); // Refresh the customer list
        toggleModal(false); // Close the modal
    } else {
        showNotification("Failed to add customer!", "error");
    }
});


// Handle delete customer
let customerToDeleteId = null; // Store the ID of the customer to delete

// Function to toggle the delete confirmation modal
function toggleDeleteModal(show, customerName = "", customerId = null) {
    const modal = document.getElementById("deleteCustomerModal");
    const customerNameElement = document.getElementById("customerNameToDelete");

    if (show) {
        customerNameElement.textContent = `"${customerName}"`;
        customerToDeleteId = customerId;
        modal.style.display = "flex"; // Directly change the display style to make the modal visible
    } else {
        modal.style.display = "none"; // Hide the modal
        customerToDeleteId = null;
    }
}

// Handle delete button click
function handleDeleteCustomer(event) {
    const customerRow = event.target.closest("tr");
    const customerName = customerRow.children[1].textContent; // Assuming 2nd cell is the name
    const customerId = event.target.dataset.id;

    toggleDeleteModal(true, customerName, customerId); // Open the modal
}

// Confirm delete action
document.getElementById("confirmDeleteBtn").addEventListener("click", async () => {
    if (customerToDeleteId) {
        try {
            const response = await fetch(apiUrl + `?action=delete&id=${customerToDeleteId}`, { method: "GET" });
            const result = await response.json();

            if (result.success) {
                showNotification("Customer deleted successfully!", "success");
                fetchCustomers(); // Refresh customer list
            } else {
                showNotification("Failed to delete customer!", "error");
            }
        } catch (error) {
            console.error("Error deleting customer:", error);
            showNotification("An error occurred while trying to delete the customer.", "error");
        } finally {
            toggleDeleteModal(false); // Close the modal
        }
    }
});

// Handle edit customer
// Store the ID of the customer to update
let customerToUpdateId = null;

// Function to toggle the update customer modal
function toggleUpdateModal(show, customerData = {}) {
    const modal = document.getElementById("updateCustomerModal");

    if (show) {
        // Populate the modal form with the customer data
        document.getElementById("updateCustomerName").value = customerData.customer_name || "";
        document.getElementById("updateContactNo").value = customerData.contact_no || "";
        document.getElementById("updateEmail").value = customerData.email || "";
        customerToUpdateId = customerData.customer_id || null;

        // Show the modal
        modal.style.display = "flex"; // Use 'flex' for consistent modal display
    } else {
        // Hide the modal
        modal.style.display = "none";

        // Clear the stored customer ID
        customerToUpdateId = null;
    }
}

// Handle update customer form submission
document.getElementById("updateCustomerForm").addEventListener("submit", async (event) => {
    event.preventDefault();

    const customerName = document.getElementById("updateCustomerName").value.trim();
    const contactNo = document.getElementById("updateContactNo").value.trim();
    const email = document.getElementById("updateEmail").value.trim();

    // Validate customer name (max 30 characters)
    if (customerName.length > 30) {
        showNotification("Customer name cannot exceed 30 characters!", "error");
        return;
    }

    // Validate phone number (10 digits, numeric)
    if (!/^\d{10}$/.test(contactNo)) {
        showNotification("Contact number must be a valid 10-digit number!", "error");
        return;
    }

    // Proceed with API call if validations pass
    if (customerToUpdateId) {
        try {
            const response = await fetch(apiUrl + "?action=edit", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    customer_id: customerToUpdateId,
                    customer_name: customerName,
                    contact_no: contactNo,
                    email: email,
                }),
            });
            const result = await response.json();

            if (result.success) {
                showNotification("Customer updated successfully!", "success");
                fetchCustomers(); // Refresh the customer list
            } else {
                showNotification("Failed to update customer!", "error");
            }
        } catch (error) {
            console.error("Error updating customer:", error);
            showNotification("An error occurred while updating the customer!", "error");
        } finally {
            toggleUpdateModal(false); // Close the modal
        }
    }
});

// Handle edit button click
function handleEditCustomer(event) {
    const customerRow = event.target.closest("tr");

    // Gather data from the row (assuming specific column order)
    const customerData = {
        customer_id: event.target.dataset.id,
        customer_name: customerRow.children[1].textContent,
        contact_no: customerRow.children[2].textContent,
        email: customerRow.children[3].textContent,
    };

    // Open the update modal with the customer data
    toggleUpdateModal(true, customerData);
}

// Handle update customer form submission
document.getElementById("updateCustomerForm").addEventListener("submit", async (event) => {
    event.preventDefault();

    // Collect data from the modal form
    const customerName = document.getElementById("updateCustomerName").value;
    const contactNo = document.getElementById("updateContactNo").value;
    const email = document.getElementById("updateEmail").value;

    if (customerToUpdateId) {
        try {
            // Make the update request to the server
            const response = await fetch(apiUrl + "?action=edit", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    customer_id: customerToUpdateId,
                    customer_name: customerName,
                    contact_no: contactNo,
                    email: email,
                }),
            });

            const result = await response.json();

            if (result.success) {
                // Show success notification
                showNotification("Customer updated successfully!", "success");

                // Refresh the customer list
                fetchCustomers();
            } else {
                // Show error notification
                showNotification("Failed to update customer!", "error");
            }
        } catch (error) {
            console.error("Error updating customer:", error);
            showNotification("An error occurred while updating the customer!", "error");
        } finally {
            // Close the modal
            toggleUpdateModal(false);
        }
    }
});








// Toggle modal visibility
function toggleModal(show) {
    const modal = document.getElementById("addCustomerModal");
    modal.style.display = show ? "flex" : "none";
}

const addCustomerBtn = document.querySelector(".add-customer-btn");
addCustomerBtn.addEventListener("click", () => toggleModal(true));

// Close modal if clicked outside
window.addEventListener("click", (event) => {
    const modal = document.getElementById("addCustomerModal");
    if (event.target === modal) {
        toggleModal(false); // Close modal if clicked outside
    }
});

document.getElementById("profileButton").addEventListener("click", function () {
    const dropdownMenu = document.getElementById("dropdownMenu");
    if (dropdownMenu) {
        dropdownMenu.classList.toggle("hidden");
    }
});




    </script>
</body>
</html>
