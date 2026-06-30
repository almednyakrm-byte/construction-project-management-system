<?php
// Session validation
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get labor ID from URL
$labor_id = $_GET['id'];

// Include database connection
require_once '../backend/db.php';

// Check if labor ID is valid
$labor_query = $db->prepare("SELECT * FROM labor WHERE id = :id");
$labor_query->bindParam(':id', $labor_id);
$labor_query->execute();
$labor = $labor_query->fetch();

if (!$labor) {
    header('Location: list_labor.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Labor</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4 pt-6 mt-10 bg-gray-200 rounded-lg shadow-md">
        <h2 class="text-2xl text-indigo-500 font-bold mb-4">Edit Labor</h2>
        <form id="edit-labor-form">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $labor['name']; ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo $labor['description']; ?></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update Labor</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Populate form fields with existing record details
            $.ajax({
                type: 'GET',
                url: '../backend/labor.php?id=<?php echo $labor_id; ?>',
                dataType: 'json',
                success: function(data) {
                    $('#name').val(data.name);
                    $('#description').val(data.description);
                }
            });

            // Submit form using AJAX PUT request
            $('#edit-labor-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/labor.php',
                    data: {
                        id: <?php echo $labor_id; ?>,
                        name: $('#name').val(),
                        description: $('#description').val()
                    },
                    success: function() {
                        window.location.href = 'list_labor.php';
                    }
                });
            });
        });
    </script>
</body>
</html>