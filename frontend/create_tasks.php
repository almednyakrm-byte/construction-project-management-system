**create_tasks.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Include navigation
include 'navigation.php';

// Include form
include 'create_task_form.php';

// Include footer
include 'footer.php';


**create_task_form.php**

<!-- Create Task Form -->
<div class="max-w-md mx-auto p-8 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-orange-500 mb-4">Create New Task</h2>
    <form id="create-task-form" class="space-y-4">
        <div>
            <label for="title" class="block text-gray-200 text-sm font-bold mb-2">Title</label>
            <input type="text" id="title" name="title" class="block w-full p-2 text-gray-200 border-gray-200 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500" required>
        </div>
        <div>
            <label for="description" class="block text-gray-200 text-sm font-bold mb-2">Description</label>
            <textarea id="description" name="description" class="block w-full p-2 text-gray-200 border-gray-200 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500" required></textarea>
        </div>
        <div>
            <label for="due_date" class="block text-gray-200 text-sm font-bold mb-2">Due Date</label>
            <input type="date" id="due_date" name="due_date" class="block w-full p-2 text-gray-200 border-gray-200 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500" required>
        </div>
        <div>
            <label for="priority" class="block text-gray-200 text-sm font-bold mb-2">Priority</label>
            <select id="priority" name="priority" class="block w-full p-2 text-gray-200 border-gray-200 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500" required>
                <option value="">Select Priority</option>
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
            </select>
        </div>
        <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-lg">Create Task</button>
    </form>
</div>

<!-- AJAX Script -->
<script>
    $(document).ready(function() {
        $('#create-task-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/tasks.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_tasks.php';
                    } else {
                        alert('Error creating task');
                    }
                }
            });
        });
    });
</script>


**tasks.php (backend)**

<?php
// Include database connection
include 'db_connection.php';

// Check if form data is submitted
if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['due_date']) && isset($_POST['priority'])) {
    // Prepare SQL query
    $query = "INSERT INTO tasks (title, description, due_date, priority, created_by) VALUES (?, ?, ?, ?, ?)";
    
    // Bind parameters
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $_POST['title'], $_POST['description'], $_POST['due_date'], $_POST['priority'], $_SESSION['username']);
    
    // Execute query
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'Error creating task';
    }
    
    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>