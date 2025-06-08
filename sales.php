<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MSB POS - Sales</title>
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
                    <a href="sales.php" class="block py-3 px-7 flex items-center gap-2 text-[#b3b3b3] hover:text-white nav-link active-link">
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
                    <h2 class="text-2xl mb-1 text-left">Sales&nbsp&nbsp&nbsp&nbsp</h2>
                        <div class="search-bar relative">
                            <input type="text" class="bg-[#333333] text-white rounded-full pl-10 pr-4 py-2" placeholder="Search...">
                            <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                        </div>
		        </div>
                    <button class="add-customer-btn">
                        <i class="fas fa-plus"></i> Sales
                    </button>
            </div>
            <table class="customers-table w-full text-center text-[#b3b3b3]">
    <thead>
        <tr>
            <th class="py-3 px-4">Sales ID</th>
            <th class="py-3 px-4">Date</th>
            <th class="py-3 px-4">Total Amount</th>
            <th class="py-3 px-4">Cashier ID</th>
            <th class="py-3 px-4">Customer ID</th>
            <th class="py-3 px-4">Discount</th>
        </tr>
    </thead>
    <tbody id="customerTable">
    </tbody>
</table>

<div class="pagination flex justify-center items-center mt-4">
    <button class="w-10 h-10 flex items-center justify-center border border-gray-500 text-white hover:bg-[#2b71fc] pagination-button" data-page="prev">
        <i class="fas fa-chevron-left"></i>
    </button>
    <button class="w-10 h-10 flex items-center justify-center border border-gray-500 text-white pagination-button" data-page="1">1</button>
    <button class="w-10 h-10 flex items-center justify-center border border-gray-500 text-white pagination-button" data-page="2">2</button>
    <button class="w-10 h-10 flex items-center justify-center border border-gray-500 text-white pagination-button" data-page="3">3</button>
    <button class="w-10 h-10 flex items-center justify-center border border-gray-500 text-white pagination-button" data-page="4">4</button>
    <button class="w-10 h-10 flex items-center justify-center border border-gray-500 text-white pagination-button" data-page="5">5</button>
    <button class="w-10 h-10 flex items-center justify-center border border-gray-500 text-white hover:bg-[#2b71fc] pagination-button" data-page="next">
        <i class="fas fa-chevron-right"></i>
    </button>
</div>

            </div>
        </div>

<!-- Add Sales Modal -->
<div class="modal" id="addSalesModal">
    <div class="modal1-content p-10" style="width: 800px;">
        <div class="flex items-center mb-5">
            <i class="fas fa-file-invoice-dollar text-white mr-3"></i>
            <h1 class="text-1xl">Add New Sales</h1>
        </div>
        <hr class="mb-4">
        <form id="addSalesForm">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <div class="mb-5">
                <label for="salesId" class="block mb-2">Sales ID</label>
                <input type="text" id="salesId" name="salesId" placeholder="Sales ID" class="w-full p-2 bg-[#333333] text-white rounded-lg" required>
            </div>
            <div class="mb-5">
                <label for="salesDate" class="block mb-2">Date</label>
                <input type="date" id="salesDate" name="salesDate" class="w-full p-2 bg-[#333333] text-white rounded-lg" required>
            </div>
            <div class="mb-5">
                <label for="totalAmount" class="block mb-2">Total Amount</label>
                <input type="number" id="totalAmount" name="totalAmount" placeholder="Total Amount" class="w-full p-2 bg-[#333333] text-white rounded-lg" required>
            </div>
        </div>
        <div>
            <div class="mb-5">
                <label for="cashierId" class="block mb-2">Cashier ID</label>
                <input type="text" id="cashierId" name="cashierId" placeholder="Cashier ID" class="w-full p-2 bg-[#333333] text-white rounded-lg" required>
            </div>
            <div class="mb-5">
                <label for="customerId" class="block mb-2">Customer ID</label>
                <input type="text" id="customerId" name="customerId" placeholder="Customer ID" class="w-full p-2 bg-[#333333] text-white rounded-lg" required>
            </div>
            <div class="mb-5">
                <label for="discount" class="block mb-2">Discount</label>
                <input type="number" id="discount" name="discount" placeholder="Discount" class="w-full p-2 bg-[#333333] text-white rounded-lg" required>
            </div>
        </div>
    </div>
    <div class="mt-6 flex justify-end gap-4">
        <button type="button" class="cancel-btn px-6 py-1 rounded-lg border border-[#5e5d5d] text-white hover:bg-[#3d3d3d] hover:text-[#a75beb]" onclick="toggleSalesModal(false)">Cancel</button>
        <button type="submit" class="bg-purple-600 hover:bg-[#a75beb] text-white px-6 py-1 rounded-lg">Save</button>
    </div>
</form>


    </div>
</div>

<script>
    const addSalesBtn = document.querySelector('.add-customer-btn');
    const addSalesModal = document.getElementById('addSalesModal');
    const addSalesForm = document.getElementById('addSalesForm');

    function toggleSalesModal(show) {
        addSalesModal.style.display = show ? 'flex' : 'none';
    }

    addSalesBtn.addEventListener('click', () => toggleSalesModal(true));

    window.addEventListener('click', (event) => {
        if (event.target === addSalesModal) {
            toggleSalesModal(false);
        }
    });
</script>

