**edit_مشروعات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get project ID from URL
$id = $_GET['id'];

// Fetch project details via AJAX
$project = json_decode(file_get_contents('../backend/مشروعات.php?id=' . $id), true);

// Check if project exists
if (empty($project)) {
    echo 'Project not found';
    exit;
}

// Set page title and mod slug
$page_title = 'Edit Project';
$mod_slug = 'projects';

// Include header
include 'header.php';
?>

<!-- Page content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12 2xl:p-12">
    <h1 class="text-3xl font-bold text-slate-900 mb-4"><?= $page_title ?></h1>

    <form id="edit-project-form" class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-8 2xl:p-8">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
            <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-700 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" value="<?= $project['name'] ?>">
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
            <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-700 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" rows="4"><?= $project['description'] ?></textarea>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Update Project</button>
    </form>
</div>

<!-- Include footer -->
<?php include 'footer.php'; ?>

<script>
    // Fetch project details via GET
    fetch('../backend/مشروعات.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('description').value = data.description;
        })
        .catch(error => console.error(error));

    // Submit form via AJAX PUT
    document.getElementById('edit-project-form').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch('../backend/مشروعات.php', {
            method: 'PUT',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
    });
</script>


**backend/مشروعات.php**

<?php
// Check if project ID is set
if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'Project ID not set']);
    exit;
}

// Get project ID
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed']);
    exit;
}

// Get project details
$stmt = $conn->prepare("SELECT * FROM مشروعات WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch project details
$project = $result->fetch_assoc();

// Close connection
$conn->close();

// Output project details
echo json_encode($project);