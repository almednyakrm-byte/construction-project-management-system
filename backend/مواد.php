<?php
require_once 'db.php';

// Get user data from session
$user = $_SESSION['user'];

// Check if user is logged in
if (!$user) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data from JSON or POST
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

// Define validation rules
$validationRules = array(
    'name' => array('required' => true, 'min' => 3, 'max' => 255),
    'description' => array('required' => false, 'min' => 3, 'max' => 255),
);

// Validate input data
foreach ($validationRules as $field => $rules) {
    if (isset($input[$field])) {
        if (isset($rules['required']) && empty($input[$field])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Validation failed'));
            exit;
        }
        if (isset($rules['min']) && strlen($input[$field]) < $rules['min']) {
            http_response_code(400);
            echo json_encode(array('error' => 'Validation failed'));
            exit;
        }
        if (isset($rules['max']) && strlen($input[$field]) > $rules['max']) {
            http_response_code(400);
            echo json_encode(array('error' => 'Validation failed'));
            exit;
        }
    }
}

// Sanitize input data
$input['name'] = htmlspecialchars($input['name']);
$input['description'] = htmlspecialchars($input['description']);

// Handle CRUD operations
if (isset($_GET['id'])) {
    // GET operation
    $stmt = $pdo->prepare('SELECT * FROM materials WHERE id = :id');
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $material = $stmt->fetch();
    if (!$material) {
        http_response_code(404);
        echo json_encode(array('error' => 'Material not found'));
        exit;
    }
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($material);
} elseif (isset($_POST['id'])) {
    // PUT operation
    if ($user['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    $stmt = $pdo->prepare('UPDATE materials SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $_POST['id']);
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Material updated successfully'));
} elseif (isset($_POST['delete_id'])) {
    // DELETE operation
    if ($user['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    $stmt = $pdo->prepare('DELETE FROM materials WHERE id = :id');
    $stmt->bindParam(':id', $_POST['delete_id']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Material deleted successfully'));
} else {
    // POST operation
    $stmt = $pdo->prepare('INSERT INTO materials (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Material created successfully'));
}