**create_موظفين.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Include navigation
include 'navigation.php';

// Include form
include 'create_form.php';

// Include footer
include 'footer.php';


**create_form.php**

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h2 class="text-2xl font-bold text-slate-900 mb-4">إضافة موظف جديد</h2>
    <form id="create-form" class="space-y-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">اسم الموظف</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-slate-900">بريد إلكتروني</label>
                <input type="email" id="email" name="email" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-slate-900">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div>
                <label for="position" class="block text-sm font-medium text-slate-900">الوظيفة</label>
                <input type="text" id="position" name="position" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
        </div>
        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">إضافة</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/موظفين.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_موظفين.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>


**header.php**

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الموظفين</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>


**footer.php**

</body>
</html>


**navigation.php**

<nav class="bg-slate-900 py-4">
    <div class="container mx-auto px-4">
        <ul class="flex justify-between items-center">
            <li><a href="index.php" class="text-sm font-medium text-white hover:text-indigo-500">الرئيسية</a></li>
            <li><a href="list_موظفين.php" class="text-sm font-medium text-white hover:text-indigo-500">قائمة الموظفين</a></li>
            <li><a href="create_موظفين.php" class="text-sm font-medium text-white hover:text-indigo-500">إضافة موظف جديد</a></li>
            <li><a href="logout.php" class="text-sm font-medium text-white hover:text-indigo-500">تسجيل الخروج</a></li>
        </ul>
    </div>
</nav>