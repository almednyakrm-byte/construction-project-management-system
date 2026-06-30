**list_tasks.php**

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
    <title>Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .bg-orange-500 {
            background-color: #FF9900;
        }
        .text-gray-200 {
            color: #F7F7F7;
        }
    </style>
</head>
<body class="bg-gray-200">
    <div class="container mx-auto p-4">
        <header class="bg-orange-500 p-4 mb-4">
            <nav class="flex justify-between">
                <a href="index.php" class="text-gray-200 hover:text-white">Back to Index</a>
                <div class="flex items-center">
                    <span class="text-gray-200 mr-2">Welcome, <?= $_SESSION['username'] ?></span>
                    <a href="logout.php" class="text-gray-200 hover:text-white">Logout</a>
                </div>
            </nav>
        </header>
        <div class="bg-white p-4 rounded shadow-md">
            <h2 class="text-lg font-bold mb-4">Tasks</h2>
            <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_tasks.php'">Add New Item</button>
            <div class="flex justify-between mb-4">
                <input type="search" class="w-full p-2 mb-2 text-gray-700" placeholder="Search tasks" id="search" onkeyup="filterTasks()">
                <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_tasks.php'">Add New Item</button>
            </div>
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Task Name</th>
                        <th class="px-4 py-2">Description</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody id="tasks-table">
                    <?php
                    // Fetch tasks from backend
                    $tasks = json_decode(file_get_contents('../backend/tasks.php'), true);
                    foreach ($tasks as $task) {
                        ?>
                        <tr>
                            <td class="px-4 py-2"><?= $task['name'] ?></td>
                            <td class="px-4 py-2"><?= $task['description'] ?></td>
                            <td class="px-4 py-2">
                                <a href="edit_tasks.php?id=<?= $task['id'] ?>" class="text-orange-500 hover:text-orange-700">Edit</a>
                                <button class="text-red-500 hover:text-red-700" onclick="deleteTask(<?= $task['id'] ?>)">Delete</button>
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
        function filterTasks() {
            const search = document.getElementById('search').value.toLowerCase();
            const tasks = document.getElementById('tasks-table').getElementsByTagName('tr');
            for (let i = 0; i < tasks.length; i++) {
                const taskName = tasks[i].cells[0].textContent.toLowerCase();
                const taskDescription = tasks[i].cells[1].textContent.toLowerCase();
                if (taskName.includes(search) || taskDescription.includes(search)) {
                    tasks[i].style.display = '';
                } else {
                    tasks[i].style.display = 'none';
                }
            }
        }

        function deleteTask(id) {
            if (confirm('Are you sure you want to delete this task?')) {
                fetch('../backend/tasks.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error deleting task');
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>


**tasks.php (backend)**

<?php
// Fetch tasks from database
$tasks = array();
$tasks[] = array('id' => 1, 'name' => 'Task 1', 'description' => 'This is task 1');
$tasks[] = array('id' => 2, 'name' => 'Task 2', 'description' => 'This is task 2');
$tasks[] = array('id' => 3, 'name' => 'Task 3', 'description' => 'This is task 3');

echo json_encode($tasks);
?>


**Note:** This code assumes that you have a backend script (`tasks.php`) that fetches tasks from a database and returns them in JSON format. You will need to modify the backend script to match your actual database schema and query.