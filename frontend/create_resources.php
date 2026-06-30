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
$mod_slug = 'resources';

// Define page title
$page_title = 'Create Resources';

// Include header
require_once 'header.php';
?>

<!-- Content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
    <h1 class="text-3xl text-gray-500 mb-4">Create Resources</h1>
    <form id="create-resources-form">
        <div class="mb-4">
            <label for="title" class="block text-gray-500 text-sm font-bold mb-2">Title</label>
            <input type="text" id="title" name="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-gray-500 text-sm font-bold mb-2">Description</label>
            <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
        </div>
        <div class="mb-4">
            <label for="url" class="block text-gray-500 text-sm font-bold mb-2">URL</label>
            <input type="url" id="url" name="url" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="category" class="block text-gray-500 text-sm font-bold mb-2">Category</label>
            <select id="category" name="category" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                <option value="">Select Category</option>
                <option value="article">Article</option>
                <option value="video">Video</option>
                <option value="book">Book</option>
            </select>
        </div>
        <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Create Resource</button>
    </form>
</div>

<!-- JavaScript -->
<script>
    $(document).ready(function() {
        $('#create-resources-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/resources.php',
                data: $(this).serialize(),
                success: function(data) {
                    window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                }
            });
        });
    });
</script>

<?php
// Include footer
require_once 'footer.php';
?>