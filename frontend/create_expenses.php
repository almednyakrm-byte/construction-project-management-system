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
$mod_slug = 'expenses';

// Page title
$page_title = 'Create Expenses';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-5xl mx-auto p-4 sm:p-6 md:p-8">
        <h1 class="text-3xl text-orange-500 font-bold mb-4"><?= $page_title ?></h1>
        <form id="create-expenses-form">
            <div class="mb-4">
                <label for="date" class="block text-gray-200 text-sm font-medium mb-2">Date</label>
                <input type="date" id="date" name="date" class="block w-full p-2 pl-10 text-gray-200 bg-gray-50 border border-gray-200 rounded-md focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div class="mb-4">
                <label for="category" class="block text-gray-200 text-sm font-medium mb-2">Category</label>
                <select id="category" name="category" class="block w-full p-2 pl-10 text-gray-200 bg-gray-50 border border-gray-200 rounded-md focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Select Category</option>
                    <option value="rent">Rent</option>
                    <option value="utilities">Utilities</option>
                    <option value="food">Food</option>
                    <option value="transportation">Transportation</option>
                    <option value="entertainment">Entertainment</option>
                    <option value="miscellaneous">Miscellaneous</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="amount" class="block text-gray-200 text-sm font-medium mb-2">Amount</label>
                <input type="number" id="amount" name="amount" class="block w-full p-2 pl-10 text-gray-200 bg-gray-50 border border-gray-200 rounded-md focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-200 text-sm font-medium mb-2">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-gray-200 bg-gray-50 border border-gray-200 rounded-md focus:ring-orange-500 focus:border-orange-500"></textarea>
            </div>
            <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-md">Create Expenses</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-expenses-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/expenses.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        window.location.href = 'list_<?= $mod_slug ?>.php';
                    }
                });
            });
        });
    </script>
</body>
</html>