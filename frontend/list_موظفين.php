**list_موظفين.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>موظفين</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            color: #fff;
        }
        .header a {
            color: #fff;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .search-bar {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 300px;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
        }
        .search-bar button[type="submit"] {
            background-color: #1a1d23;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-bar button[type="submit"]:hover {
            background-color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="header flex justify-between items-center p-4">
        <a href="index.php"><i class="fas fa-arrow-left"></i> الرجوع إلى الرئيسية</a>
        <div class="flex items-center">
            <img src="profile-picture.jpg" alt="Profile Picture" class="w-10 h-10 rounded-full mr-2">
            <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded ml-2" onclick="location.href='logout.php'">تسجيل الخروج</button>
        </div>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">قائمة الموظفين</h1>
        <button class="bg-slate-900 hover:bg-slate-800 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_موظفين.php'">إضافة موظف جديد</button>
        <div class="search-bar flex justify-between items-center mb-4">
            <input type="search" id="search-input" placeholder="بحث...">
            <button type="submit" id="search-button">بحث</button>
        </div>
        <table class="table w-full">
            <thead>
                <tr>
                    <th>اسم الموظف</th>
                    <th>وظيفة</th>
                    <th>تاريخ الميلاد</th>
                    <th>حذف</th>
                    <th>تعديل</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Table rows will be populated here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search-input');
        const searchButton = document.getElementById('search-button');
        const tableBody = document.getElementById('table-body');

        searchButton.addEventListener('click', () => {
            const searchTerm = searchInput.value.trim();
            if (searchTerm) {
                fetch('../backend/موظفين.php', {
                    method: 'GET',
                    params: { search: searchTerm }
                })
                .then(response => response.json())
                .then(data => {
                    tableBody.innerHTML = '';
                    data.forEach(item => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${item.اسم_الموظف}</td>
                            <td>${item.وظيفة}</td>
                            <td>${item.تاريخ_الميلاد}</td>
                            <td>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteItem(${item.id})">حذف</button>
                            </td>
                            <td>
                                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='edit_موظفين.php?id=${item.id}'">تعديل</button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                });
            } else {
                fetch('../backend/موظفين.php')
                .then(response => response.json())
                .then(data => {
                    tableBody.innerHTML = '';
                    data.forEach(item => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${item.اسم_الموظف}</td>
                            <td>${item.وظيفة}</td>
                            <td>${item.تاريخ_الميلاد}</td>
                            <td>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteItem(${item.id})">حذف</button>
                            </td>
                            <td>
                                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='edit_موظفين.php?id=${item.id}'">تعديل</button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                });
            }
        });

        function deleteItem(id) {
            if (confirm('هل أنت متأكد من حذف هذا الموظف؟')) {
                fetch('../backend/موظفين.php', {
                    method: 'DELETE',
                    params: { id: id }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف الموظف بنجاح');
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف الموظف');
                    }
                });
            }
        }
    </script>
</body>
</html>

Note: This code assumes that you have a backend PHP script (`../backend/موظفين.php`) that handles the GET and DELETE requests. The `deleteItem` function sends a DELETE request to the backend to delete the specified item. The `searchButton` click event handler sends a GET request to the backend with the search term as a parameter.