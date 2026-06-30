**list_teams.php**

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
    <title>Teams</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .bg-orange-500 {
            background-color: #FF9900;
        }
        .text-gray-200 {
            color: #D3D3D3;
        }
    </style>
</head>
<body class="bg-gray-200">
    <div class="max-w-7xl mx-auto p-4">
        <nav class="bg-orange-500 p-4 mb-4">
            <div class="container mx-auto flex justify-between">
                <a href="index.php" class="text-gray-200">Back to Index</a>
                <div class="flex items-center">
                    <p class="text-gray-200 mr-4">Welcome, <?= $_SESSION['username'] ?></p>
                    <a href="logout.php" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">Logout</a>
                </div>
            </div>
        </nav>
        <div class="container mx-auto p-4">
            <h1 class="text-3xl mb-4">Teams</h1>
            <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_teams.php'">Add New Item</button>
            <div class="flex justify-between mb-4">
                <input type="search" id="search" class="border border-gray-400 p-2 w-full" placeholder="Search...">
                <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded" onclick="searchTeams()">Search</button>
            </div>
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody id="teams-list">
                    <?php
                    // Fetch teams list from backend
                    $teams = json_decode(file_get_contents('../backend/teams.php'), true);
                    foreach ($teams as $team) {
                        echo '<tr>';
                        echo '<td class="px-4 py-2">' . $team['name'] . '</td>';
                        echo '<td class="px-4 py-2">';
                        echo '<a href="edit_teams.php?id=' . $team['id'] . '" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded mr-2">Edit</a>';
                        echo '<button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded" onclick="deleteTeam(' . $team['id'] . ')">Delete</button>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function searchTeams() {
            const searchInput = document.getElementById('search');
            const searchValue = searchInput.value.toLowerCase();
            const teamsList = document.getElementById('teams-list');
            teamsList.innerHTML = '';
            fetch('../backend/teams.php?search=' + searchValue)
                .then(response => response.json())
                .then(data => {
                    data.forEach(team => {
                        const teamRow = document.createElement('tr');
                        teamRow.innerHTML = `
                            <td class="px-4 py-2">${team.name}</td>
                            <td class="px-4 py-2">
                                <a href="edit_teams.php?id=${team.id}" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded mr-2">Edit</a>
                                <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded" onclick="deleteTeam(${team.id})">Delete</button>
                            </td>
                        `;
                        teamsList.appendChild(teamRow);
                    });
                });
        }

        function deleteTeam(id) {
            if (confirm('Are you sure you want to delete this team?')) {
                fetch('../backend/teams.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Team deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error deleting team!');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>
</html>

**teams.php (backend)**

<?php
// Fetch teams list from database
$teams = array(
    array('id' => 1, 'name' => 'Team 1'),
    array('id' => 2, 'name' => 'Team 2'),
    array('id' => 3, 'name' => 'Team 3')
);

// Search functionality
if (isset($_GET['search'])) {
    $searchValue = $_GET['search'];
    $teams = array_filter($teams, function($team) use ($searchValue) {
        return strpos(strtolower($team['name']), $searchValue) !== false;
    });
}

// Output teams list in JSON format
header('Content-Type: application/json');
echo json_encode($teams);
?>

Note: This code assumes that you have a `teams` table in your database with `id` and `name` columns. You'll need to modify the `teams.php` file to connect to your database and fetch the teams list accordingly.