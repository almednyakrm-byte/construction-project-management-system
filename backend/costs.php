<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Initialize database connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET requests
if ($method == 'GET') {
    // Validate and sanitize input
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
    
    // SQL query structure: Select all costs or a specific cost by id
    if ($id) {
        $stmt = $pdo->prepare('SELECT * FROM costs WHERE id = :id');
        $stmt->bindParam(':id', $id);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM costs');
    }
    
    // Execute query
    $stmt->execute();
    
    // Output processing: Return costs in JSON format
    $costs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($costs);
}

// Handle POST requests
elseif ($method == 'POST') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    
    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize input
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $amount = filter_var($data['amount'] ?? null, FILTER_VALIDATE_FLOAT);
    $date = filter_var($data['date'] ?? null, FILTER_SANITIZE_STRING);
    
    // Check if input data is valid
    if (!$name || !$amount || !$date) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }
    
    // SQL query structure: Insert new cost
    $stmt = $pdo->prepare('INSERT INTO costs (name, amount, date) VALUES (:name, :amount, :date)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':date', $date);
    
    // Execute query
    $stmt->execute();
    
    // Output processing: Return created cost id in JSON format
    $id = $pdo->lastInsertId();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $id]);
}

// Handle PUT requests
elseif ($method == 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    
    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize input
    $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $amount = filter_var($data['amount'] ?? null, FILTER_VALIDATE_FLOAT);
    $date = filter_var($data['date'] ?? null, FILTER_SANITIZE_STRING);
    
    // Check if input data is valid
    if (!$id || !$name || !$amount || !$date) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }
    
    // SQL query structure: Update existing cost
    $stmt = $pdo->prepare('UPDATE costs SET name = :name, amount = :amount, date = :date WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':date', $date);
    
    // Execute query
    $stmt->execute();
    
    // Output processing: Return updated cost id in JSON format
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['id' => $id]);
}

// Handle DELETE requests
elseif ($method == 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    
    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize input
    $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    
    // Check if input data is valid
    if (!$id) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }
    
    // SQL query structure: Delete existing cost
    $stmt = $pdo->prepare('DELETE FROM costs WHERE id = :id');
    $stmt->bindParam(':id', $id);
    
    // Execute query
    $stmt->execute();
    
    // Output processing: Return deleted cost id in JSON format
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['id' => $id]);
}

// Handle invalid request methods
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}