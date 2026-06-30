<?php
// Session check
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة مشاريع البناء</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(7.5px);
            -webkit-backdrop-filter: blur(7.5px);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
    </style>
</head>
<body class="bg-gray-500 h-screen">
    <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <div class="flex justify-between">
            <h1 class="text-3xl text-orange-500 font-bold">نظام إدارة مشاريع البناء</h1>
            <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل الخروج</button>
        </div>
        <div class="mt-12">
            <h2 class="text-2xl text-orange-500 font-bold">مرحباً!</h2>
            <p class="text-lg text-gray-200">مرحباً بك في نظام إدارة مشاريع البناء. يمكنك إدارة مشاريعك ومراقبة تقدمها من خلال هذه الصفحة.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-12">
            <div class="glass p-4 rounded">
                <h3 class="text-lg text-orange-500 font-bold">عدد المشاريع</h3>
                <p id="projects-count" class="text-2xl text-gray-200">0</p>
            </div>
            <div class="glass p-4 rounded">
                <h3 class="text-lg text-orange-500 font-bold">عدد الموارد</h3>
                <p id="resources-count" class="text-2xl text-gray-200">0</p>
            </div>
            <div class="glass p-4 rounded">
                <h3 class="text-lg text-orange-500 font-bold">إجمالي التكاليف</h3>
                <p id="costs-total" class="text-2xl text-gray-200">0</p>
            </div>
            <div class="glass p-4 rounded">
                <h3 class="text-lg text-orange-500 font-bold">عدد الجداول الزمنية</h3>
                <p id="schedules-count" class="text-2xl text-gray-200">0</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-12">
            <div class="glass p-4 rounded">
                <h3 class="text-lg text-orange-500 font-bold">إدارة المشاريع</h3>
                <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='projects.php'">إدارة المشاريع</button>
            </div>
            <div class="glass p-4 rounded">
                <h3 class="text-lg text-orange-500 font-bold">إدارة الموارد</h3>
                <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='resources.php'">إدارة الموارد</button>
            </div>
            <div class="glass p-4 rounded">
                <h3 class="text-lg text-orange-500 font-bold">إدارة التكاليف</h3>
                <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='costs.php'">إدارة التكاليف</button>
            </div>
            <div class="glass p-4 rounded">
                <h3 class="text-lg text-orange-500 font-bold">إدارة الجداول الزمنية</h3>
                <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='schedules.php'">إدارة الجداول الزمنية</button>
            </div>
        </div>
    </div>

    <script>
        // Fetch stats dynamically via Javascript API calls from the backend files
        fetch('api/projects.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('projects-count').innerText = data.count;
            });

        fetch('api/resources.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('resources-count').innerText = data.count;
            });

        fetch('api/costs.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('costs-total').innerText = data.total;
            });

        fetch('api/schedules.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('schedules-count').innerText = data.count;
            });
    </script>
</body>
</html>