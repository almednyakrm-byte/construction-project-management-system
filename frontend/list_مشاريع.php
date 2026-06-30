**list_مشاريع.php**

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
    <title>مشاريع</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2d3748;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #2d3748;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-indigo-500 font-bold">مرحباً <?= $_SESSION['username'] ?></span>
        <a href="logout.php" class="text-red-500">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">مشاريع</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="window.location.href='create_مشاريع.php'">إضافة مشروع جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button class="bg-slate-900 hover:bg-slate-800 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم المشروع</th>
                    <th>تاريخ الإنشاء</th>
                    <th>حالة المشروع</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be displayed here -->
            </tbody>
        </table>
    </div>

    <script>
        // Fetch records from backend
        async function fetchRecords() {
            try {
                const response = await fetch('../backend/مشاريع.php', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();
                displayRecords(data);
            } catch (error) {
                console.error(error);
            }
        }

        // Display records
        function displayRecords(data) {
            const records = document.getElementById('records');
            records.innerHTML = '';
            data.forEach(record => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${record.name}</td>
                    <td>${record.created_at}</td>
                    <td>${record.status}</td>
                    <td>
                        <a href="edit_مشاريع.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                        <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                    </td>
                `;
                records.appendChild(row);
            });
        }

        // Search records
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchValue = searchInput.value.trim();
            if (searchValue) {
                fetchRecords().then(() => {
                    const records = document.getElementById('records');
                    const rows = records.children;
                    for (let i = 0; i < rows.length; i++) {
                        const row = rows[i];
                        const cells = row.children;
                        let match = false;
                        for (let j = 0; j < cells.length; j++) {
                            const cell = cells[j];
                            if (cell.textContent.toLowerCase().includes(searchValue.toLowerCase())) {
                                match = true;
                                break;
                            }
                        }
                        if (match) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });
            } else {
                fetchRecords();
            }
        }

        // Delete record
        async function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا المشروع؟')) {
                try {
                    const response = await fetch('../backend/مشاريع.php', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id })
                    });
                    if (response.ok) {
                        fetchRecords();
                    } else {
                        alert('حدث خطأ أثناء حذف المشروع');
                    }
                } catch (error) {
                    console.error(error);
                }
            }
        }

        // Initialize records
        fetchRecords();
    </script>
</body>
</html>

This code includes the following features:

1. Header navigation with links to index.php, current user info, and logout.
2. Table showing list of records with actions: Edit (link to edit_مشاريع.php?id=X), Delete (AJAX call to backend).
3. 'Add New Item' button linking to create_مشاريع.php.
4. Search bar filtering elements in real-time.
5. AJAX Javascript (Fetch API) fetching list records from '../backend/مشاريع.php' (GET) and DELETE requests.

Note: This code assumes that the backend API is implemented in PHP and is located at '../backend/مشاريع.php'. The API should return a JSON response with the list of records.