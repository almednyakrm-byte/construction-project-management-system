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
$mod_slug = 'labor';

// Page title
$page_title = 'Create Labor';

// Include header
require_once 'header.php';
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto">
    <div class="container mx-auto px-6 py-8">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Create Labor</h3>
        <form id="create-labor-form" class="mt-6 space-y-6">
            <div class="flex flex-col">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-200 py-2 pl-3 pr-10 text-base text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="flex flex-col">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" class="mt-1 block w-full rounded-md border-gray-200 py-2 pl-3 pr-10 text-base text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"></textarea>
            </div>
            <div class="flex flex-col">
                <label for="hourly_rate" class="block text-sm font-medium text-gray-700">Hourly Rate</label>
                <input type="number" id="hourly_rate" name="hourly_rate" class="mt-1 block w-full rounded-md border-gray-200 py-2 pl-3 pr-10 text-base text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
            </div>
            <button type="submit" class="inline-flex w-full items-center rounded-md border border-transparent bg-indigo-500 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">Create Labor</button>
        </form>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('#create-labor-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/labor.php',
                data: $(this).serialize(),
                success: function(data) {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                }
            });
        });
    });
</script>