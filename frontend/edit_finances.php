<?php
// edit_finances.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_finances.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Finances</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4 pt-6 mt-10 bg-gray-100 rounded-lg shadow-md">
        <h2 class="text-2xl text-gray-500 mb-4">Edit Finances</h2>
        <form id="edit-finances-form">
            <div class="mb-4">
                <label for="date" class="block text-gray-500 text-sm font-bold mb-2">Date</label>
                <input type="date" id="date" name="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="amount" class="block text-gray-500 text-sm font-bold mb-2">Amount</label>
                <input type="number" id="amount" name="amount" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-500 text-sm font-bold mb-2">Description</label>
                <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
            </div>
            <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">Update</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            var id = '<?php echo $id; ?>';
            $.ajax({
                type: 'GET',
                url: '../backend/finances.php?id=' + id,
                dataType: 'json',
                success: function(data) {
                    $('#date').val(data.date);
                    $('#amount').val(data.amount);
                    $('#description').val(data.description);
                }
            });

            $('#edit-finances-form').submit(function(e) {
                e.preventDefault();
                var formData = {
                    'id': id,
                    'date': $('#date').val(),
                    'amount': $('#amount').val(),
                    'description': $('#description').val()
                };
                $.ajax({
                    type: 'PUT',
                    url: '../backend/finances.php',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    success: function(data) {
                        window.location.href = 'list_finances.php';
                    }
                });
            });
        });
    </script>
</body>
</html>