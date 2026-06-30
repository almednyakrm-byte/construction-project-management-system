<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}

// Current user info
$current_user = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resources</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-gray-500 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">Home</a>
            <span class="text-lg font-bold">Welcome, <?php echo $current_user; ?></span>
            <a href="logout.php" class="text-lg font-bold">Logout</a>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-3xl font-bold mb-4">Resources</h1>
        <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded mb-4">
            <a href="create_resources.php">Add New Item</a>
        </button>
        <input type="text" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-600" placeholder="Search...">
        <table id="resources-table" class="w-full text-gray-700 mt-4">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody id="resources-tbody">
                <!-- Table content will be populated by JavaScript -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch resources from backend
        fetch('../backend/resources.php')
            .then(response => response.json())
            .then(data => {
                const resourcesTbody = document.getElementById('resources-tbody');
                resourcesTbody.innerHTML = '';
                data.forEach(resource => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-3">${resource.id}</td>
                        <td class="px-4 py-3">${resource.name}</td>
                        <td class="px-4 py-3">
                            <a href="edit_resources.php?id=${resource.id}" class="text-orange-500 hover:text-orange-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700 ml-2" onclick="deleteResource(${resource.id})">Delete</button>
                        </td>
                    `;
                    resourcesTbody.appendChild(row);
                });
            });

        // Delete resource
        function deleteResource(id) {
            fetch('../backend/resources.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove deleted resource from table
                    const resourcesTbody = document.getElementById('resources-tbody');
                    const rows = resourcesTbody.children;
                    for (let i = 0; i < rows.length; i++) {
                        const row = rows[i];
                        const idCell = row.children[0];
                        if (idCell.textContent == id) {
                            resourcesTbody.removeChild(row);
                            break;
                        }
                    }
                } else {
                    console.error('Error deleting resource:', data.error);
                }
            });
        }

        // Search bar filtering
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const rows = document.getElementById('resources-tbody').children;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const nameCell = row.children[1];
                if (nameCell.textContent.toLowerCase().includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>