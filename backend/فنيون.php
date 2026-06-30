<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = [
    'GET' => [
        '/fniun' => function() {
            $stmt = $pdo->prepare('SELECT * FROM fniun');
            $stmt->execute();
            return json_encode($stmt->fetchAll());
        },
        '/fniun/:id' => function($id) {
            $stmt = $pdo->prepare('SELECT * FROM fniun WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return json_encode($stmt->fetch());
        }
    ],
    'POST' => [
        '/fniun' => function() {
            // Validate input data
            if (!isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid input']);
                exit;
            }

            // Sanitize input data
            $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
            $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
            $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);

            // Insert data into database
            $stmt = $pdo->prepare('INSERT INTO fniun (name, email, phone) VALUES (:name, :email, :phone)');
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->execute();

            // Return success response
            http_response_code(201);
            echo json_encode(['message' => 'Fniun created successfully']);
        }
    ],
    'PUT' => [
        '/fniun/:id' => function($id) {
            // Validate input data
            if (!isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid input']);
                exit;
            }

            // Sanitize input data
            $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
            $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
            $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);

            // Check if user is admin
            if ($_SESSION['role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Forbidden']);
                exit;
            }

            // Update data in database
            $stmt = $pdo->prepare('UPDATE fniun SET name = :name, email = :email, phone = :phone WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->execute();

            // Return success response
            http_response_code(200);
            echo json_encode(['message' => 'Fniun updated successfully']);
        }
    ],
    'DELETE' => [
        '/fniun/:id' => function($id) {
            // Check if user is admin
            if ($_SESSION['role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Forbidden']);
                exit;
            }

            // Delete data from database
            $stmt = $pdo->prepare('DELETE FROM fniun WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Return success response
            http_response_code(200);
            echo json_encode(['message' => 'Fniun deleted successfully']);
        }
    ]
];

// Get request method and route
$method = $_SERVER['REQUEST_METHOD'];
$route = $_SERVER['REQUEST_URI'];

// Parse route parameters
$matches = [];
preg_match('/\/fniun\/([0-9]+)/', $route, $matches);
$id = $matches[1];

// Call route handler
if (isset($routes[$method][$route])) {
    $handler = $routes[$method][$route];
    if ($id) {
        $handler($id);
    } else {
        $handler();
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
}