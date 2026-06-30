**list_schedules.php**

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
    <title>Schedules</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .bg-gray-500 {
            background-color: #6b7280;
        }
        .text-orange-500 {
            color: #ff9900;
        }
    </style>
</head>
<body class="bg-gray-500">
    <div class="max-w-7xl mx-auto p-4">
        <nav class="bg-gray-500 rounded-md p-4">
            <ul class="flex justify-between items-center">
                <li>
                    <a href="index.php" class="text-orange-500 hover:text-white">Back to Index</a>
                </li>
                <li>
                    <span class="text-orange-500 hover:text-white">Welcome, <?= $_SESSION['username'] ?></span>
                </li>
                <li>
                    <a href="logout.php" class="text-orange-500 hover:text-white">Logout</a>
                </li>
            </ul>
        </nav>
        <div class="p-4">
            <h1 class="text-orange-500 text-3xl">Schedules</h1>
            <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_schedules.php'">Add New Item</button>
            <div class="p-4">
                <input type="search" id="search" class="block p-2 pl-10 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search...">
            </div>
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="py-3 px-6">ID</th>
                        <th scope="col" class="py-3 px-6">Name</th>
                        <th scope="col" class="py-3 px-6">Actions</th>
                    </tr>
                </thead>
                <tbody id="schedules-list">
                    <?php
                    // Fetch data from backend
                    $url = '../backend/schedules.php';
                    $response = file_get_contents($url);
                    $data = json_decode($response, true);
                    foreach ($data as $schedule) {
                        ?>
                        <tr>
                            <td class="py-4 px-6"><?= $schedule['id'] ?></td>
                            <td class="py-4 px-6"><?= $schedule['name'] ?></td>
                            <td class="py-4 px-6">
                                <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='edit_schedules.php?id=<?= $schedule['id'] ?>'">Edit</button>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteSchedule(<?= $schedule['id'] ?>)">Delete</button>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Search bar filtering
        const searchInput = document.getElementById('search');
        const schedulesList = document.getElementById('schedules-list');

        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const schedules = schedulesList.children;
            for (let i = 0; i < schedules.length; i++) {
                const schedule = schedules[i];
                const name = schedule.children[1].textContent.toLowerCase();
                if (name.includes(searchValue)) {
                    schedule.style.display = 'table-row';
                } else {
                    schedule.style.display = 'none';
                }
            }
        });

        // Delete schedule
        function deleteSchedule(id) {
            fetch('../backend/schedules.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Schedule deleted successfully!');
                    location.reload();
                } else {
                    alert('Error deleting schedule!');
                }
            })
            .catch(error => console.error(error));
        }
    </script>
</body>
</html>

**backend/schedules.php**

<?php
// Fetch data from database
$data = array(
    array('id' => 1, 'name' => 'Schedule 1'),
    array('id' => 2, 'name' => 'Schedule 2'),
    array('id' => 3, 'name' => 'Schedule 3')
);
echo json_encode($data);
?>

Note: This code assumes that you have a database setup to store the schedules data. You will need to modify the `backend/schedules.php` file to connect to your database and fetch the data accordingly.