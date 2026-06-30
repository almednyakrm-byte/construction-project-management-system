<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
include '../backend/db.php';

// Define module slug
$mod_slug = 'مشروعات';

// Define form fields
$form_fields = array(
    'project_name' => '',
    'project_description' => '',
    'start_date' => '',
    'end_date' => '',
    'status' => '',
    'priority' => '',
    'assigned_to' => ''
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create مشروعات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-5xl mx-auto p-4 sm:p-6 md:p-8 bg-white rounded-xl shadow-md mt-10">
        <h2 class="text-lg text-slate-900 font-bold mb-4">Create مشروعات</h2>
        <form id="create-form">
            <div class="mb-4">
                <label for="project_name" class="block text-sm text-slate-900 font-bold mb-2">Project Name</label>
                <input type="text" id="project_name" name="project_name" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="project_description" class="block text-sm text-slate-900 font-bold mb-2">Project Description</label>
                <textarea id="project_description" name="project_description" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
            </div>
            <div class="mb-4">
                <label for="start_date" class="block text-sm text-slate-900 font-bold mb-2">Start Date</label>
                <input type="date" id="start_date" name="start_date" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="end_date" class="block text-sm text-slate-900 font-bold mb-2">End Date</label>
                <input type="date" id="end_date" name="end_date" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="status" class="block text-sm text-slate-900 font-bold mb-2">Status</label>
                <select id="status" name="status" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                    <option value="">Select Status</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="priority" class="block text-sm text-slate-900 font-bold mb-2">Priority</label>
                <select id="priority" name="priority" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                    <option value="">Select Priority</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="assigned_to" class="block text-sm text-slate-900 font-bold mb-2">Assigned To</label>
                <input type="text" id="assigned_to" name="assigned_to" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create مشروعات</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/مشروعات.php',
                    data: $(this).serialize(),
                    success: function() {
                        window.location.href = 'list_مشروعات.php';
                    }
                });
            });
        });
    </script>
</body>
</html>