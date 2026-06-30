<?php
require_once 'db.php';

// Get the HTTP request method
$method = $_SERVER['REQUEST_METHOD'];

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get the user role
$userRole = $_SESSION['user_role'];

// Handle GET request
if ($method === 'GET') {
    // Check if the user is an admin to allow deletion
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get the project ID from the URL query string
    $projectId = $_GET['id'] ?? null;

    // Validate the project ID
    if (!$projectId) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid project ID']);
        exit;
    }

    // Sanitize the project ID
    $projectId = intval($projectId);

    // Prepare the SQL query to select a project
    $sql = 'SELECT * FROM مشاريع WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $projectId);
    $stmt->execute();

    // Fetch the project data
    $project = $stmt->fetch();

    // Check if the project exists
    if (!$project) {
        http_response_code(404);
        echo json_encode(['error' => 'Project not found']);
        exit;
    }

    // Return the project data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($project);
}

// Handle POST request
elseif ($method === 'POST') {
    // Get the project data from the request body
    $postData = json_decode(file_get_contents('php://input'), true);

    // Validate the project data
    if (!$postData || !isset($postData['name']) || !isset($postData['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid project data']);
        exit;
    }

    // Sanitize the project data
    $name = trim($postData['name']);
    $description = trim($postData['description']);

    // Prepare the SQL query to insert a project
    $sql = 'INSERT INTO مشاريع (name, description) VALUES (:name, :description)';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Get the last inserted project ID
    $projectId = $pdo->lastInsertId();

    // Return the project ID
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $projectId]);
}

// Handle PUT request
elseif ($method === 'PUT') {
    // Check if the user is an admin to allow edits
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get the project ID from the URL query string
    $projectId = $_GET['id'] ?? null;

    // Validate the project ID
    if (!$projectId) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid project ID']);
        exit;
    }

    // Sanitize the project ID
    $projectId = intval($projectId);

    // Get the project data from the request body
    $postData = json_decode(file_get_contents('php://input'), true);

    // Validate the project data
    if (!$postData || !isset($postData['name']) || !isset($postData['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid project data']);
        exit;
    }

    // Sanitize the project data
    $name = trim($postData['name']);
    $description = trim($postData['description']);

    // Prepare the SQL query to update a project
    $sql = 'UPDATE مشاريع SET name = :name, description = :description WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':id', $projectId);
    $stmt->execute();

    // Check if the project was updated
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Project not found']);
        exit;
    }

    // Return a success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Project updated successfully']);
}

// Handle DELETE request
elseif ($method === 'DELETE') {
    // Check if the user is an admin to allow deletions
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get the project ID from the URL query string
    $projectId = $_GET['id'] ?? null;

    // Validate the project ID
    if (!$projectId) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid project ID']);
        exit;
    }

    // Sanitize the project ID
    $projectId = intval($projectId);

    // Prepare the SQL query to delete a project
    $sql = 'DELETE FROM مشاريع WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $projectId);
    $stmt->execute();

    // Check if the project was deleted
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Project not found']);
        exit;
    }

    // Return a success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Project deleted successfully']);
}