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
    <title>MSB POS - Employees</title>
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
                    <a href="employees.php" class="block py-3 px-7 flex items-center gap-2 text-[#b3b3b3] hover:text-white nav-link active-link">
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
                <h2 class="text-2xl mb-1 text-left">EMPLOYEES</h2>
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search...">
                </div>
                <button class="add-customer-btn">
                    <i class="fas fa-plus"></i> Employee
                </button>
            </div>
<table class="customers-table w-full text-center text-[#b3b3b3]">
    <thead>
        <tr>
            <th class="py-3 px-4">EMPLOYEE ID</th>
            <th class="py-3 px-4">EMPLOYEE NAME</th>
            <th class="py-3 px-4">NIC</th>
            <th class="py-3 px-4">CONTACT</th>
            <th class="py-3 px-4">EMAIL</th>
            <th class="py-3 px-4">ACTIONS</th>
        </tr>
    </thead>
    <tbody>
        <!-- Dynamic rows will be inserted here -->
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

<!-- Add Employee Modal -->
<div class="modal" id="addEmployeeModal">
    <div class="modal-content">
        <div class="flex items-center mb-5">
            <i class="fas fa-users text-white mr-3"></i>
            <h1 class="text-xl">Add New Employee</h1>
        </div>
        <hr class="mb-4">
        <form id="addEmployeeForm">
            <div class="mb-4">
                <label for="employeeName" class="block mb-2">Employee Name</label>
                <input type="text" id="employeeName" placeholder="Employee Name" class="w-full p-2 bg-gray-700 text-white rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="nic" class="block mb-2">NIC</label>
                <input type="text" id="nic" placeholder="NIC" class="w-full p-2 bg-gray-700 text-white rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="contactNo" class="block mb-2">Contact Number</label>
                <input type="text" id="contactNo" placeholder="Contact Number" class="w-full p-2 bg-gray-700 text-white rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block mb-2">Email</label>
                <input type="email" id="email" placeholder="Email" class="w-full p-2 bg-gray-700 text-white rounded-lg" required>
            </div>
            <div class="mt-6 flex justify-end gap-4">
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


<!-- Update Employee Modal -->
<div id="updateEmployeeModal" class="modal hidden">
    <div class="modal-content">
        <div class="flex items-center mb-5">
            <i class="fas fa-users text-white mr-3"></i>
            <h1 class="text-xl">Update Employee</h1>
        </div>
        <hr class="mb-4">
        <form id="updateEmployeeForm">
            <div class="mb-4">
                <label for="updateEmployeeName" class="block mb-2">Employee Name</label>
                <input type="text" id="updateEmployeeName" class="w-full p-2 bg-gray-700 text-white rounded-lg" placeholder="Employee Name" required>
            </div>
            <div class="mb-4">
                <label for="updateNIC" class="block mb-2">NIC</label>
                <input type="text" id="updateNIC" class="w-full p-2 bg-gray-700 text-white rounded-lg" placeholder="NIC" required>
            </div>
            <div class="mb-4">
                <label for="updateContactNo" class="block mb-2">Contact Number</label>
                <input type="text" id="updateContactNo" class="w-full p-2 bg-gray-700 text-white rounded-lg" placeholder="Contact Number" required>
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
        // Base API URL
const apiUrl = "employee_api.php"; // Replace with your actual PHP API endpoint

// Fetch and display employees
let allEmployees = []; // Store all employees globally for filtering

async function fetchEmployees() {
    try {
        const response = await fetch(`${apiUrl}?action=fetch`);
        allEmployees = await response.json(); // Store fetched employees
        displayEmployees(allEmployees); // Display all employees initially
    } catch (error) {
        console.error("Error fetching employees:", error);
    }
}

