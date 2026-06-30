**edit_مشاريع.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get project ID from URL
$id = $_GET['id'];

// Fetch existing project details via AJAX
$js = "
<script>
    $(document).ready(function() {
        $.get('../backend/مشاريع.php?id=" . $id . "')
            .done(function(data) {
                $('#project_name').val(data.project_name);
                $('#project_description').val(data.project_description);
                $('#project_status').val(data.project_status);
            })
            .fail(function() {
                alert('Error fetching project details');
            });
    });
</script>
";

// Display form
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .slate-900 { color: #1a1d23; }
        .indigo-500 { color: #6b6bcf; }
    </style>
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Edit Project</h1>
        <form id="edit-project-form" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="project_name" class="block text-sm font-bold mb-2">Project Name:</label>
                <input type="text" id="project_name" name="project_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="project_description" class="block text-sm font-bold mb-2">Project Description:</label>
                <textarea id="project_description" name="project_description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
            </div>
            <div class="mb-4">
                <label for="project_status" class="block text-sm font-bold mb-2">Project Status:</label>
                <select id="project_status" name="project_status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-project-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/مشاريع.php',
                    data: $(this).serialize() + '&id=' + <?php echo $id; ?>,
                    success: function(data) {
                        if (data.success) {
                            window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                        } else {
                            alert('Error updating project');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error updating project');
                    }
                });
            });
        });
    </script>
    <?php echo $js; ?>
</body>
</html>


**Note:** Replace `$mod_slug` with the actual slug of the module.