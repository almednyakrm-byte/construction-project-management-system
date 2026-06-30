**edit_teams.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get team ID from URL
$id = $_GET['id'];

// Fetch team details via AJAX
$teamDetails = json_decode(file_get_contents('../backend/teams.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Team</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+Dz00He7Rc7vnsL8th2IDt6CT0QirVO" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDzfgbWSSxoLHrNwNwOgKlmWHRsye" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.10/dist/sweetalert2.min.js"></script>
    <style>
        body {
            background-color: #f7f7f7;
        }
        .container {
            max-width: 500px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: #666;
        }
        .form-group input, .form-group select {
            width: 100%;
            height: 40px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .form-group input[type="submit"] {
            background-color: #ff9900;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
        .form-group input[type="submit"]:hover {
            background-color: #ffcc00;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Team</h2>
        <form id="edit-team-form">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $teamDetails['name']; ?>">
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description"><?php echo $teamDetails['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="status">
                    <option value="active" <?php if ($teamDetails['status'] == 'active') echo 'selected'; ?>>Active</option>
                    <option value="inactive" <?php if ($teamDetails['status'] == 'inactive') echo 'selected'; ?>>Inactive</option>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" value="Update Team">
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Fetch team details via AJAX
            $.ajax({
                type: 'GET',
                url: '../backend/teams.php?id=' + <?php echo $id; ?>,
                success: function(data) {
                    var teamDetails = JSON.parse(data);
                    $('#name').val(teamDetails.name);
                    $('#description').val(teamDetails.description);
                    $('#status').val(teamDetails.status);
                }
            });

            // Update team via AJAX
            $('#edit-team-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/teams.php',
                    data: formData,
                    success: function(data) {
                        Swal.fire({
                            title: 'Team updated successfully!',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(function() {
                            window.location.href = 'list_teams.php';
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error updating team!',
                            icon: 'error',
                            text: error,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>


**teams.php (backend)**

<?php
// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get team ID from URL
$id = $_GET['id'];

// Fetch team details
$query = "SELECT * FROM teams WHERE id = '$id'";
$result = $conn->query($query);

// Check if team exists
if ($result->num_rows > 0) {
    // Get team details
    $teamDetails = $result->fetch_assoc();
    echo json_encode($teamDetails);
} else {
    echo json_encode(array('error' => 'Team not found'));
}

// Close database connection
$conn->close();
?>


**teams.php (backend) - Update team**

<?php
// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get team ID from URL
$id = $_GET['id'];

// Get team details from form data
$name = $_POST['name'];
$description = $_POST['description'];
$status = $_POST['status'];

// Update team
$query = "UPDATE teams SET name = '$name', description = '$description', status = '$status' WHERE id = '$id'";
$conn->query($query);

// Close database connection
$conn->close();

// Redirect to list teams page
header('Location: list_teams.php');
exit;
?>