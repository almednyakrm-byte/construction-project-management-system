<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Module slug
$mod_slug = 'teams';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Team</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto p-4 mt-10 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-orange-500">Create Team</h2>
        <form id="create-team-form">
            <div class="mt-4">
                <label for="name" class="block text-gray-700">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 border border-gray-200 rounded-lg" required>
            </div>
            <div class="mt-4">
                <label for="description" class="block text-gray-700">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 border border-gray-200 rounded-lg" required></textarea>
            </div>
            <div class="mt-4">
                <label for="leader" class="block text-gray-700">Leader</label>
                <input type="text" id="leader" name="leader" class="block w-full p-2 mt-1 border border-gray-200 rounded-lg" required>
            </div>
            <div class="mt-4">
                <label for="members" class="block text-gray-700">Members</label>
                <input type="text" id="members" name="members" class="block w-full p-2 mt-1 border border-gray-200 rounded-lg" required>
            </div>
            <button type="submit" class="w-full p-2 mt-4 bg-orange-500 text-white rounded-lg hover:bg-orange-700">Create Team</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-team-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/teams.php',
                    data: $(this).serialize(),
                    success: function(data) {
                        window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                    }
                });
            });
        });
    </script>
</body>
</html>