<!-- Update Customer Modal -->
    <div class="modal" id="updateCustomerModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="modal3-content p-10">
        <div class="flex items-center mb-5">
            <i class="fas fa-users text-white mr-3"></i>
            <h1 class="text-1xl">UPDATE CUSTOMER</h1>
        </div>
            <hr class="mb-4">
            <form id="updateCustomerForm">
                <div class="mb-5">
                    <label class="block mb-2">Customer Name: <span id="customerName" class="text-white"></span></label>
                </div>
                <div class="mb-5">
                    <label for="contactNo" class="block mb-2">Contact No</label>
                    <input type="number" id="edit-contactNo" placeholder="Contact No" class="w-full p-2 bg-[#333333] text-white rounded-lg" required">
                </div>
                <div class="mb-5">
                    <label for="email" class="block mb-2">Email</label>
                    <input type="email" id="edit-email" placeholder="Email" class="w-full p-2 bg-[#333333] text-white rounded-lg" required">
                </div>

                <div class="mt-6 flex justify-end gap-4"> <!-- Right align the buttons -->
                    <button type="button" class="cancel-btn px-6 py-1 rounded-lg border border-[#5e5d5d] text-white hover:bg-[#3d3d3d] hover:text-[#a75beb]" onclick="toggleUpdateModal(false)">Cancel</button>

                    <button type="submit" class="bg-purple-600 hover:bg-[#a75beb] text-white px-6 py-1 rounded-lg" id="customerUpdateBtn">Save</button>
                </div>
            </form>
        </div>
    </div>

<!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteCustomerModal">
        <div class="modal2-content p-10 text-center">
            <div class="flex items-center mb-5">
                <h1 class="text-red-500 text-1xl"><i class="fas fa-exclamation-triangle"></i> DELETE COFIRMATION</h1>
            </div>
            <hr class="mb-4">
            <p class="text-justify-center">Are you sure you want to delete this customer?</p>
            
            <div class="mt-6 flex justify-end gap-4"> <!-- Right align the buttons -->
                <button type="button" class="cancel-btn px-6 py-1 rounded-lg border border-[#5e5d5d] text-white hover:bg-[#3d3d3d] hover:text-[#e74c3c]" onclick="toggleDeleteModal(false)">Cancel</button>

                <button type="button" class="bg-red-600 hover:bg-[#e34949] text-white px-6 py-1 rounded-lg" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>

    <script>
 document.addEventListener("DOMContentLoaded", () => {
    const salesPerPage = 7; // Number of sales to display per page
    let currentPage = 1;

    // Function to fetch and load sales data
    const loadSalesData = async (page = 1) => {
        const customerTable = document.getElementById("customerTable");
        try {
            const response = await fetch("http://localhost/getsales.php?page=" + page + "&limit=" + salesPerPage);
            const result = await response.json();

            if (result.success) {
                customerTable.innerHTML = result.data
                    .map(row => {
                        const salesDate = new Date(row.SalesDate.date).toLocaleDateString();
                        return `
                            <tr data-id="${row.SalesID}">
                                <td>${row.SalesID}</td>
                                <td>${salesDate}</td>
                                <td>$${row.TotalAmount}</td>
                                <td>${row.CashierID}</td>
                                <td>${row.CustomerID}</td>
                                <td>${row.Discount}%</td>
                            </tr>`;
                    })
                    .join("");

                // Update pagination
                updatePagination(result.totalPages, page);
            } else {
                console.error("Error fetching data:", result.message);
            }
        } catch (error) {
            console.error("Error fetching data:", error);
        }
    };

    // Function to handle pagination updates
    const updatePagination = (totalPages, currentPage) => {
        const paginationButtons = document.querySelectorAll(".pagination-button");
        paginationButtons.forEach(button => button.classList.remove("bg-purple-600"));
        if (currentPage > 1) {
            document.querySelector(`.pagination-button[data-page="prev"]`).classList.add("bg-purple-600");
        }
        if (currentPage < totalPages) {
            document.querySelector(`.pagination-button[data-page="next"]`).classList.add("bg-purple-600");
        }
        document.querySelector(`.pagination-button[data-page="${currentPage}"]`).classList.add("bg-purple-600");
    };

    // Add Sales Form submission
    document.getElementById("addSalesForm").addEventListener("submit", async (event) => {
        event.preventDefault();

        const formData = new FormData(event.target);
        try {
            const response = await fetch("insert_sales.php", {
                method: "POST",
                body: formData,
            });
            const data = await response.json();

            if (data.success) {
                alert("Sales data inserted successfully");
                // Reload sales data with pagination
                loadSalesData(currentPage);
                document.getElementById("addSalesForm").reset(); // Clear the form
            } else {
                alert(`Error: ${data.message}`);
            }
        } catch (error) {
            console.error("Error:", error);
            alert("There was an error while submitting the form.");
        }
    });

    // Event listeners for pagination
    document.querySelectorAll(".pagination-button").forEach(button => {
        button.addEventListener("click", () => {
            const page = button.getAttribute("data-page");
            if (page === "prev" && currentPage > 1) {
                currentPage--;
            } else if (page === "next" && currentPage < totalPages) {
                currentPage++;
            } else if (!isNaN(page)) {
                currentPage = parseInt(page);
            }
            loadSalesData(currentPage);
        });
    });

    // Load sales data on initial load
    loadSalesData(currentPage);
});

</script>

</body>
</html>
