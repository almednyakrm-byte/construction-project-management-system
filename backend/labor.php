<?php
// Import database connection
require_once 'db.php';

// Initialize database connection
$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);

// Function to validate user role
function validateUserRole($role) {
    // For this example, assume we have a function to get the current user's role
    $currentUserRole = getCurrentUserRole();
    if ($currentUserRole !== $role) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
}

// Function to get current user's role
function getCurrentUserRole() {
    // For this example, assume we have a session variable to store the user's role
    return $_SESSION['user_role'] ?? null;
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate user role
    validateUserRole('user');

    // Prepare SQL query to select all labors
    $stmt = $pdo->prepare('SELECT * FROM labor');
    $stmt->execute();

    // Fetch all labors
    $labors = $stmt->fetchAll();

    // Set HTTP response status code and headers
    http_response_code(200);
    header('Content-Type: application/json');

    // Output labors in JSON format
    echo json_encode($labors);
}

// Handle POST requests
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate user role
    validateUserRole('admin');

    // Read input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($inputData['name'], $inputData['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    // Sanitize input data
    $name = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($inputData['description'], FILTER_SANITIZE_STRING);

    // Prepare SQL query to insert new labor
    $stmt = $pdo->prepare('INSERT INTO labor (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);

    // Execute SQL query
    if ($stmt->execute()) {
        // Set HTTP response status code and headers
        http_response_code(201);
        header('Content-Type: application/json');

        // Output inserted labor in JSON format
        echo json_encode(['message' => 'Labor created successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create labor']);
    }
}

// Handle PUT requests
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate user role
    validateUserRole('admin');

    // Read input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($inputData['id'], $inputData['name'], $inputData['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($inputData['description'], FILTER_SANITIZE_STRING);

    // Prepare SQL query to update labor
    $stmt = $pdo->prepare('UPDATE labor SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);

    // Execute SQL query
    if ($stmt->execute()) {
        // Set HTTP response status code and headers
        http_response_code(200);
        header('Content-Type: application/json');

        // Output updated labor in JSON format
        echo json_encode(['message' => 'Labor updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update labor']);
    }
}

// Handle DELETE requests
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate user role
    validateUserRole('admin');

    // Read input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);

    // Prepare SQL query to delete labor
    $stmt = $pdo->prepare('DELETE FROM labor WHERE id = :id');
    $stmt->bindParam(':id', $id);

    // Execute SQL query
    if ($stmt->execute()) {
        // Set HTTP response status code and headers
        http_response_code(200);
        header('Content-Type: application/json');

        // Output deleted labor in JSON format
        echo json_encode(['message' => 'Labor deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete labor']);
    }
}

// Handle invalid request methods
else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}