**create_schedules.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $start_date = trim($_POST['start_date']);
    $end_date = trim($_POST['end_date']);

    // Check for empty fields
    if (empty($name) || empty($description) || empty($start_date) || empty($end_date)) {
        $error = 'Please fill in all fields.';
    } else {
        // Insert new schedule record
        $query = "INSERT INTO schedules (name, description, start_date, end_date) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("ssss", $name, $description, $start_date, $end_date);
        $result = $stmt->execute();

        if ($result) {
            $success = 'Schedule created successfully.';
            header('Location: list_schedules.php');
            exit;
        } else {
            $error = 'Failed to create schedule.';
        }
    }
}

// Include header
require_once '../includes/header.php';

// Include premium Tailwind UI form
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-8 xl:p-10 2xl:p-12">
    <div class="bg-gray-100 rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-10 2xl:p-12">
        <h2 class="text-lg font-bold text-gray-500">Create New Schedule</h2>
        <form id="create-schedule-form" method="post">
            <div class="mb-4">
                <label for="name" class="block text-sm font-bold text-gray-500">Schedule Name:</label>
                <input type="text" id="name" name="name" class="w-full px-3 py-2 text-sm text-gray-700 border border-gray-500 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-bold text-gray-500">Description:</label>
                <textarea id="description" name="description" class="w-full px-3 py-2 text-sm text-gray-700 border border-gray-500 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500" required></textarea>
            </div>
            <div class="mb-4">
                <label for="start_date" class="block text-sm font-bold text-gray-500">Start Date:</label>
                <input type="date" id="start_date" name="start_date" class="w-full px-3 py-2 text-sm text-gray-700 border border-gray-500 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500" required>
            </div>
            <div class="mb-4">
                <label for="end_date" class="block text-sm font-bold text-gray-500">End Date:</label>
                <input type="date" id="end_date" name="end_date" class="w-full px-3 py-2 text-sm text-gray-700 border border-gray-500 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500" required>
            </div>
            <button type="submit" id="submit" name="submit" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-lg">Create Schedule</button>
            <?php if (isset($error)) : ?>
                <p class="text-red-500 text-sm mt-2"><?= $error ?></p>
            <?php elseif (isset($success)) : ?>
                <p class="text-green-500 text-sm mt-2"><?= $success ?></p>
            <?php endif; ?>
        </form>
    </div>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>

<script>
    $(document).ready(function() {
        $('#create-schedule-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/schedules.php',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        window.location.href = 'list_schedules.php';
                    } else {
                        alert(response.error);
                    }
                }
            });
        });
    });
</script>


**schedules.php (backend)**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $start_date = trim($_POST['start_date']);
    $end_date = trim($_POST['end_date']);

    // Insert new schedule record
    $query = "INSERT INTO schedules (name, description, start_date, end_date) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ssss", $name, $description, $start_date, $end_date);
    $result = $stmt->execute();

    if ($result) {
        $success = 'Schedule created successfully.';
        echo json_encode(['success' => true, 'message' => $success]);
    } else {
        $error = 'Failed to create schedule.';
        echo json_encode(['success' => false, 'error' => $error]);
    }
}