// Display employees in the table
function displayEmployees(employees) {
    const tableBody = document.querySelector(".customers-table tbody");
    tableBody.innerHTML = ""; // Clear the table body

    employees.forEach((employee) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td class="py-3 px-4">${employee.employee_id}</td>
            <td class="py-3 px-4">${employee.employee_name}</td>
            <td class="py-3 px-4">${employee.nic}</td>
            <td class="py-3 px-4">${employee.contact_no}</td>
            <td class="py-3 px-4">${employee.email}</td>
            <td class="py-3 px-4">
                <i class="fas fa-pen text-blue-500 mr-3 edit-btn" data-id="${employee.employee_id}"></i>
                <i class="fas fa-trash text-red-500 delete-btn" data-id="${employee.employee_id}"></i>
            </td>
        `;
        tableBody.appendChild(row);
    });

    attachEventListeners(); // Attach edit and delete listeners
}

// Filter employees based on the search query
function filterEmployees(query) {
    const filteredEmployees = allEmployees.filter((employee) => {
        return (
            employee.employee_name.toLowerCase().includes(query.toLowerCase()) ||
            employee.nic.toLowerCase().includes(query.toLowerCase()) ||
            employee.contact_no.includes(query) ||
            employee.email.toLowerCase().includes(query.toLowerCase())
        );
    });
    displayEmployees(filteredEmployees); // Update table with filtered results
}

// Add an event listener to the search bar
document.querySelector(".search-bar input").addEventListener("input", (event) => {
    const query = event.target.value.trim(); // Get the input value
    filterEmployees(query); // Call the filter function
});

// Initialize the employee table on page load
document.addEventListener("DOMContentLoaded", () => {
    fetchEmployees();

    document.querySelector(".add-customer-btn").addEventListener("click", () => toggleModal(true));
});


// Add new employee
document.getElementById("addEmployeeForm").addEventListener("submit", async (event) => {
    event.preventDefault();

    const employeeName = document.getElementById("employeeName").value.trim();
    const nic = document.getElementById("nic").value.trim();
    const contactNo = document.getElementById("contactNo").value.trim();
    const email = document.getElementById("email").value.trim();

    // Input validation
    if (employeeName.length > 30) {
        showNotification("Employee name cannot exceed 30 characters!", "error");
        return;
    }
    if (!/^\d{10,12}$/.test(nic)) {
        showNotification("NIC must be 10-12 numeric digits!", "error");
        return;
    }
    if (!/^\d{10}$/.test(contactNo)) {
        showNotification("Contact number must be a valid 10-digit number!", "error");
        return;
    }

    try {
        const response = await fetch(`${apiUrl}?action=add`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ employee_name: employeeName, nic, contact_no: contactNo, email }),
        });
        const result = await response.json();

        if (result.success) {
            showNotification("Employee added successfully!", "success");
            fetchEmployees(); // Refresh the employee table
            toggleModal(false); // Close the add employee modal
        } else {
            showNotification("Failed to add employee!", "error");
        }
    } catch (error) {
        console.error("Error adding employee:", error);
        showNotification("An error occurred while adding the employee!", "error");
    }
});

// Delete employee
let employeeToDeleteId = null;

function toggleDeleteModal(show, employeeId = null) {
    const modal = document.getElementById("deleteCustomerModal");
    modal.style.display = show ? "flex" : "none";
    employeeToDeleteId = show ? employeeId : null;
}

document.getElementById("confirmDeleteBtn").addEventListener("click", async () => {
    if (employeeToDeleteId) {
        try {
            const response = await fetch(`${apiUrl}?action=delete&id=${employeeToDeleteId}`);
            const result = await response.json();

            if (result.success) {
                showNotification("Employee deleted successfully!", "success");
                fetchEmployees(); // Refresh the employee table
            } else {
                showNotification("Failed to delete employee!", "error");
            }
        } catch (error) {
            console.error("Error deleting employee:", error);
            showNotification("An error occurred while deleting the employee!", "error");
        } finally {
            toggleDeleteModal(false); // Close delete modal
        }
    }
});

// Update employee
let employeeToUpdateId = null;

// Function to toggle the Update Employee modal
function toggleUpdateModal(show, employeeData = {}) {
    const modal = document.getElementById("updateEmployeeModal");

    if (show) {
        // Populate modal fields with employee data
        document.getElementById("updateEmployeeName").value = employeeData.employee_name || "";
        document.getElementById("updateNIC").value = employeeData.nic || "";
        document.getElementById("updateContactNo").value = employeeData.contact_no || "";
        document.getElementById("updateEmail").value = employeeData.email || "";
        employeeToUpdateId = employeeData.employee_id || null;

        modal.style.display = "flex"; // Show the modal
    } else {
        modal.style.display = "none"; // Hide the modal
        employeeToUpdateId = null; // Clear the stored employee ID
    }
}

// Handle Update Employee form submission
document.getElementById("updateEmployeeForm").addEventListener("submit", async (event) => {
    event.preventDefault();

    const employeeName = document.getElementById("updateEmployeeName").value.trim();
    const nic = document.getElementById("updateNIC").value.trim();
    const contactNo = document.getElementById("updateContactNo").value.trim();
    const email = document.getElementById("updateEmail").value.trim();

    // Input validation
    if (employeeName.length > 30) {
        showNotification("Employee name cannot exceed 30 characters!", "error");
        return;
    }
    if (!/^\d{10,12}$/.test(nic)) {
        showNotification("NIC must be 10-12 numeric digits!", "error");
        return;
    }
    if (!/^\d{10}$/.test(contactNo)) {
        showNotification("Contact number must be a valid 10-digit number!", "error");
        return;
    }

    try {
        const response = await fetch(`${apiUrl}?action=edit`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                employee_id: employeeToUpdateId,
                employee_name: employeeName,
                nic: nic,
                contact_no: contactNo,
                email: email,
            }),
        });

        const result = await response.json();

        if (result.success) {
            showNotification("Employee updated successfully!", "success");
            fetchEmployees(); // Refresh the employee list
            toggleUpdateModal(false); // Close the modal
        } else {
            showNotification("Failed to update employee!", "error");
        }
    } catch (error) {
        console.error("Error updating employee:", error);
        showNotification("An error occurred while updating the employee!", "error");
    }
});

// Handle Edit button click
function attachEventListeners() {
    document.querySelectorAll(".edit-btn").forEach((button) => {
        button.addEventListener("click", (event) => {
            const row = event.target.closest("tr");
            const employeeData = {
                employee_id: event.target.dataset.id,
                employee_name: row.children[1].textContent,
                nic: row.children[2].textContent,
                contact_no: row.children[3].textContent,
                email: row.children[4].textContent,
            };
            toggleUpdateModal(true, employeeData);
        });
    });

    document.querySelectorAll(".delete-btn").forEach((button) => {
        button.addEventListener("click", (event) => {
            const employeeId = event.target.dataset.id;
            toggleDeleteModal(true, employeeId);
        });
    });
}

// Attach event listeners to dynamically created edit and delete buttons
function attachEventListeners() {
    document.querySelectorAll(".edit-btn").forEach((button) => {
        button.addEventListener("click", (event) => {
            const row = event.target.closest("tr");
            const employeeData = {
                employee_id: event.target.dataset.id,
                employee_name: row.children[1].textContent,
                nic: row.children[2].textContent,
                contact_no: row.children[3].textContent,
                email: row.children[4].textContent,
            };
            toggleUpdateModal(true, employeeData);
        });
    });

    document.querySelectorAll(".delete-btn").forEach((button) => {
        button.addEventListener("click", (event) => {
            const employeeId = event.target.dataset.id;
            toggleDeleteModal(true, employeeId);
        });
    });
}

// Toggle modal visibility for Add Employee Modal
function toggleModal(show) {
    const modal = document.getElementById("addEmployeeModal");
    modal.style.display = show ? "flex" : "none";
}

// Show notifications
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

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Initialize the employee table on page load
document.addEventListener("DOMContentLoaded", () => {
    fetchEmployees();

    document.querySelector(".add-customer-btn").addEventListener("click", () => toggleModal(true));
});
    </script>
</body>
</html>
