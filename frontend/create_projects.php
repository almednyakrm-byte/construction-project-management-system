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

// Define module slug
$mod_slug = 'projects';

// Define page title
$page_title = 'Create Project';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-5xl mx-auto p-4 sm:p-6 md:p-8 bg-white rounded-xl shadow-md mt-10">
        <h2 class="text-lg font-medium text-gray-500"><?php echo $page_title; ?></h2>
        <form id="create-project-form">
            <div class="mt-4">
                <label for="project_name" class="block text-sm font-medium text-gray-500">Project Name</label>
                <input type="text" id="project_name" name="project_name" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
            </div>
            <div class="mt-4">
                <label for="project_description" class="block text-sm font-medium text-gray-500">Project Description</label>
                <textarea id="project_description" name="project_description" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm"></textarea>
            </div>
            <div class="mt-4">
                <label for="project_start_date" class="block text-sm font-medium text-gray-500">Project Start Date</label>
                <input type="date" id="project_start_date" name="project_start_date" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
            </div>
            <div class="mt-4">
                <label for="project_end_date" class="block text-sm font-medium text-gray-500">Project End Date</label>
                <input type="date" id="project_end_date" name="project_end_date" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
            </div>
            <div class="mt-4">
                <label for="project_status" class="block text-sm font-medium text-gray-500">Project Status</label>
                <select id="project_status" name="project_status" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="mt-4">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-500 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">Create Project</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-project-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: '../backend/projects.php',
                    data: formData,
                    success: function(response) {
                        window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                    }
                });
            });
        });
    </script>
</body>
</html>