**list_costs.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Costs Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .bg-orange-500 {
            background-color: #FF9900;
        }
        .text-gray-500 {
            color: #6B7280;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <header class="bg-orange-500 p-4 mb-4">
            <nav class="flex justify-between">
                <a href="index.php" class="text-gray-500 hover:text-white">Back to Index</a>
                <div class="flex items-center">
                    <p class="mr-2">Welcome, <?php echo $_SESSION['username']; ?></p>
                    <a href="logout.php" class="bg-gray-500 hover:bg-orange-500 text-white font-bold py-2 px-4 rounded">Logout</a>
                </div>
            </nav>
        </header>
        <main class="bg-white p-4 rounded shadow-md">
            <h2 class="text-lg font-bold mb-4">Costs Management</h2>
            <div class="flex justify-between mb-4">
                <a href="create_costs.php" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">Add New Item</a>
                <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-orange-500 focus:border-orange-500" placeholder="Search...">
            </div>
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Description</th>
                        <th class="px-4 py-2">Amount</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody id="costs-table">
                    <!-- Table rows will be populated dynamically -->
                </tbody>
            </table>
        </main>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const tableBody = document.getElementById('costs-table');

        searchInput.addEventListener('input', () => {
            const searchTerm = searchInput.value.toLowerCase();
            const tableRows = tableBody.children;
            for (let i = 0; i < tableRows.length; i++) {
                const row = tableRows[i];
                const description = row.cells[1].textContent.toLowerCase();
                if (description.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });

        async function fetchCosts() {
            try {
                const response = await fetch('../backend/costs.php');
                const costs = await response.json();
                const tableRows = costs.map((cost, index) => {
                    return `
                        <tr>
                            <td class="px-4 py-2">${cost.id}</td>
                            <td class="px-4 py-2">${cost.description}</td>
                            <td class="px-4 py-2">${cost.amount}</td>
                            <td class="px-4 py-2">
                                <a href="edit_costs.php?id=${cost.id}" class="text-orange-500 hover:text-orange-700">Edit</a>
                                <button class="bg-gray-500 hover:bg-orange-500 text-white font-bold py-2 px-4 rounded" onclick="deleteCost(${cost.id})">Delete</button>
                            </td>
                        </tr>
                    `;
                }).join('');
                tableBody.innerHTML = tableRows;
            } catch (error) {
                console.error(error);
            }
        }

        fetchCosts();

        async function deleteCost(id) {
            try {
                const response = await fetch('../backend/costs.php', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
                if (response.ok) {
                    fetchCosts();
                } else {
                    console.error('Error deleting cost');
                }
            } catch (error) {
                console.error(error);
            }
        }
    </script>
</body>
</html>

**Note:** This code assumes that you have a `costs.php` file in the `../backend` directory that handles GET and DELETE requests for costs data. You will need to implement this file separately.