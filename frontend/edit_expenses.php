<?php
// edit_expenses.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_expenses.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Expenses</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-4 bg-gray-200 rounded-lg shadow-md">
        <h2 class="text-orange-500 text-2xl font-bold mb-4">Edit Expenses</h2>
        <form id="edit-expenses-form">
            <div class="mb-4">
                <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Date</label>
                <input type="date" id="date" name="date" class="block w-full p-2 bg-gray-200 border border-gray-200 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div class="mb-4">
                <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">Amount</label>
                <input type="number" id="amount" name="amount" class="block w-full p-2 bg-gray-200 border border-gray-200 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 bg-gray-200 border border-gray-200 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500"></textarea>
            </div>
            <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-lg">Update Expenses</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            const id = '<?php echo $id; ?>';
            $.ajax({
                type: 'GET',
                url: '../backend/expenses.php?id=' + id,
                dataType: 'json',
                success: function(data) {
                    $('#date').val(data.date);
                    $('#amount').val(data.amount);
                    $('#description').val(data.description);
                }
            });

            $('#edit-expenses-form').submit(function(e) {
                e.preventDefault();
                const formData = {
                    id: id,
                    date: $('#date').val(),
                    amount: $('#amount').val(),
                    description: $('#description').val()
                };

                $.ajax({
                    type: 'PUT',
                    url: '../backend/expenses.php',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    success: function(data) {
                        window.location.href = 'list_expenses.php';
                    }
                });
            });
        });
    </script>
</body>
</html>