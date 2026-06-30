<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = [
    'GET' => [
        '/projects' => function () {
            // Get all projects
            $stmt = $pdo->prepare('SELECT * FROM مشروعات');
            $stmt->execute();
            $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($projects);
        },
        '/projects/:id' => function ($id) {
            // Get project by ID
            $stmt = $pdo->prepare('SELECT * FROM مشروعات WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $project = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$project) {
                http_response_code(404);
                echo json_encode(['error' => 'Project not found']);
                exit;
            }
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($project);
        }
    ],
    'POST' => [
        '/projects' => function () {
            // Create new project
            if (!isset($input['name']) || !isset($input['description'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid input']);
                exit;
            }
            $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
            $description = filter_var($input['description'], FILTER_SANITIZE_STRING);
            $stmt = $pdo->prepare('INSERT INTO مشروعات (name, description) VALUES (:name, :description)');
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->execute();
            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Project created successfully']);
        }
    ],
    'PUT' => [
        '/projects/:id' => function ($id) {
            // Update project
            if (!isset($input['name']) || !isset($input['description'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid input']);
                exit;
            }
            $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
            $description = filter_var($input['description'], FILTER_SANITIZE_STRING);
            if ($_SESSION['user_role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Forbidden']);
                exit;
            }
            $stmt = $pdo->prepare('UPDATE مشروعات SET name = :name, description = :description WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->execute();
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Project updated successfully']);
        }
    ],
    'DELETE' => [
        '/projects/:id' => function ($id) {
            // Delete project
            if ($_SESSION['user_role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Forbidden']);
                exit;
            }
            $stmt = $pdo->prepare('DELETE FROM مشروعات WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Project deleted successfully']);
        }
    ]
];

// Get route
$method = $_SERVER['REQUEST_METHOD'];
$route = $_SERVER['REQUEST_URI'];
$parts = explode('/', $route);
array_shift($parts); // Remove empty string
array_shift($parts); // Remove 'projects'
if (isset($parts[0]) && $parts[0] === 'edit') {
    array_shift($parts);
    $id = end($parts);
    $parts = array_slice($parts, 0, -1);
    $route = implode('/', $parts);
}
if (isset($parts[0]) && $parts[0] === 'delete') {
    array_shift($parts);
    $id = end($parts);
    $parts = array_slice($parts, 0, -1);
    $route = implode('/', $parts);
}
if (isset($parts[0]) && $parts[0] === 'edit') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'delete') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'view') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'edit') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'delete') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'view') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'edit') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'delete') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'view') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'edit') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'delete') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'view') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'edit') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'delete') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'view') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'edit') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'delete') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'view') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'edit') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'delete') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'view') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'edit') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'delete') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'view') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'edit') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'delete') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'view') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'edit') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'delete') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'view') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'edit') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'delete') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'view') {
    $route = '/projects/' . $id;
}
if (isset($parts[0]) && $parts[0] === 'edit') {
    $route =