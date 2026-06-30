<?php
require_once 'db.php';

// Get user data from session
$userData = $_SESSION['userData'];

// Check if user is logged in
if (!isset($userData['id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Function to validate input data
function validateInput($data) {
    $errors = array();
    if (!isset($data['name']) || empty($data['name'])) {
        $errors[] = 'Name is required';
    }
    if (!isset($data['description']) || empty($data['description'])) {
        $errors[] = 'Description is required';
    }
    return $errors;
}

// Function to sanitize input data
function sanitizeInput($data) {
    $sanitizedData = array();
    $sanitizedData['name'] = trim($data['name']);
    $sanitizedData['description'] = trim($data['description']);
    return $sanitizedData;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is admin
    if ($userData['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get all teams
    $stmt = $pdo->prepare('SELECT * FROM teams');
    $stmt->execute();
    $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return teams in JSON format
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($teams);
}

// Handle POST request
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input data
    $errors = validateInput($inputData);
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode(array('error' => $errors));
        exit;
    }

    // Sanitize input data
    $sanitizedData = sanitizeInput($inputData);

    // Insert team into database
    $stmt = $pdo->prepare('INSERT INTO teams (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $sanitizedData['name']);
    $stmt->bindParam(':description', $sanitizedData['description']);
    $stmt->execute();

    // Return inserted team in JSON format
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $pdo->lastInsertId()));
}

// Handle PUT request
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if ($userData['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    $errors = validateInput($inputData);
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode(array('error' => $errors));
        exit;
    }

    // Sanitize input data
    $sanitizedData = sanitizeInput($inputData);

    // Get team ID from URL
    $teamId = $_GET['id'];

    // Update team in database
    $stmt = $pdo->prepare('UPDATE teams SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $teamId);
    $stmt->bindParam(':name', $sanitizedData['name']);
    $stmt->bindParam(':description', $sanitizedData['description']);
    $stmt->execute();

    // Return updated team in JSON format
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $teamId));
}

// Handle DELETE request
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if ($userData['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get team ID from URL
    $teamId = $_GET['id'];

    // Delete team from database
    $stmt = $pdo->prepare('DELETE FROM teams WHERE id = :id');
    $stmt->bindParam(':id', $teamId);
    $stmt->execute();

    // Return deleted team in JSON format
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $teamId));
}