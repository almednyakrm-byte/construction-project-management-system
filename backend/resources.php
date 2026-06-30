<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Get request data
$data = json_decode(file_get_contents('php://input'), true);
if (empty($data)) {
    $data = $_POST;
}

// Connect to database
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET requests
if ($method == 'GET') {
    // Validate and sanitize input
    $id = isset($data['id']) ? filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT) : null;

    // SQL query structure
    if ($id) {
        $stmt = $pdo->prepare('SELECT * FROM resources WHERE id = :id');
        $stmt->bindParam(':id', $id);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM resources');
    }

    // Execute query
    $stmt->execute();

    // Output processing
    $resources = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($resources);
}

// Handle POST requests
elseif ($method == 'POST') {
    // Validate and sanitize input
    $name = isset($data['name']) ? filter_var($data['name'], FILTER_SANITIZE_STRING) : null;
    $description = isset($data['description']) ? filter_var($data['description'], FILTER_SANITIZE_STRING) : null;

    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden access']);
        exit;
    }

    // SQL query structure
    $stmt = $pdo->prepare('INSERT INTO resources (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);

    // Execute query
    if ($stmt->execute()) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Resource created successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to create resource']);
    }
}

// Handle PUT requests
elseif ($method == 'PUT') {
    // Validate and sanitize input
    $id = isset($data['id']) ? filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT) : null;
    $name = isset($data['name']) ? filter_var($data['name'], FILTER_SANITIZE_STRING) : null;
    $description = isset($data['description']) ? filter_var($data['description'], FILTER_SANITIZE_STRING) : null;

    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden access']);
        exit;
    }

    // SQL query structure
    $stmt = $pdo->prepare('UPDATE resources SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);

    // Execute query
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Resource updated successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to update resource']);
    }
}

// Handle DELETE requests
elseif ($method == 'DELETE') {
    // Validate and sanitize input
    $id = isset($data['id']) ? filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT) : null;

    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden access']);
        exit;
    }

    // SQL query structure
    $stmt = $pdo->prepare('DELETE FROM resources WHERE id = :id');
    $stmt->bindParam(':id', $id);

    // Execute query
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Resource deleted successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to delete resource']);
    }
}

// Close database connection
$pdo = null;

?>