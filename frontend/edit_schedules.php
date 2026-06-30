**edit_schedules.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get schedule ID from URL
$schedule_id = $_GET['id'];

// Fetch schedule details via AJAX
$js = "
<script>
    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: '../backend/schedules.php?id=" . $schedule_id . "',
            dataType: 'json',
            success: function(data) {
                $('#schedule_name').val(data.name);
                $('#schedule_description').val(data.description);
                $('#schedule_start_date').val(data.start_date);
                $('#schedule_end_date').val(data.end_date);
            }
        });
    });
</script>
";

// Include header and footer
include 'header.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-8">
        <h2 class="text-lg font-bold text-gray-500 mb-4">Edit Schedule</h2>
        <form id="edit-schedule-form">
            <div class="mb-4">
                <label for="schedule_name" class="block text-sm font-bold text-gray-500 mb-2">Schedule Name</label>
                <input type="text" id="schedule_name" name="schedule_name" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-500 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div class="mb-4">
                <label for="schedule_description" class="block text-sm font-bold text-gray-500 mb-2">Schedule Description</label>
                <textarea id="schedule_description" name="schedule_description" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-500 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500"></textarea>
            </div>
            <div class="mb-4">
                <label for="schedule_start_date" class="block text-sm font-bold text-gray-500 mb-2">Schedule Start Date</label>
                <input type="date" id="schedule_start_date" name="schedule_start_date" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-500 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div class="mb-4">
                <label for="schedule_end_date" class="block text-sm font-bold text-gray-500 mb-2">Schedule End Date</label>
                <input type="date" id="schedule_end_date" name="schedule_end_date" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-500 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-lg">Update Schedule</button>
        </form>
    </div>
</div>

<?php
// Include footer
include 'footer.php';
?>

<script>
    $(document).ready(function() {
        $('#edit-schedule-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'PUT',
                url: '../backend/schedules.php',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(data) {
                    if (data.status === 'success') {
                        window.location.href = 'list_{mod_slug}.php';
                    } else {
                        alert('Error updating schedule');
                    }
                }
            });
        });
    });
</script>


**header.php**

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Schedule</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body>
    <?php echo $js; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


**footer.php**

</html>


**schedules.php (backend)**

<?php
// Check if schedule ID is set
if (isset($_GET['id'])) {
    // Get schedule ID
    $schedule_id = $_GET['id'];

    // Fetch schedule details from database
    $schedule = get_schedule($schedule_id);

    // Return schedule details as JSON
    echo json_encode($schedule);
} elseif (isset($_POST['schedule_id'])) {
    // Get schedule ID
    $schedule_id = $_POST['schedule_id'];

    // Update schedule details in database
    update_schedule($schedule_id, $_POST);

    // Return success message as JSON
    echo json_encode(array('status' => 'success'));
} else {
    // Return error message as JSON
    echo json_encode(array('status' => 'error'));
}


**get_schedule function (backend)**

function get_schedule($schedule_id) {
    // Connect to database
    $db = connect_to_database();

    // Query database for schedule details
    $query = "SELECT * FROM schedules WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $schedule_id);
    $stmt->execute();

    // Fetch schedule details
    $schedule = $stmt->fetch();

    // Return schedule details
    return $schedule;
}


**update_schedule function (backend)**

function update_schedule($schedule_id, $data) {
    // Connect to database
    $db = connect_to_database();

    // Query database to update schedule details
    $query = "UPDATE schedules SET name = :name, description = :description, start_date = :start_date, end_date = :end_date WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $schedule_id);
    $stmt->bindParam(':name', $data['schedule_name']);
    $stmt->bindParam(':description', $data['schedule_description']);
    $stmt->bindParam(':start_date', $data['schedule_start_date']);
    $stmt->bindParam(':end_date', $data['schedule_end_date']);
    $stmt->execute();
}


Note: This code assumes that you have a `connect_to_database` function that connects to your database and a `get_schedule` function that fetches schedule details from the database. You will need to implement these functions according to your database schema and requirements.