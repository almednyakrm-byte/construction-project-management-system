**list_فنيون.php**

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
    <title>فنيون</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
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
            text-align: center;
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
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(13, 130, 184, 0.5);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-white">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 mb-4">فنيون</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_فنيون.php'">إضافة جديد</button>
        <div class="flex justify-between items-center mb-4">
            <input type="search" class="search-bar" id="search-input" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم</th>
                    <th>تليفون</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records-table">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?php echo $record['اسم']; ?></td>
                        <td><?php echo $record['تليفون']; ?></td>
                        <td>
                            <a href="edit_فنيون.php?id=<?php echo $record['id']; ?>" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(<?php echo $record['id']; ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const searchInput = document.getElementById('search-input');
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetchRecords(searchQuery);
            } else {
                fetchRecords();
            }
        }

        function fetchRecords(searchQuery = '') {
            const url = '../backend/فنيون.php';
            const method = 'GET';
            const headers = {
                'Content-Type': 'application/json'
            };
            const body = JSON.stringify({ searchQuery });
            fetch(url, { method, headers, body })
                .then(response => response.json())
                .then(data => {
                    const recordsTable = document.getElementById('records-table');
                    recordsTable.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.اسم}</td>
                            <td>${record.تليفون}</td>
                            <td>
                                <a href="edit_فنيون.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                                <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        recordsTable.appendChild(row);
                    });
                })
                .catch(error => console.error(error));
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                const url = '../backend/فنيون.php';
                const method = 'DELETE';
                const headers = {
                    'Content-Type': 'application/json'
                };
                const body = JSON.stringify({ id });
                fetch(url, { method, headers, body })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            fetchRecords();
                        } else {
                            alert('حدث خطأ أثناء حذف السجل');
                        }
                    })
                    .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

<?php
function fetchRecords() {
    // Fetch records from backend
    $url = '../backend/فنيون.php';
    $method = 'GET';
    $headers = array('Content-Type' => 'application/json');
    $body = array();
    $response = json_decode(file_get_contents($url, false, stream_context_create(array('http' => array('method' => $method, 'header' => json_encode($headers), 'content' => json_encode($body)))), 0, 10), true);
    return $response['records'];
}
?>


**backend/فنيون.php**

<?php
// Fetch records from database
$records = array();
// Simulating database query
$records = array(
    array('id' => 1, 'اسم' => 'فني 1', 'تليفون' => '0123456789'),
    array('id' => 2, 'اسم' => 'فني 2', 'تليفون' => '0987654321'),
    array('id' => 3, 'اسم' => 'فني 3', 'تليفون' => '1234567890'),
);

// Search query
$searchQuery = isset($_GET['searchQuery']) ? $_GET['searchQuery'] : '';

// Filter records based on search query
if ($searchQuery) {
    $records = array_filter($records, function ($record) use ($searchQuery) {
        return strpos($record['اسم'], $searchQuery) !== false || strpos($record['تليفون'], $searchQuery) !== false;
    });
}

// Output records
header('Content-Type: application/json');
echo json_encode(array('records' => $records));
?>


Note: This code is a basic example and should be adapted to your specific needs and database schema. Additionally, you should ensure that your backend API is properly secured and validated to prevent SQL injection and other security vulnerabilities.