**edit_موظفين.php**

<?php
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get the ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/موظفين.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Error: Record not found.';
    exit;
}

// Set page title and mod slug
$pageTitle = 'تعديل موظف';
$modSlug = 'موظفين';

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6">
    <h1 class="text-3xl font-bold text-slate-900 mb-4"><?= $pageTitle ?></h1>

    <form id="edit-form" class="bg-white rounded shadow-md p-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">اسم الموظف</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['name'] ?>">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-slate-900">بريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['email'] ?>">
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-slate-900">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['phone'] ?>">
            </div>
            <div>
                <label for="position" class="block text-sm font-medium text-slate-900">وظيفة</label>
                <input type="text" id="position" name="position" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['position'] ?>">
            </div>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">تعديل</button>
    </form>
</div>

<script>
    // Fetch existing record details via GET
    fetch('../backend/موظفين.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            document.getElementById('name').value = data.name;
            document.getElementById('email').value = data.email;
            document.getElementById('phone').value = data.phone;
            document.getElementById('position').value = data.position;
        })
        .catch(error => console.error(error));

    // Submit form via AJAX PUT request
    document.getElementById('edit-form').addEventListener('submit', event => {
        event.preventDefault();

        const formData = new FormData(event.target);
        const id = <?= $id ?>;

        fetch('../backend/موظفين.php', {
            method: 'PUT',
            body: formData,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_<?= $modSlug ?>.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**backend/موظفين.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo 'Error: ID not set.';
    exit;
}

// Get the ID
$id = $_GET['id'];

// Fetch existing record details from database
$existingRecord = getRecordFromDatabase($id);

// Output JSON response
echo json_encode($existingRecord);


**backend/update_موظفين.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo 'Error: ID not set.';
    exit;
}

// Get the ID
$id = $_GET['id'];

// Get the form data
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$position = $_POST['position'];

// Update the record in the database
updateRecordInDatabase($id, $name, $email, $phone, $position);

// Output JSON response
echo json_encode(['success' => true]);


Note: This code assumes that you have a `getRecordFromDatabase` function and an `updateRecordInDatabase` function in your backend code that handle the database operations. You should replace these functions with your actual database code.