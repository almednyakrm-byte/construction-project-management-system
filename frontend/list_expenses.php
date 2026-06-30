<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}

// Current user info
$current_user = $_SESSION['username'];

// Logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expenses List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-orange-500 text-gray-200 py-4">
        <nav class="container mx-auto flex justify-between">
            <a href="index.php" class="text-lg font-bold">Home</a>
            <span class="text-lg font-bold">Welcome, <?php echo $current_user; ?></span>
            <a href="?logout" class="text-lg font-bold">Logout</a>
        </nav>
    </header>
    <main class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <h1 class="text-3xl font-bold mb-4">Expenses List</h1>
        <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500" placeholder="Search...">
        <table id="expenses-table" class="w-full mt-4 border border-gray-200">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 text-sm font-bold text-gray-700">ID</th>
                    <th class="px-4 py-2 text-sm font-bold text-gray-700">Name</th>
                    <th class="px-4 py-2 text-sm font-bold text-gray-700">Amount</th>
                    <th class="px-4 py-2 text-sm font-bold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody id="expenses-tbody">
                <!-- Table content will be populated by JavaScript -->
            </tbody>
        </table>
        <a href="create_expenses.php" class="bg-orange-500 hover:bg-orange-700 text-gray-200 font-bold py-2 px-4 rounded mt-4">Add New Item</a>
    </main>

    <script>
        // Fetch API to get expenses list
        fetch('../backend/expenses.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('expenses-tbody');
                data.forEach(expense => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${expense.id}</td>
                        <td>${expense.name}</td>
                        <td>${expense.amount}</td>
                        <td>
                            <a href="edit_expenses.php?id=${expense.id}" class="text-orange-500 hover:text-orange-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteExpense(${expense.id})">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            })
            .catch(error => console.error('Error:', error));

        // Delete expense using Fetch API
        function deleteExpense(id) {
            fetch(`../backend/expenses.php?id=${id}`, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const row = document.querySelector(`#expenses-tbody tr:nth-child(${id})`);
                    row.remove();
                } else {
                    console.error('Error deleting expense:', data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Search bar filtering
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const filter = searchInput.value.toLowerCase();
            const rows = document.querySelectorAll('#expenses-tbody tr');
            rows.forEach(row => {
                const name = row.cells[1].textContent.toLowerCase();
                if (name.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>