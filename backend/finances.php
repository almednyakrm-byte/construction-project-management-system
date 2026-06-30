<?php
require_once 'db.php';

// Initialize PDO connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Get request data
$data = json_decode(file_get_contents('php://input'), true);
if (empty($data)) {
    $data = $_POST;
}

// Define user roles
$roles = ['admin', 'user'];

// Validate user role
if (!in_array($_SESSION['user_role'], $roles)) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

// Handle GET requests
if ($method == 'GET') {
    // Validate query parameters
    if (isset($data['id'])) {
        $id = filter_var($data['id'], FILTER_VALIDATE_INT);
        if ($id === false) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid id']);
            exit;
        }
    }

    // Prepare SQL query
    if (isset($id)) {
        $stmt = $pdo->prepare('SELECT * FROM finances WHERE id = :id');
        $stmt->bindParam(':id', $id);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM finances');
    }

    // Execute query
    try {
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($result);
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Handle POST requests
if ($method == 'POST') {
    // Validate request data
    if (!isset($data['name']) || !isset($data['amount'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    // Sanitize request data
    $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
    $amount = filter_var($data['amount'], FILTER_VALIDATE_FLOAT);

    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('INSERT INTO finances (name, amount) VALUES (:name, :amount)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':amount', $amount);

    // Execute query
    try {
        $stmt->execute();
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Finance added successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Handle PUT requests
if ($method == 'PUT') {
    // Validate request data
    if (!isset($data['id']) || !isset($data['name']) || !isset($data['amount'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    // Sanitize request data
    $id = filter_var($data['id'], FILTER_VALIDATE_INT);
    $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
    $amount = filter_var($data['amount'], FILTER_VALIDATE_FLOAT);

    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('UPDATE finances SET name = :name, amount = :amount WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':amount', $amount);

    // Execute query
    try {
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Finance updated successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Handle DELETE requests
if ($method == 'DELETE') {
    // Validate request data
    if (!isset($data['id'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    // Sanitize request data
    $id = filter_var($data['id'], FILTER_VALIDATE_INT);

    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('DELETE FROM finances WHERE id = :id');
    $stmt->bindParam(':id', $id);

    // Execute query
    try {
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Finance deleted successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Internal Server Error']);
    }
}