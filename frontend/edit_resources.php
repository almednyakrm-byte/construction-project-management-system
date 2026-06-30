<?php
// edit_resources.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_resources.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Resource</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-gray-500">Edit Resource</h2>
        <form id="edit-resource-form">
            <div class="mt-4">
                <label for="name" class="block text-gray-500">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 border border-gray-500 rounded-lg">
            </div>
            <div class="mt-4">
                <label for="description" class="block text-gray-500">Description:</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 border border-gray-500 rounded-lg"></textarea>
            </div>
            <div class="mt-4">
                <label for="url" class="block text-gray-500">URL:</label>
                <input type="text" id="url" name="url" class="block w-full p-2 mt-1 border border-gray-500 rounded-lg">
            </div>
            <button type="submit" class="w-full p-2 mt-4 bg-orange-500 text-white rounded-lg hover:bg-orange-700">Update Resource</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('edit-resource-form');
        const id = <?php echo $id; ?>;

        // Fetch existing record details
        fetch(`../backend/resources.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('name').value = data.name;
                document.getElementById('description').value = data.description;
                document.getElementById('url').value = data.url;
            });

        // Submit form with AJAX PUT request
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch(`../backend/resources.php`, {
                method: 'PUT',
                body: JSON.stringify({
                    id: id,
                    name: formData.get('name'),
                    description: formData.get('description'),
                    url: formData.get('url')
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_resources.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>