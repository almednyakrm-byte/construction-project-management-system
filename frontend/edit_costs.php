**edit_costs.php**

<?php
session_start();

// Validate session
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Fetch existing record details
$data = json_decode(file_get_contents('../backend/costs.php?id=' . $id), true);

// Check if record exists
if (empty($data)) {
    header('Location: list_mod_slug.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Costs</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-gray-500 mb-4">Edit Costs</h2>
        <form id="edit-costs-form">
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-500 mb-2" for="name">Name:</label>
                <input class="w-full px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500" type="text" id="name" name="name" value="<?= $data['name'] ?>">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-500 mb-2" for="amount">Amount:</label>
                <input class="w-full px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500" type="number" id="amount" name="amount" value="<?= $data['amount'] ?>">
            </div>
            <button class="px-4 py-2 text-sm text-white bg-orange-500 rounded-lg hover:bg-orange-700 focus:outline-none focus:ring-orange-500 focus:border-orange-500" type="submit">Update</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-costs-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/costs.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_mod_slug.php';
                        } else {
                            alert('Error updating costs');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**costs.php (backend)**

<?php
// Connect to database
$conn = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');

// Get ID from URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Fetch existing record details
$stmt = $conn->prepare('SELECT * FROM costs WHERE id = :id');
$stmt->bindParam(':id', $id);
$stmt->execute();
$data = $stmt->fetch();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($data);
?>


**Note:** Replace `database_name`, `username`, and `password` with your actual database credentials. Also, replace `list_mod_slug.php` with the actual URL of the page you want to redirect to after updating the costs.