**create_مشاريع.php**

<?php
// Session validation
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
require_once 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-12">
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة مشروع جديد</h2>
        <form id="create-project-form" class="space-y-4">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label for="project_name" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">اسم المشروع</label>
                    <input type="text" id="project_name" name="project_name" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="اسم المشروع">
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label for="project_description" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">وصف المشروع</label>
                    <textarea id="project_description" name="project_description" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="وصف المشروع"></textarea>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label for="project_start_date" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">تاريخ بداية المشروع</label>
                    <input type="date" id="project_start_date" name="project_start_date" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="تاريخ بداية المشروع">
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label for="project_end_date" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">تاريخ نهاية المشروع</label>
                    <input type="date" id="project_end_date" name="project_end_date" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="تاريخ نهاية المشروع">
                </div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة المشروع</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-project-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/مشاريع.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_مشاريع.php';
                    } else {
                        alert('Error adding project');
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
require_once 'footer.php';
?>


**backend/مشاريع.php**

<?php
// Include database connection
require_once 'db.php';

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input data
    $project_name = sanitize($_POST['project_name']);
    $project_description = sanitize($_POST['project_description']);
    $project_start_date = sanitize($_POST['project_start_date']);
    $project_end_date = sanitize($_POST['project_end_date']);

    // Insert data into database
    $query = "INSERT INTO مشاريع (project_name, project_description, project_start_date, project_end_date) VALUES ('$project_name', '$project_description', '$project_start_date', '$project_end_date')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo 'success';
    } else {
        echo 'Error adding project';
    }
}

// Function to sanitize input data
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>