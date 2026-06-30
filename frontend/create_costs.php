**create_costs.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Include navigation
include 'navigation.php';

// Include form
include 'create_costs_form.php';

// Include footer
include 'footer.php';


**create_costs_form.php**

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-8 xl:p-12 2xl:p-16">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-12 2xl:p-16">
        <h2 class="text-lg font-bold text-gray-500 mb-4">Create New Cost</h2>
        <form id="create-costs-form">
            <div class="mb-4">
                <label for="cost_name" class="block text-sm font-bold text-gray-500 mb-2">Cost Name:</label>
                <input type="text" id="cost_name" name="cost_name" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-500 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div class="mb-4">
                <label for="cost_description" class="block text-sm font-bold text-gray-500 mb-2">Cost Description:</label>
                <textarea id="cost_description" name="cost_description" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-500 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500"></textarea>
            </div>
            <div class="mb-4">
                <label for="cost_amount" class="block text-sm font-bold text-gray-500 mb-2">Cost Amount:</label>
                <input type="number" id="cost_amount" name="cost_amount" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-500 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div class="mb-4">
                <label for="cost_date" class="block text-sm font-bold text-gray-500 mb-2">Cost Date:</label>
                <input type="date" id="cost_date" name="cost_date" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-500 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">Create Cost</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-costs-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/costs.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_costs.php';
                    } else {
                        alert('Error creating cost');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error creating cost: ' + error);
                }
            });
        });
    });
</script>


**header.php** (example)

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Cost</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <?php include 'create_costs.php'; ?>
</body>
</html>


**footer.php** (example)

</div>
</body>
</html>


**navigation.php** (example)

<nav class="bg-gray-500 text-white p-4">
    <ul>
        <li><a href="list_costs.php" class="text-sm font-bold hover:text-orange-500">Costs List</a></li>
        <li><a href="create_costs.php" class="text-sm font-bold hover:text-orange-500">Create New Cost</a></li>
    </ul>
</nav>